<?php

namespace App\Services;

use App\Models\User;
use App\Models\Lesson;
use App\Models\UserProgress;
use App\Models\StudyActivity;
use App\Models\DailyStreak;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgressService
{
    public function __construct(
        protected CacheService $cacheService
    ) {}

    /**
     * Update user progress for a lesson when shadowing exercise is completed
     */
    public function updateShadowingProgress(User $user, int $lessonId): void
    {
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lessonId,
            ],
            [
                'phrases_viewed' => 0,
                'dialogues_viewed' => 0,
                'drills_completed' => 0,
                'shadowing_completed' => 0,
                'completion_percentage' => 0,
            ]
        );

        // Increment shadowing completed count
        $progress->increment('shadowing_completed');
        $progress->last_accessed_at = now();
        
        // Recalculate completion percentage
        $this->calculateLessonProgress($user, $lessonId);
        
        $progress->save();
        
        // Clear progress cache
        $this->cacheService->clearProgressCache($user);
    }

    /**
     * Calculate lesson progress percentage based on completed activities
     */
    public function calculateLessonProgress(User $user, int $lessonId): float
    {
        $lesson = Lesson::with(['phrases', 'dialogues', 'drills', 'shadowingExercises'])->find($lessonId);
        
        if (!$lesson) {
            return 0;
        }

        $progress = UserProgress::where('user_id', $user->id)
            ->where('lesson_id', $lessonId)
            ->first();

        if (!$progress) {
            return 0;
        }

        // Calculate total possible activities
        $totalPhrases = $lesson->phrases->count();
        $totalDialogues = $lesson->dialogues->count();
        $totalDrills = $lesson->drills->count();
        $totalShadowing = $lesson->shadowingExercises->count();

        // Weight each activity type
        $weights = [
            'phrases' => 0.25,
            'dialogues' => 0.25,
            'drills' => 0.25,
            'shadowing' => 0.25,
        ];

        $completionScore = 0;

        // Calculate phrases completion (viewing all phrases = 100%)
        if ($totalPhrases > 0) {
            $phrasesCompletion = min(1, $progress->phrases_viewed / $totalPhrases);
            $completionScore += $phrasesCompletion * $weights['phrases'];
        }

        // Calculate dialogues completion (viewing all dialogues = 100%)
        if ($totalDialogues > 0) {
            $dialoguesCompletion = min(1, $progress->dialogues_viewed / $totalDialogues);
            $completionScore += $dialoguesCompletion * $weights['dialogues'];
        }

        // Calculate drills completion (completing all drills = 100%)
        if ($totalDrills > 0) {
            $drillsCompletion = min(1, $progress->drills_completed / $totalDrills);
            $completionScore += $drillsCompletion * $weights['drills'];
        }

        // Calculate shadowing completion (completing all exercises = 100%)
        if ($totalShadowing > 0) {
            $shadowingCompletion = min(1, $progress->shadowing_completed / $totalShadowing);
            $completionScore += $shadowingCompletion * $weights['shadowing'];
        }

        $percentage = $completionScore * 100;

        // Update the progress record
        $progress->completion_percentage = $percentage;
        $progress->save();

        return $percentage;
    }

    /**
     * Get overall progress across all lessons
     */
    public function getOverallProgress(User $user): array
    {
        $allProgress = UserProgress::where('user_id', $user->id)
            ->with('lesson')
            ->get();

        $totalLessons = Lesson::count();
        $completedLessons = $allProgress->where('completion_percentage', '>=', 100)->count();
        $inProgressLessons = $allProgress->where('completion_percentage', '>', 0)
            ->where('completion_percentage', '<', 100)
            ->count();

        $averageCompletion = $allProgress->avg('completion_percentage') ?? 0;

        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'in_progress_lessons' => $inProgressLessons,
            'average_completion' => round($averageCompletion, 2),
            'lessons' => $allProgress,
        ];
    }

    /**
     * Update user progress for a specific activity type
     */
    public function updateProgress(User $user, int $lessonId, string $activityType): void
    {
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lessonId,
            ],
            [
                'phrases_viewed' => 0,
                'dialogues_viewed' => 0,
                'drills_completed' => 0,
                'shadowing_completed' => 0,
                'completion_percentage' => 0,
            ]
        );

        // Increment the appropriate counter based on activity type
        switch ($activityType) {
            case 'phrase_view':
                $progress->increment('phrases_viewed');
                break;
            case 'dialogue_view':
                $progress->increment('dialogues_viewed');
                break;
            case 'drill_complete':
                $progress->increment('drills_completed');
                break;
            case 'shadowing_complete':
                $progress->increment('shadowing_completed');
                break;
        }

        // Update last accessed timestamp
        $progress->last_accessed_at = now();
        $progress->save();

        // Recalculate completion percentage
        $this->calculateLessonProgress($user, $lessonId);
    }

    /**
     * Get daily statistics for a specific date
     */
    public function getDailyStats(User $user, \Carbon\Carbon $date): array
    {
        $dateString = $date->toDateString();

        // Get daily streak record
        $dailyStreak = \App\Models\DailyStreak::where('user_id', $user->id)
            ->where('date', $dateString)
            ->first();

        // Get all activities for the day
        $activities = \App\Models\StudyActivity::where('user_id', $user->id)
            ->whereDate('created_at', $dateString)
            ->get();

        // Calculate statistics
        $totalActivities = $activities->count();
        $totalDuration = $activities->sum('duration_seconds');
        $totalXp = $activities->sum('xp_earned');

        // Break down by activity type
        $activityBreakdown = $activities->groupBy('activity_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'duration' => $group->sum('duration_seconds'),
                'xp' => $group->sum('xp_earned'),
            ];
        })->toArray();

        return [
            'date' => $dateString,
            'total_activities' => $totalActivities,
            'total_duration_seconds' => $totalDuration,
            'total_duration_minutes' => round($totalDuration / 60, 2),
            'total_xp' => $totalXp,
            'activity_breakdown' => $activityBreakdown,
            'daily_streak_record' => $dailyStreak,
        ];
    }

    /**
     * Get weekly statistics (last 7 days)
     */
    public function getWeeklyStats(User $user): array
    {
        $endDate = now();
        $startDate = now()->subDays(6)->startOfDay();

        // Get daily streaks for the week
        $dailyStreaks = \App\Models\DailyStreak::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date')
            ->get();

        // Get all activities for the week
        $activities = \App\Models\StudyActivity::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Calculate totals
        $totalActivities = $activities->count();
        $totalDuration = $activities->sum('duration_seconds');
        $totalXp = $activities->sum('xp_earned');

        // Calculate daily averages
        $daysWithActivity = $dailyStreaks->count();
        $avgDailyActivities = $daysWithActivity > 0 ? round($totalActivities / 7, 2) : 0;
        $avgDailyMinutes = $daysWithActivity > 0 ? round($totalDuration / 60 / 7, 2) : 0;
        $avgDailyXp = $daysWithActivity > 0 ? round($totalXp / 7, 2) : 0;

        // Build daily breakdown
        $dailyBreakdown = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays(6 - $i)->toDateString();
            $dayStreak = $dailyStreaks->firstWhere('date', $date);
            
            $dailyBreakdown[] = [
                'date' => $date,
                'activities_count' => $dayStreak->activities_count ?? 0,
                'study_minutes' => $dayStreak->study_minutes ?? 0,
                'xp_earned' => $dayStreak->xp_earned ?? 0,
            ];
        }

        // Activity type breakdown
        $activityTypeBreakdown = $activities->groupBy('activity_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'duration' => $group->sum('duration_seconds'),
                'xp' => $group->sum('xp_earned'),
            ];
        })->toArray();

        return [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_activities' => $totalActivities,
            'total_duration_seconds' => $totalDuration,
            'total_duration_minutes' => round($totalDuration / 60, 2),
            'total_xp' => $totalXp,
            'days_with_activity' => $daysWithActivity,
            'avg_daily_activities' => $avgDailyActivities,
            'avg_daily_minutes' => $avgDailyMinutes,
            'avg_daily_xp' => $avgDailyXp,
            'daily_breakdown' => $dailyBreakdown,
            'activity_type_breakdown' => $activityTypeBreakdown,
        ];
    }

    /**
     * Update user's daily streak
     */
    public function updateStreak(User $user): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        // Get user profile
        $profile = $user->profile;
        if (!$profile) {
            return;
        }

        // Check if user had activity yesterday
        $yesterdayStreak = \App\Models\DailyStreak::where('user_id', $user->id)
            ->where('date', $yesterday)
            ->exists();

        // Check if user has activity today
        $hasActivityToday = \App\Models\StudyActivity::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();

        if ($hasActivityToday) {
            // If user had activity yesterday, increment streak
            if ($yesterdayStreak || $profile->current_streak == 0) {
                $profile->current_streak += 1;
            } else {
                // Streak was broken, reset to 1
                $profile->current_streak = 1;
            }

            // Update longest streak if current is higher
            if ($profile->current_streak > $profile->longest_streak) {
                $profile->longest_streak = $profile->current_streak;
            }

            $profile->save();
        } else {
            // No activity today, check if streak should be broken
            // Give a grace period - only break streak if no activity yesterday either
            if (!$yesterdayStreak && $profile->current_streak > 0) {
                $profile->current_streak = 0;
                $profile->save();
            }
        }
    }

    /**
     * Calculate current streak based on consecutive days with activity
     */
    public function calculateStreak(User $user): int
    {
        $profile = $user->profile;
        if (!$profile) {
            return 0;
        }

        $today = now()->startOfDay();
        $currentDate = $today->copy();
        $streak = 0;
        $gracePeriodUsed = false;

        // Check backwards from today
        while (true) {
            $dateString = $currentDate->toDateString();
            
            $hasActivity = DailyStreak::where('user_id', $user->id)
                ->where('date', $dateString)
                ->exists();

            if ($hasActivity) {
                $streak++;
                $currentDate->subDay();
            } else {
                // Check if we can use grace period (one day skip allowed)
                if (!$gracePeriodUsed && $streak > 0) {
                    $gracePeriodUsed = true;
                    $currentDate->subDay();
                    continue;
                }
                break;
            }
        }

        return $streak;
    }

    /**
     * Check if user can use streak recovery
     */
    public function canUseStreakRecovery(User $user): bool
    {
        $profile = $user->profile;
        if (!$profile) {
            return false;
        }

        // Check if user has used streak recovery in the last 30 days
        $lastRecovery = $profile->last_streak_recovery_at;
        
        if (!$lastRecovery) {
            return true;
        }

        return $lastRecovery->diffInDays(now()) >= 30;
    }

    /**
     * Use streak recovery to restore a broken streak
     */
    public function useStreakRecovery(User $user): bool
    {
        if (!$this->canUseStreakRecovery($user)) {
            return false;
        }

        $profile = $user->profile;
        $yesterday = now()->subDay()->toDateString();

        // Create a DailyStreak record for yesterday with minimal activity
        DailyStreak::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $yesterday,
            ],
            [
                'activities_count' => 1,
                'study_minutes' => 0,
                'xp_earned' => 0,
            ]
        );

        // Recalculate streak
        $newStreak = $this->calculateStreak($user);
        $profile->current_streak = $newStreak;
        $profile->last_streak_recovery_at = now();
        $profile->save();

        return true;
    }

    /**
     * Get streak calendar data for the last N days
     */
    public function getStreakCalendar(User $user, int $days = 28): array
    {
        $endDate = now();
        $startDate = now()->subDays($days - 1)->startOfDay();

        $streaks = DailyStreak::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy('date');

        $calendar = [];
        $currentDate = $startDate->copy();

        for ($i = 0; $i < $days; $i++) {
            $dateString = $currentDate->toDateString();
            $streak = $streaks->get($dateString);

            $calendar[] = [
                'date' => $dateString,
                'day' => $currentDate->format('D'),
                'dayNumber' => $currentDate->format('j'),
                'hasActivity' => $streak !== null,
                'activities_count' => $streak->activities_count ?? 0,
                'study_minutes' => $streak->study_minutes ?? 0,
                'xp_earned' => $streak->xp_earned ?? 0,
            ];

            $currentDate->addDay();
        }

        return $calendar;
    }
}
