<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Display all achievements (earned and locked)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get all achievements
        $allAchievements = Achievement::orderBy('requirement_value')->get();
        
        // Get user's achievement progress
        $userAchievements = $user->achievements()
            ->withPivot('progress', 'earned_at')
            ->get()
            ->keyBy('id');
        
        // Organize achievements by earned/locked status
        $achievements = $allAchievements->map(function ($achievement) use ($userAchievements, $user) {
            $userAchievement = $userAchievements->get($achievement->id);
            
            // Calculate current progress
            $currentProgress = $this->calculateProgress($user, $achievement);
            
            return [
                'id' => $achievement->id,
                'slug' => $achievement->slug,
                'name' => $achievement->name,
                'description' => $achievement->description,
                'icon' => $achievement->icon,
                'xp_reward' => $achievement->xp_reward,
                'requirement_type' => $achievement->requirement_type,
                'requirement_value' => $achievement->requirement_value,
                'earned' => $userAchievement && $userAchievement->pivot->earned_at !== null,
                'earned_at' => $userAchievement?->pivot->earned_at,
                'progress' => $currentProgress,
                'progress_percentage' => $achievement->requirement_value > 0 
                    ? min(100, round(($currentProgress / $achievement->requirement_value) * 100, 2))
                    : 0,
            ];
        });
        
        // Split into earned and locked
        $earnedAchievements = $achievements->where('earned', true)->values();
        $lockedAchievements = $achievements->where('earned', false)->values();
        
        return view('achievements.index', [
            'earnedAchievements' => $earnedAchievements,
            'lockedAchievements' => $lockedAchievements,
            'totalAchievements' => $allAchievements->count(),
            'earnedCount' => $earnedAchievements->count(),
        ]);
    }

    /**
     * Calculate current progress for an achievement
     */
    protected function calculateProgress($user, Achievement $achievement): int
    {
        return (int) match ($achievement->requirement_type) {
            'flashcard_reviews' => $user->flashcardReviews()->count(),
            'streak' => $user->profile?->current_streak ?? 0,
            'lessons_completed' => $user->progress()
                ->where('completion_percentage', '>=', 100)
                ->count(),
            'exercises_completed' => $user->exerciseAttempts()->count(),
            'perfect_exercise' => $user->exerciseAttempts()
                ->where('score', '>=', 100)
                ->count(),
            'shadowing_completed' => $user->shadowingCompletions()->count(),
            'study_minutes' => (int) floor($user->studyActivities()->sum('duration_seconds') / 60),
            'level' => $user->profile?->level ?? 1,
            default => 0,
        };
    }
}
