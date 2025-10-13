<?php

namespace App\Observers;

use App\Models\ShadowingCompletion;
use App\Models\StudyActivity;
use App\Models\DailyStreak;
use App\Services\GamificationService;
use App\Services\ProgressService;

class ShadowingCompletionObserver
{
    protected GamificationService $gamificationService;
    protected ProgressService $progressService;

    public function __construct(GamificationService $gamificationService, ProgressService $progressService)
    {
        $this->gamificationService = $gamificationService;
        $this->progressService = $progressService;
    }

    /**
     * Handle the ShadowingCompletion "created" event.
     */
    public function created(ShadowingCompletion $completion): void
    {
        $user = $completion->user;
        $exercise = $completion->exercise;

        // Award standard XP for shadowing completion
        $xpEarned = GamificationService::XP_SHADOWING_COMPLETION;

        // Bonus XP if user recorded their voice
        if ($completion->recording_id) {
            $xpEarned += 10; // Bonus for recording
        }

        // Log study activity FIRST (so updateStreak can find it)
        StudyActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'shadowing',
            'activityable_type' => ShadowingCompletion::class,
            'activityable_id' => $completion->id,
            'duration_seconds' => $completion->duration_seconds ?? 0,
            'xp_earned' => $xpEarned,
        ]);

        // Update daily streak (must be after StudyActivity creation)
        $this->updateDailyStreak($user, $completion->duration_seconds ?? 0, $xpEarned);

        // Award XP to user and check for level up
        $levelUpData = $this->gamificationService->awardXP($user, $xpEarned, 'Shadowing completion');
        
        // Store level up data in session if user leveled up
        if ($levelUpData) {
            session()->flash('level_up_data', $levelUpData);
        }

        // Update user progress for the lesson
        if ($exercise && $exercise->lesson_id) {
            $this->progressService->updateProgress(
                $user,
                $exercise->lesson_id,
                'shadowing_complete'
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
