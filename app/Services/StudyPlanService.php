<?php

namespace App\Services;

use App\Models\User;
use App\Models\DailyPlan;
use App\Models\Lesson;
use App\Models\UserProgress;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudyPlanService
{
    /**
     * Get daily plan for a specific date (generates if doesn't exist)
     */
    public function getDailyPlan(User $user, string $date): ?DailyPlan
    {
        $normalized = Carbon::parse($date)->copy()->startOfDay();

        $plan = DailyPlan::where('user_id', $user->id)
            ->whereDate('date', $normalized->toDateString())
            ->first();

        if (!$plan) {
            $plan = $this->generateDailyPlan($user, $normalized);
        }

        return $plan;
    }

    /**
     * Generate a daily study plan for the user based on their goals and progress
     */
    public function generateDailyPlan(User $user, Carbon $date): DailyPlan
    {
        // Normalize the date to start of day to avoid UNIQUE collisions on different time representations
        $normalized = $date->copy()->startOfDay();

        // Check if plan already exists for this date
        $existingPlan = DailyPlan::where('user_id', $user->id)
            ->whereDate('date', $normalized->toDateString())
            ->first();

        if ($existingPlan) {
            return $existingPlan;
        }

        // Get user profile with goals
        $profile = $user->profile;
        if (!$profile) {
            // Create default profile if it doesn't exist
            $profile = $user->profile()->create([
                'level' => 1,
                'total_xp' => 0,
                'current_streak' => 0,
                'longest_streak' => 0,
                'study_goal_minutes' => 120, // 2 hours default
                'cards_per_day_goal' => 20,
            ]);
        }

        // Get recommended activities
        $activities = $this->getRecommendedActivities($user);

        // Build the plan data
        $planData = [
            'activities' => $activities,
            'estimated_total_minutes' => array_sum(array_column($activities, 'estimated_minutes')),
            'goal_minutes' => $profile->study_goal_minutes,
        ];

        // Create or update the daily plan (idempotent for the same day)
        try {
            $plan = DailyPlan::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date' => $normalized->toDateString(),
                ],
                [
                    'plan_data' => $planData,
                    'completed_activities' => 0,
                    'total_activities' => count($activities),
                ]
            );
        } catch (\Illuminate\Database\QueryException $e) {
            // In case of a rare race condition, fetch the existing plan (no 404)
            $plan = DailyPlan::where('user_id', $user->id)
                ->whereDate('date', $normalized->toDateString())
                ->first();
            if (!$plan) {
                // As a last resort, create a fresh plan
                $plan = DailyPlan::create([
                    'user_id' => $user->id,
                    'date' => $normalized->toDateString(),
                    'plan_data' => $planData,
                    'completed_activities' => 0,
                    'total_activities' => count($activities),
                ]);
            }
        }

        return $plan;
    }

    /**
     * Get recommended activities based on user progress and goals
     */
    public function getRecommendedActivities(User $user): array
    {
        $activities = [];
        $profile = $user->profile;

        if (!$profile) {
            return [];
        }

        // 1. Flashcard Reviews (Priority: Due cards first)
        $dueCardsCount = $user->flashcards()->due()->count();
        $newCardsCount = $user->flashcards()->new()->count();
        
        if ($dueCardsCount > 0) {
            $activities[] = [
                'id' => 'flashcard_review',
                'type' => 'flashcard_review',
                'title' => 'Review Due Flashcards',
                'description' => "Review {$dueCardsCount} cards that are due today",
                'estimated_minutes' => min(30, ceil($dueCardsCount * 0.5)), // ~30 seconds per card
                'target_count' => $dueCardsCount,
                'priority' => 1,
                'completed' => false,
            ];
        }

        // 2. New Flashcards (if goal not met)
        $cardsPerDayGoal = $profile->cards_per_day_goal;
        $newCardsToLearn = min($newCardsCount, max(0, $cardsPerDayGoal - $dueCardsCount));
        
        if ($newCardsToLearn > 0) {
            $activities[] = [
                'id' => 'flashcard_new',
                'type' => 'flashcard_review',
                'title' => 'Learn New Flashcards',
                'description' => "Learn {$newCardsToLearn} new cards",
                'estimated_minutes' => ceil($newCardsToLearn * 1), // ~1 minute per new card
                'target_count' => $newCardsToLearn,
                'priority' => 2,
                'completed' => false,
            ];
        }

        // 3. New Content (Next incomplete lesson)
        $nextLesson = $this->getNextLessonToStudy($user);
        
        if ($nextLesson) {
            $activities[] = [
                'id' => "lesson_{$nextLesson->id}",
                'type' => 'content_view',
                'title' => "Study: {$nextLesson->title}",
                'description' => 'Review phrases and dialogues from this lesson',
                'estimated_minutes' => 20,
                'lesson_id' => $nextLesson->id,
                'priority' => 3,
                'completed' => false,
            ];
        }

        // 4. Practice Exercises (from lessons in progress)
        $lessonsInProgress = $this->getLessonsInProgress($user);
        
        if ($lessonsInProgress->isNotEmpty()) {
            $lesson = $lessonsInProgress->first();
            $incompleteDrills = $this->getIncompleteDrills($user, $lesson);
            
            if ($incompleteDrills > 0) {
                $activities[] = [
                    'id' => "drills_{$lesson->id}",
                    'type' => 'exercise',
                    'title' => "Practice Drills: {$lesson->title}",
                    'description' => "Complete {$incompleteDrills} drill exercises",
                    'estimated_minutes' => min(25, $incompleteDrills * 5),
                    'lesson_id' => $lesson->id,
                    'target_count' => $incompleteDrills,
                    'priority' => 4,
                    'completed' => false,
                ];
            }
        }

        // 5. Shadowing Practice (from lessons in progress)
        if ($lessonsInProgress->isNotEmpty()) {
            $lesson = $lessonsInProgress->first();
            $incompleteShadowing = $this->getIncompleteShadowing($user, $lesson);
            
            if ($incompleteShadowing > 0) {
                $activities[] = [
                    'id' => "shadowing_{$lesson->id}",
                    'type' => 'shadowing',
                    'title' => "Shadowing Practice: {$lesson->title}",
                    'description' => "Practice {$incompleteShadowing} shadowing exercises",
                    'estimated_minutes' => min(20, $incompleteShadowing * 5),
                    'lesson_id' => $lesson->id,
                    'target_count' => $incompleteShadowing,
                    'priority' => 5,
                    'completed' => false,
                ];
            }
        }

        // If no activities found, suggest starting the first lesson
        if (empty($activities)) {
            $firstLesson = Lesson::orderBy('order')->first();
            
            if ($firstLesson) {
                $activities[] = [
                    'id' => "lesson_{$firstLesson->id}",
                    'type' => 'content_view',
                    'title' => "Start: {$firstLesson->title}",
                    'description' => 'Begin your Japanese learning journey!',
                    'estimated_minutes' => 30,
                    'lesson_id' => $firstLesson->id,
                    'priority' => 1,
                    'completed' => false,
                ];
            }
        }

        return $activities;
    }

    /**
     * Get the next lesson the user should study
     */
    private function getNextLessonToStudy(User $user): ?Lesson
    {
        // Get all lessons with user progress
        $lessons = Lesson::with(['userProgress' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->orderBy('order')->get();

        // Find first lesson that's not 100% complete
        foreach ($lessons as $lesson) {
            $progress = $lesson->userProgress->first();
            
            if (!$progress || $progress->completion_percentage < 100) {
                return $lesson;
            }
        }

        // All lessons complete, return null
        return null;
    }

    /**
     * Get lessons that are in progress (started but not completed)
     */
    private function getLessonsInProgress(User $user)
    {
        return Lesson::whereHas('userProgress', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('completion_percentage', '>', 0)
                ->where('completion_percentage', '<', 100);
        })->orderBy('order')->get();
    }

    /**
     * Get count of incomplete drills for a lesson
     */
    private function getIncompleteDrills(User $user, Lesson $lesson): int
    {
        $totalDrills = $lesson->drills()->count();
        
        // Count completed drills
        $completedDrills = DB::table('exercise_attempts')
            ->join('drills', 'exercise_attempts.drill_id', '=', 'drills.id')
            ->where('exercise_attempts.user_id', $user->id)
            ->where('drills.lesson_id', $lesson->id)
            ->distinct('exercise_attempts.drill_id')
            ->count('exercise_attempts.drill_id');

        return max(0, $totalDrills - $completedDrills);
    }

    /**
     * Get count of incomplete shadowing exercises for a lesson
     */
    private function getIncompleteShadowing(User $user, Lesson $lesson): int
    {
        $totalShadowing = $lesson->shadowingExercises()->count();
        
        // Count completed shadowing exercises
        $completedShadowing = DB::table('shadowing_completions')
            ->join('shadowing_exercises', 'shadowing_completions.shadowing_exercise_id', '=', 'shadowing_exercises.id')
            ->where('shadowing_completions.user_id', $user->id)
            ->where('shadowing_exercises.lesson_id', $lesson->id)
            ->distinct('shadowing_completions.shadowing_exercise_id')
            ->count('shadowing_completions.shadowing_exercise_id');

        return max(0, $totalShadowing - $completedShadowing);
    }
}

