<?php

namespace App\Observers;

use App\Models\ExerciseAttempt;
use App\Models\StudyActivity;
use App\Models\DailyStreak;
use App\Services\GamificationService;
use App\Services\ProgressService;

class ExerciseAttemptObserver
{
    protected GamificationService $gamificationService;
    protected ProgressService $progressService;

    public function __construct(GamificationService $gamificationService, ProgressService $progressService)
    {
        $this->gamificationService = $gamificationService;
        $this->progressService = $progressService;
    }

    /**
     * Handle the ExerciseAttempt "created" event.
     */
    public function created(ExerciseAttempt $attempt): void
    {
        $user = $attempt->user;
        $drill = $attempt->drill;

        // Calculate XP based on score (better performance = more XP)
        $baseXp = GamificationService::XP_EXERCISE_COMPLETION;
        $scoreMultiplier = $attempt->score / 100; // 0.0 to 1.0
        $xpEarned = (int) round($baseXp * $scoreMultiplier);
        
        // Minimum XP for attempting
        $xpEarned = max($xpEarned, 10);

        // Log study activity FIRST (so updateStreak can find it)
        StudyActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'exercise',
            'activityable_type' => ExerciseAttempt::class,
            'activityable_id' => $attempt->id,
            'duration_seconds' => $attempt->duration_seconds ?? 0,
            'xp_earned' => $xpEarned,
        ]);

        // Update daily streak (must be after StudyActivity creation)
        $this->updateDailyStreak($user, $attempt->duration_seconds ?? 0, $xpEarned);

        // Award XP to user and check for level up
        $levelUpData = $this->gamificationService->awardXP($user, $xpEarned, 'Exercise completion');
        
        // Store level up data in session if user leveled up
        if ($levelUpData) {
            session()->flash('level_up_data', $levelUpData);
        }

        // Update user progress for the lesson
        if ($drill && $drill->lesson_id) {
            $this->progressService->updateProgress(
                $user,
                $drill->lesson_id,
                'drill_complete'
            );
        }

        // Check for newly earned achievements
        $this->gamificationService->checkAchievements($user);
    }

    /**
     * Update or create daily streak record
     */
    protected function updateDailyStreak($user, int $durationSeconds, int $xpEarned): void
    {
        $today = now()->toDateString();

        \DB::transaction(function () use ($user, $today, $durationSeconds, $xpEarned) {
            // Use lockForUpdate to prevent race conditions
            $dailyStreak = DailyStreak::where('user_id', $user->id)
                ->where('date', $today)
                ->lockForUpdate()
                ->first();

            if ($dailyStreak) {
                $dailyStreak->increment('activities_count');
                $dailyStreak->increment('study_minutes', (int) ceil($durationSeconds / 60));
                $dailyStreak->increment('xp_earned', $xpEarned);
            } else {
                DailyStreak::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'activities_count' => 1,
                    'study_minutes' => (int) ceil($durationSeconds / 60),
                    'xp_earned' => $xpEarned,
                ]);
            }
        });

        // Update user's streak counter in profile
        $this->progressService->updateStreak($user);
    }
}
