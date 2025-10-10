<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Flashcard;
use App\Models\Lesson;
use App\Models\UserProgress;
use App\Models\DailyStreak;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Get flashcard statistics
        $cardsDueToday = Flashcard::where('user_id', $user->id)
            ->where('next_review_at', '<=', now())
            ->count();

        $newCardsAvailable = Flashcard::where('user_id', $user->id)
            ->where('repetitions', 0)
            ->where('next_review_at', '<=', now())
            ->count();

        $upcomingReviews = Flashcard::where('user_id', $user->id)
            ->where('next_review_at', '>', now())
            ->where('next_review_at', '<=', now()->addDays(7))
            ->count();

        // Get level and XP information
        $currentLevel = $profile->level ?? 1;
        $totalXp = $profile->total_xp ?? 0;
        $xpForNextLevel = $this->getXpForNextLevel($currentLevel);
        $xpForCurrentLevel = $this->getXpForLevel($currentLevel);
        $xpProgress = $totalXp - $xpForCurrentLevel;
        $xpNeeded = $xpForNextLevel - $xpForCurrentLevel;
        $xpProgressPercentage = $xpNeeded > 0 ? ($xpProgress / $xpNeeded) * 100 : 0;

        // Get streak information
        $currentStreak = $profile->current_streak ?? 0;

        // Get today's study time
        $todayStreak = DailyStreak::where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->first();
        $studyTimeToday = $todayStreak ? $todayStreak->study_minutes : 0;

        // Get recent lessons accessed
        $recentLessons = UserProgress::where('user_id', $user->id)
            ->with('lesson')
            ->orderBy('last_accessed_at', 'desc')
            ->limit(5)
            ->get()
            ->pluck('lesson')
            ->filter();

        // Get overall progress
        $totalLessons = Lesson::count();
        $completedLessons = UserProgress::where('user_id', $user->id)
            ->where('completion_percentage', '>=', 100)
            ->count();
        $overallProgress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

        return view('dashboard.index', compact(
            'user',
            'profile',
            'cardsDueToday',
            'newCardsAvailable',
            'upcomingReviews',
            'currentLevel',
            'totalXp',
            'xpForNextLevel',
            'xpProgress',
            'xpNeeded',
            'xpProgressPercentage',
            'currentStreak',
            'studyTimeToday',
            'recentLessons',
            'overallProgress'
        ));
    }

    /**
     * Calculate XP required for a given level
     */
    private function getXpForLevel(int $level): int
    {
        if ($level <= 1) {
            return 0;
        }
        // Formula: 100 * level^1.5
        return (int) (100 * pow($level - 1, 1.5));
    }

    /**
     * Calculate XP required for next level
     */
    private function getXpForNextLevel(int $currentLevel): int
    {
        return (int) (100 * pow($currentLevel, 1.5));
    }
}
