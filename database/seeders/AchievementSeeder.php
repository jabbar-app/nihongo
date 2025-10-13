<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            // Flashcard Achievements
            [
                'slug' => 'first-review',
                'name' => 'First Review',
                'description' => 'Complete your first flashcard review',
                'icon' => '🎴',
                'xp_reward' => 10,
                'requirement_type' => 'flashcard_reviews',
                'requirement_value' => 1,
            ],
            [
                'slug' => 'card-collector',
                'name' => 'Card Collector',
                'description' => 'Review 100 flashcards',
                'icon' => '📚',
                'xp_reward' => 50,
                'requirement_type' => 'flashcard_reviews',
                'requirement_value' => 100,
            ],
            [
                'slug' => 'card-master',
                'name' => 'Card Master',
                'description' => 'Review 500 flashcards',
                'icon' => '🏆',
                'xp_reward' => 100,
                'requirement_type' => 'flashcard_reviews',
                'requirement_value' => 500,
            ],
            [
                'slug' => 'card-legend',
                'name' => 'Card Legend',
                'description' => 'Review 1000 flashcards',
                'icon' => '👑',
                'xp_reward' => 200,
                'requirement_type' => 'flashcard_reviews',
                'requirement_value' => 1000,
            ],

            // Streak Achievements
            [
                'slug' => 'getting-started',
                'name' => 'Getting Started',
                'description' => 'Maintain a 3-day study streak',
                'icon' => '🔥',
                'xp_reward' => 25,
                'requirement_type' => 'streak',
                'requirement_value' => 3,
            ],
            [
                'slug' => 'week-warrior',
                'name' => 'Week Warrior',
                'description' => 'Maintain a 7-day study streak',
                'icon' => '⚡',
                'xp_reward' => 75,
                'requirement_type' => 'streak',
                'requirement_value' => 7,
            ],
            [
                'slug' => 'month-master',
                'name' => 'Month Master',
                'description' => 'Maintain a 30-day study streak',
                'icon' => '🌟',
                'xp_reward' => 250,
                'requirement_type' => 'streak',
                'requirement_value' => 30,
            ],
            [
                'slug' => 'dedication-champion',
                'name' => 'Dedication Champion',
                'description' => 'Maintain a 100-day study streak',
                'icon' => '💎',
                'xp_reward' => 500,
                'requirement_type' => 'streak',
                'requirement_value' => 100,
            ],

            // Lesson Completion Achievements
            [
                'slug' => 'first-lesson',
                'name' => 'First Lesson',
                'description' => 'Complete your first lesson',
                'icon' => '📖',
                'xp_reward' => 50,
                'requirement_type' => 'lessons_completed',
                'requirement_value' => 1,
            ],
            [
                'slug' => 'lesson-master',
                'name' => 'Lesson Master',
                'description' => 'Complete 5 lessons',
                'icon' => '🎓',
                'xp_reward' => 150,
                'requirement_type' => 'lessons_completed',
                'requirement_value' => 5,
            ],
            [
                'slug' => 'course-champion',
                'name' => 'Course Champion',
                'description' => 'Complete all 9 lessons',
                'icon' => '🏅',
                'xp_reward' => 300,
                'requirement_type' => 'lessons_completed',
                'requirement_value' => 9,
            ],

            // Exercise Achievements
            [
                'slug' => 'exercise-beginner',
                'name' => 'Exercise Beginner',
                'description' => 'Complete 10 exercises',
                'icon' => '💪',
                'xp_reward' => 30,
                'requirement_type' => 'exercises_completed',
                'requirement_value' => 10,
            ],
            [
                'slug' => 'exercise-enthusiast',
                'name' => 'Exercise Enthusiast',
                'description' => 'Complete 50 exercises',
                'icon' => '🎯',
                'xp_reward' => 100,
                'requirement_type' => 'exercises_completed',
                'requirement_value' => 50,
            ],
            [
                'slug' => 'exercise-expert',
                'name' => 'Exercise Expert',
                'description' => 'Complete 100 exercises',
                'icon' => '🥇',
                'xp_reward' => 200,
                'requirement_type' => 'exercises_completed',
                'requirement_value' => 100,
            ],
            [
                'slug' => 'perfectionist',
                'name' => 'Perfectionist',
                'description' => 'Score 100% on 10 exercises',
                'icon' => '✨',
                'xp_reward' => 150,
                'requirement_type' => 'perfect_exercise',
                'requirement_value' => 10,
            ],

            // Shadowing Achievements
            [
                'slug' => 'first-shadow',
                'name' => 'First Shadow',
                'description' => 'Complete your first shadowing exercise',
                'icon' => '🎤',
                'xp_reward' => 25,
                'requirement_type' => 'shadowing_completed',
                'requirement_value' => 1,
            ],
            [
                'slug' => 'voice-actor',
                'name' => 'Voice Actor',
                'description' => 'Complete 20 shadowing exercises',
                'icon' => '🎭',
                'xp_reward' => 100,
                'requirement_type' => 'shadowing_completed',
                'requirement_value' => 20,
            ],
            [
                'slug' => 'pronunciation-pro',
                'name' => 'Pronunciation Pro',
                'description' => 'Complete 50 shadowing exercises',
                'icon' => '🗣️',
                'xp_reward' => 200,
                'requirement_type' => 'shadowing_completed',
                'requirement_value' => 50,
            ],

            // Study Time Achievements
            [
                'slug' => 'study-starter',
                'name' => 'Study Starter',
                'description' => 'Study for 60 minutes total',
                'icon' => '⏰',
                'xp_reward' => 20,
                'requirement_type' => 'study_minutes',
                'requirement_value' => 60,
            ],
            [
                'slug' => 'dedicated-learner',
                'name' => 'Dedicated Learner',
                'description' => 'Study for 10 hours total',
                'icon' => '📚',
                'xp_reward' => 100,
                'requirement_type' => 'study_minutes',
                'requirement_value' => 600,
            ],
            [
                'slug' => 'study-marathon',
                'name' => 'Study Marathon',
                'description' => 'Study for 50 hours total',
                'icon' => '🏃',
                'xp_reward' => 300,
                'requirement_type' => 'study_minutes',
                'requirement_value' => 3000,
            ],
            [
                'slug' => 'time-master',
                'name' => 'Time Master',
                'description' => 'Study for 100 hours total',
                'icon' => '⌛',
                'xp_reward' => 500,
                'requirement_type' => 'study_minutes',
                'requirement_value' => 6000,
            ],

            // Level Achievements
            [
                'slug' => 'level-5',
                'name' => 'Rising Star',
                'description' => 'Reach level 5',
                'icon' => '⭐',
                'xp_reward' => 50,
                'requirement_type' => 'level',
                'requirement_value' => 5,
            ],
            [
                'slug' => 'level-10',
                'name' => 'Skilled Learner',
                'description' => 'Reach level 10',
                'icon' => '🌠',
                'xp_reward' => 100,
                'requirement_type' => 'level',
                'requirement_value' => 10,
            ],
            [
                'slug' => 'level-25',
                'name' => 'Expert Student',
                'description' => 'Reach level 25',
                'icon' => '💫',
                'xp_reward' => 250,
                'requirement_type' => 'level',
                'requirement_value' => 25,
            ],
            [
                'slug' => 'level-50',
                'name' => 'Master of Japanese',
                'description' => 'Reach level 50',
                'icon' => '🌌',
                'xp_reward' => 500,
                'requirement_type' => 'level',
                'requirement_value' => 50,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['slug' => $achievement['slug']],
                $achievement
            );
        }
    }
}
