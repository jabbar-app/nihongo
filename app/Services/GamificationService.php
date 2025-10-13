<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\LevelUp;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * XP rewards for different activities
     */
    public const XP_FLASHCARD_REVIEW = 5;
    public const XP_EXERCISE_COMPLETION = 20;
    public const XP_SHADOWING_COMPLETION = 25;
    public const XP_LESSON_COMPLETION = 100;
    public const XP_ACHIEVEMENT_UNLOCK = 50;

    /**
     * XP thresholds for each level
     * Level 1: 0 XP
     * Level 2: 100 XP
     * Level 3: 250 XP
     * Level 4: 450 XP
     * And so on with increasing requirements
     */
    protected array $xpThresholds = [
        1 => 0,
        2 => 100,
        3 => 250,
        4 => 450,
        5 => 700,
        6 => 1000,
        7 => 1350,
        8 => 1750,
        9 => 2200,
        10 => 2700,
        11 => 3250,
        12 => 3850,
        13 => 4500,
        14 => 5200,
        15 => 5950,
        16 => 6750,
        17 => 7600,
        18 => 8500,
        19 => 9450,
        20 => 10450,
        21 => 11500,
        22 => 12600,
        23 => 13750,
        24 => 14950,
        25 => 16200,
        26 => 17500,
        27 => 18850,
        28 => 20250,
        29 => 21700,
        30 => 23200,
        31 => 24750,
        32 => 26350,
        33 => 28000,
        34 => 29700,
        35 => 31450,
        36 => 33250,
        37 => 35100,
        38 => 37000,
        39 => 38950,
        40 => 40950,
        41 => 43000,
        42 => 45100,
        43 => 47250,
        44 => 49450,
        45 => 51700,
        46 => 54000,
        47 => 56350,
        48 => 58750,
        49 => 61200,
        50 => 63700,
    ];

    /**
     * Award XP to a user and check for level up
     * Returns array with level_up info if user leveled up
     */
    public function awardXP(User $user, int $xp, string $reason): ?array
    {
        return DB::transaction(function () use ($user, $xp, $reason) {
            $profile = $user->profile;
            
            if (!$profile) {
                // Create profile if it doesn't exist
                $profile = UserProfile::create([
                    'user_id' => $user->id,
                    'level' => 1,
                    'total_xp' => 0,
                    'current_streak' => 0,
                    'longest_streak' => 0,
                    'study_goal_minutes' => 120,
                    'cards_per_day_goal' => 20,
                ]);
            }

            // Add XP to user's total
            $oldXp = $profile->total_xp;
            $profile->total_xp += $xp;
            
            // Check for level up
            $oldLevel = $profile->level;
            $newLevel = $this->calculateLevel($profile->total_xp);
            
            $levelUpInfo = null;
            
            if ($newLevel > $oldLevel) {
                $profile->level = $newLevel;
                
                // Calculate bonus XP for milestone levels
                $bonusXp = $this->getMilestoneBonusXp($newLevel);
                
                if ($bonusXp > 0) {
                    $profile->total_xp += $bonusXp;
                }
                
                // Record the level up
                $levelUp = LevelUp::create([
                    'user_id' => $user->id,
                    'old_level' => $oldLevel,
                    'new_level' => $newLevel,
                    'xp_at_levelup' => $profile->total_xp - $bonusXp,
                    'bonus_xp' => $bonusXp,
                ]);
                
                $levelUpInfo = [
                    'old_level' => $oldLevel,
                    'new_level' => $newLevel,
                    'xp_earned' => $xp,
                    'bonus_xp' => $bonusXp,
                    'total_xp' => $profile->total_xp,
                    'unlocked_features' => $this->getUnlockedFeatures($newLevel),
                ];
            }
            
            $profile->save();
            
            return $levelUpInfo;
        });
    }

    /**
     * Get bonus XP for milestone levels
     */
    protected function getMilestoneBonusXp(int $level): int
    {
        return match ($level) {
            10 => 500,
            25 => 1000,
            50 => 2500,
            default => 0,
        };
    }

    /**
     * Get features unlocked at a specific level
     */
    protected function getUnlockedFeatures(int $level): array
    {
        $features = [];
        
        if ($level === 5) {
            $features[] = 'Advanced exercise tracking';
        }
        
        if ($level === 10) {
            $features[] = 'Custom study plans';
            $features[] = 'Milestone bonus: 500 XP';
        }
        
        if ($level === 15) {
            $features[] = 'Achievement showcase';
        }
        
        if ($level === 20) {
            $features[] = 'Advanced analytics';
        }
        
        if ($level === 25) {
            $features[] = 'Master learner status';
            $features[] = 'Milestone bonus: 1000 XP';
        }
        
        if ($level === 50) {
            $features[] = 'Legend status';
            $features[] = 'Milestone bonus: 2500 XP';
        }
        
        return $features;
    }

    /**
     * Calculate level based on total XP
     */
    public function calculateLevel(int $totalXp): int
    {
        $level = 1;
        
        foreach ($this->xpThresholds as $lvl => $threshold) {
            if ($totalXp >= $threshold) {
                $level = $lvl;
            } else {
                break;
            }
        }
        
        return $level;
    }

    /**
     * Check if user has leveled up (returns new level if leveled up, null otherwise)
     */
    public function checkLevelUp(User $user): ?int
    {
        $profile = $user->profile;
        
        if (!$profile) {
            return null;
        }
        
        $currentLevel = $profile->level;
        $calculatedLevel = $this->calculateLevel($profile->total_xp);
        
        if ($calculatedLevel > $currentLevel) {
            // Update the level
            $profile->level = $calculatedLevel;
            $profile->save();
            
            return $calculatedLevel;
        }
        
        return null;
    }

    /**
     * Get XP required for next level
     */
    public function getXpForNextLevel(int $currentLevel): int
    {
        // If we have a defined threshold for the next level, return it
        if (isset($this->xpThresholds[$currentLevel + 1])) {
            return $this->xpThresholds[$currentLevel + 1];
        }
        
        // For levels beyond our defined thresholds, use a formula
        // Each level requires 250 more XP than the previous level
        $lastDefinedLevel = max(array_keys($this->xpThresholds));
        $lastDefinedXp = $this->xpThresholds[$lastDefinedLevel];
        
        $levelsAbove = ($currentLevel + 1) - $lastDefinedLevel;
        return $lastDefinedXp + ($levelsAbove * 250);
    }

    /**
     * Get XP progress to next level (returns array with current, required, and percentage)
     */
    public function getXpProgress(User $user): array
    {
        $profile = $user->profile;
        
        if (!$profile) {
            return [
                'current_level' => 1,
                'current_xp' => 0,
                'xp_for_current_level' => 0,
                'xp_for_next_level' => $this->xpThresholds[2],
                'xp_progress' => 0,
                'xp_remaining' => $this->xpThresholds[2],
                'progress_percentage' => 0,
            ];
        }
        
        $currentLevel = $profile->level;
        $totalXp = $profile->total_xp;
        $xpForCurrentLevel = $this->xpThresholds[$currentLevel] ?? 0;
        $xpForNextLevel = $this->getXpForNextLevel($currentLevel);
        
        $xpProgress = $totalXp - $xpForCurrentLevel;
        $xpRequired = $xpForNextLevel - $xpForCurrentLevel;
        $progressPercentage = $xpRequired > 0 ? round(($xpProgress / $xpRequired) * 100, 2) : 100;
        
        return [
            'current_level' => $currentLevel,
            'current_xp' => $totalXp,
            'xp_for_current_level' => $xpForCurrentLevel,
            'xp_for_next_level' => $xpForNextLevel,
            'xp_progress' => $xpProgress,
            'xp_remaining' => $xpForNextLevel - $totalXp,
            'progress_percentage' => min(100, max(0, $progressPercentage)),
        ];
    }

    /**
     * Check for newly earned achievements and award them
     * Returns collection of newly earned achievements
     */
    public function checkAchievements(User $user): Collection
    {
        $newlyEarned = collect();
        
        // Get all achievements
        $achievements = Achievement::all();
        
        // Get user's current achievement progress
        $userAchievements = $user->achievements()
            ->withPivot('progress', 'earned_at')
            ->get()
            ->keyBy('id');
        
        foreach ($achievements as $achievement) {
            // Skip if already earned
            $userAchievement = $userAchievements->get($achievement->id);
            if ($userAchievement && $userAchievement->pivot->earned_at) {
                continue;
            }
            
            // Calculate current progress for this achievement
            $currentProgress = $this->calculateAchievementProgress($user, $achievement);
            
            // Check if achievement is now earned
            if ($currentProgress >= $achievement->requirement_value) {
                // Award the achievement
                $user->achievements()->syncWithoutDetaching([
                    $achievement->id => [
                        'progress' => $currentProgress,
                        'earned_at' => now(),
                    ]
                ]);
                
                // Award bonus XP
                $this->awardXP($user, $achievement->xp_reward, "Achievement unlocked: {$achievement->name}");
                
                $newlyEarned->push($achievement);
            } else {
                // Update progress even if not earned yet
                $user->achievements()->syncWithoutDetaching([
                    $achievement->id => [
                        'progress' => $currentProgress,
                        'earned_at' => null,
                    ]
                ]);
            }
        }
        
        return $newlyEarned;
    }

    /**
     * Calculate current progress for a specific achievement
     */
    protected function calculateAchievementProgress(User $user, Achievement $achievement): int
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
