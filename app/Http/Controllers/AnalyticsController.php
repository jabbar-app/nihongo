<?php

namespace App\Http\Controllers;

use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudyActivity;
use App\Models\DailyStreak;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Get analytics data for the dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $range = $request->input('range', 'week');

        // Determine date range
        [$startDate, $endDate, $days] = $this->getDateRange($range);

        // Get key metrics
        $metrics = $this->getKeyMetrics($user, $startDate, $endDate, $days);

        // Get study time data
        $studyTimeData = $this->getStudyTimeData($user, $startDate, $endDate, $days);

        // Get activity breakdown
        $activityBreakdown = $this->getActivityBreakdown($user, $startDate, $endDate);

        // Get XP data
        $xpData = $this->getXpData($user, $startDate, $endDate, $days);

        // Get streak calendar (last 28 days for calendar view)
        $streakCalendar = $this->getStreakCalendar($user);

        return response()->json([
            'metrics' => $metrics,
            'studyTimeData' => $studyTimeData,
            'activityBreakdown' => $activityBreakdown,
            'xpData' => $xpData,
            'streakCalendar' => $streakCalendar,
        ]);
    }

    /**
     * Determine date range based on filter
     */
    protected function getDateRange(string $range): array
    {
        $endDate = now();
        
        switch ($range) {
            case 'month':
                $startDate = now()->subDays(29)->startOfDay();
                $days = 30;
                break;
            case 'all':
                $startDate = StudyActivity::where('user_id', Auth::id())
                    ->orderBy('created_at')
                    ->first()
                    ?->created_at ?? now()->subDays(6);
                $days = max(7, $startDate->diffInDays($endDate) + 1);
                break;
            case 'week':
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $days = 7;
                break;
        }

        return [$startDate, $endDate, $days];
    }

    /**
     * Get key metrics
     */
    protected function getKeyMetrics($user, $startDate, $endDate, $days): array
    {
        // Get activities in range
        $activities = StudyActivity::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalSeconds = $activities->sum('duration_seconds');
        $totalMinutes = round($totalSeconds / 60, 0);
        $totalHours = round($totalSeconds / 3600, 1);
        
        $avgDailyMinutes = $days > 0 ? round($totalMinutes / $days, 0) : 0;

        // Get overall progress
        $overallProgress = $this->progressService->getOverallProgress($user);
        $completionRate = round($overallProgress['average_completion'], 1);

        // Get streak info
        $profile = $user->profile;
        $currentStreak = $profile->current_streak ?? 0;
        $longestStreak = $profile->longest_streak ?? 0;

        return [
            'totalHours' => $totalHours,
            'totalMinutes' => $totalMinutes,
            'avgDailyMinutes' => $avgDailyMinutes,
            'completionRate' => $completionRate,
            'currentStreak' => $currentStreak,
            'longestStreak' => $longestStreak,
        ];
    }

    /**
     * Get study time data for chart
     */
    protected function getStudyTimeData($user, $startDate, $endDate, $days): array
    {
        $dailyStreaks = DailyStreak::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy('date');

        $maxMinutes = $dailyStreaks->max('study_minutes') ?: 1;

        $data = [];
        $displayDays = min($days, 30); // Limit display to 30 days max
        $step = $days > 30 ? ceil($days / 30) : 1;

        for ($i = 0; $i < $displayDays; $i++) {
            $date = now()->subDays(($displayDays - 1 - $i) * $step);
            $dateString = $date->toDateString();
            $streak = $dailyStreaks->get($dateString);

            $minutes = $streak->study_minutes ?? 0;
            $activities = $streak->activities_count ?? 0;

            $data[] = [
                'label' => $date->format('M d'),
                'minutes' => $minutes,
                'activities' => $activities,
                'percentage' => $maxMinutes > 0 ? round(($minutes / $maxMinutes) * 100, 2) : 0,
            ];
        }

        return $data;
    }

    /**
     * Get activity breakdown for pie chart
     */
    protected function getActivityBreakdown($user, $startDate, $endDate): array
    {
        $activities = StudyActivity::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $total = $activities->count();

        $breakdown = $activities->groupBy('activity_type')->map(function ($group, $type) use ($total) {
            $count = $group->count();
            $duration = round($group->sum('duration_seconds') / 60, 0);
            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;

            return [
                'label' => $this->formatActivityType($type),
                'count' => $count,
                'duration' => $duration,
                'percentage' => $percentage,
                'color' => $this->getActivityColor($type),
            ];
        });

        return [
            'total' => $total,
            'types' => $breakdown,
        ];
    }

    /**
     * Get XP data for line chart
     */
    protected function getXpData($user, $startDate, $endDate, $days): array
    {
        $dailyStreaks = DailyStreak::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy('date');

        $maxXp = $dailyStreaks->max('xp_earned') ?: 1;

        $data = [];
        $displayDays = min($days, 30);
        $step = $days > 30 ? ceil($days / 30) : 1;

        for ($i = 0; $i < $displayDays; $i++) {
            $date = now()->subDays(($displayDays - 1 - $i) * $step);
            $dateString = $date->toDateString();
            $streak = $dailyStreaks->get($dateString);

            $xp = $streak->xp_earned ?? 0;

            $data[] = [
                'label' => $date->format('M d'),
                'xp' => $xp,
                'percentage' => $maxXp > 0 ? round(($xp / $maxXp) * 100, 2) : 0,
            ];
        }

        return $data;
    }

    /**
     * Get streak calendar (last 28 days)
     */
    protected function getStreakCalendar($user): array
    {
        $endDate = now();
        $startDate = now()->subDays(27)->startOfDay();

        $dailyStreaks = DailyStreak::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy('date');

        $calendar = [];
        for ($i = 0; $i < 28; $i++) {
            $date = now()->subDays(27 - $i);
            $dateString = $date->toDateString();
            $streak = $dailyStreaks->get($dateString);

            $calendar[] = [
                'day' => $date->format('D'),
                'date' => $date->format('j'),
                'hasActivity' => $streak && $streak->activities_count > 0,
                'minutes' => $streak->study_minutes ?? 0,
            ];
        }

        return $calendar;
    }

    /**
     * Format activity type for display
     */
    protected function formatActivityType(string $type): string
    {
        return match($type) {
            'flashcard_review' => 'Flashcards',
            'exercise' => 'Exercises',
            'shadowing' => 'Shadowing',
            'content_view' => 'Content',
            default => ucfirst(str_replace('_', ' ', $type)),
        };
    }

    /**
     * Get color for activity type
     */
    protected function getActivityColor(string $type): string
    {
        return match($type) {
            'flashcard_review' => '#3b82f6', // blue
            'exercise' => '#10b981', // green
            'shadowing' => '#8b5cf6', // purple
            'content_view' => '#f59e0b', // yellow
            default => '#6b7280', // gray
        };
    }
}
