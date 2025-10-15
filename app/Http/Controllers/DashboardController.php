<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Flashcard;
use App\Models\Lesson;
use App\Models\UserProgress;
use App\Models\DailyStreak;
use App\Services\GamificationService;
use App\Services\RecentlyViewedService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected GamificationService $gamificationService;
    protected RecentlyViewedService $recentlyViewedService;

    public function __construct(
        GamificationService $gamificationService,
        RecentlyViewedService $recentlyViewedService
    ) {
        $this->gamificationService = $gamificationService;
        $this->recentlyViewedService = $recentlyViewedService;
    }

    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Get flashcard statistics (cached for 5 minutes)
        $cardsDueToday = cache()->remember(
            "user.{$user->id}.cards_due_today",
            300,
            fn() => Flashcard::where('user_id', $user->id)
                ->where('next_review_at', '<=', now())
                ->count()
        );

        $newCardsAvailable = cache()->remember(
            "user.{$user->id}.new_cards_available",
            300,
            fn() => Flashcard::where('user_id', $user->id)
                ->where('repetitions', 0)
                ->where('next_review_at', '<=', now())
                ->count()
        );

        $upcomingReviews = cache()->remember(
            "user.{$user->id}.upcoming_reviews",
            300,
            fn() => Flashcard::where('user_id', $user->id)
                ->where('next_review_at', '>', now())
                ->where('next_review_at', '<=', now()->addDays(7))
                ->count()
        );

        // Get level and XP information using GamificationService
        $xpProgressData = $this->gamificationService->getXpProgress($user);
        $currentLevel = $xpProgressData['current_level'];
        $totalXp = $xpProgressData['current_xp'];
        $xpForNextLevel = $xpProgressData['xp_for_next_level'];
        $xpProgress = $xpProgressData['xp_progress'];
        $xpNeeded = $xpForNextLevel - $xpProgressData['xp_for_current_level'];
        $xpProgressPercentage = $xpProgressData['progress_percentage'];

        // Get streak information
        $currentStreak = $profile->current_streak ?? 0;

        // Get today's study time
        $todayStreak = DailyStreak::where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->first();
        $studyTimeToday = $todayStreak ? $todayStreak->study_minutes : 0;

        // Get speaking-focused metrics
        $studyGoalMinutes = $profile->study_goal_minutes ?? 30;
        $todaySpeakingProgress = $studyGoalMinutes > 0 ? min(($studyTimeToday / $studyGoalMinutes) * 100, 100) : 0;
        
        // Get conversations mastered (completed lessons)
        $totalLessons = cache()->remember('total_lessons_count', 3600, fn() => Lesson::count());
        $conversationsMastered = cache()->remember(
            "user.{$user->id}.completed_lessons",
            600,
            fn() => UserProgress::where('user_id', $user->id)
                ->where('completion_percentage', '>=', 100)
                ->count()
        );
        
        // Get total speaking time (all time study minutes)
        $totalSpeakingMinutes = cache()->remember(
            "user.{$user->id}.total_speaking_minutes",
            600,
            fn() => DailyStreak::where('user_id', $user->id)->sum('study_minutes')
        );
        $totalSpeakingHours = round($totalSpeakingMinutes / 60, 1);

        // Get recent lessons accessed (with eager loading and caching)
        $recentLessons = cache()->remember(
            "user.{$user->id}.recent_lessons",
            600,
            fn() => UserProgress::where('user_id', $user->id)
                ->with('lesson')
                ->orderBy('last_accessed_at', 'desc')
                ->limit(5)
                ->get()
                ->pluck('lesson')
                ->filter()
        );

        // Get overall progress (cached for 10 minutes)
        $overallProgress = $totalLessons > 0 ? ($conversationsMastered / $totalLessons) * 100 : 0;

        // Get recent achievements (last 3)
        $recentAchievements = $user->achievements()
            ->wherePivot('earned_at', '!=', null)
            ->orderByPivot('earned_at', 'desc')
            ->limit(3)
            ->get();

        // Get recently viewed items
        $recentlyViewed = $this->recentlyViewedService->getRecentItemsWithModels($user, 5);

        // Get last viewed lesson for "Continue Learning" button
        $lastViewedLesson = $this->recentlyViewedService->getLastViewedOfType($user, 'lesson');
        
        // Get current lesson in progress
        $currentLesson = UserProgress::where('user_id', $user->id)
            ->where('completion_percentage', '>', 0)
            ->where('completion_percentage', '<', 100)
            ->with('lesson')
            ->orderBy('last_accessed_at', 'desc')
            ->first();
        
        // Check if user is new (no activity)
        $isNewUser = $totalSpeakingMinutes === 0 && $conversationsMastered === 0;

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
            'studyGoalMinutes',
            'todaySpeakingProgress',
            'conversationsMastered',
            'totalLessons',
            'totalSpeakingMinutes',
            'totalSpeakingHours',
            'recentLessons',
            'overallProgress',
            'recentAchievements',
            'recentlyViewed',
            'lastViewedLesson',
            'currentLesson',
            'isNewUser'
        ));
    }
}
