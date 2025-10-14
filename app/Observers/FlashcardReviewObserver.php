<?php

namespace App\Observers;

use App\Models\FlashcardReview;
use App\Models\StudyActivity;
use App\Models\DailyStreak;
use App\Services\GamificationService;
use App\Services\ProgressService;

class FlashcardReviewObserver
{
    protected GamificationService $gamificationService;
    protected ProgressService $progressService;

    public function __construct(GamificationService $gamificationService, ProgressService $progressService)
    {
        $this->gamificationService = $gamificationService;
        $this->progressService = $progressService;
    }

    /**
     * Handle the FlashcardReview "created" event.
     */
    public function created(FlashcardReview $review): void
    {
        $flashcard = $review->flashcard;
        $user = $flashcard->user;

        // Calculate XP based on rating (better performance = more XP)
        $xpEarned = match($review->rating) {
            1 => 2,  // Again - minimal XP
            2 => 3,  // Hard - some XP
            3 => 5,  // Good - standard XP
            4 => 7,  // Easy - bonus XP
            default => 5,
        };

        // Log study activity FIRST (so updateStreak can find it)
        StudyActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'flashcard_review',
            'activityable_type' => FlashcardReview::class,
            'activityable_id' => $review->id,
            'duration_seconds' => $review->duration_seconds ?? 0,
            'xp_earned' => $xpEarned,
        ]);

        // Update daily streak (must be after StudyActivity creation)
        $this->updateDailyStreak($user, $review->duration_seconds ?? 0, $xpEarned);

        // Award XP to user and check for level up
        $levelUpData = $this->gamificationService->awardXP($user, $xpEarned, 'Flashcard review');
        
        // Store level up data in session if user leveled up
        if ($levelUpData) {
            session()->flash('level_up_data', $levelUpData);
        }

        // Update user progress if flashcard is linked to a phrase with a lesson
        if ($flashcard->phrase && $flashcard->phrase->lesson_id) {
            $this->progressService->updateProgress(
                $user,
                $flashcard->phrase->lesson_id,
                'phrase_view'
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
            // Idempotent upsert to avoid UNIQUE constraint violations
            $values = [
                'activities_count' => \DB::raw('activities_count + 1'),
                'study_minutes' => \DB::raw('study_minutes + ' . (int) ceil($durationSeconds / 60)),
                'xp_earned' => \DB::raw('xp_earned + ' . $xpEarned),
                'updated_at' => now(),
            ];

            $insert = [
                'user_id' => $user->id,
                'date' => $today,
                'activities_count' => 1,
                'study_minutes' => (int) ceil($durationSeconds / 60),
                'xp_earned' => $xpEarned,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Try atomic update; if zero rows affected, insert; if insert conflicts, fallback to update
            $affected = DailyStreak::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->update($values);

            if ($affected === 0) {
                try {
                    DailyStreak::create($insert);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Another request inserted concurrently; perform update
                    DailyStreak::where('user_id', $user->id)
                        ->whereDate('date', $today)
                        ->update($values);
                }
            }
        });

        // Update user's streak counter in profile
        $this->progressService->updateStreak($user);
    }
}
