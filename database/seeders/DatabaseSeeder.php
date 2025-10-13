<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting comprehensive database seeding...');
        $this->command->newLine();

        // Step 1: Seed all lesson content (phrases, dialogues, drills, shadowing)
        $this->command->info('📚 Step 1: Seeding lesson content...');
        $this->call(LessonSeeder::class);
        $this->command->newLine();

        // Step 2: Seed predefined achievements
        $this->command->info('🏆 Step 2: Seeding achievements...');
        $this->call(AchievementSeeder::class);
        $this->command->newLine();

        // Step 3: Create demo user with sample progress
        $this->command->info('👤 Step 3: Creating demo user with sample data...');
        $this->call(DemoUserSeeder::class);
        $this->command->newLine();

        // Verify data integrity
        $this->verifyDataIntegrity();

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('You can now login with:');
        $this->command->info('  📧 Email: demo@example.com');
        $this->command->info('  🔑 Password: password');
    }

    /**
     * Verify all relationships and data integrity
     */
    private function verifyDataIntegrity(): void
    {
        $this->command->info('🔍 Verifying data integrity...');

        $checks = [
            'Lessons' => \App\Models\Lesson::count(),
            'Phrases' => \App\Models\Phrase::count(),
            'Dialogues' => \App\Models\Dialogue::count(),
            'Drills' => \App\Models\Drill::count(),
            'Shadowing Exercises' => \App\Models\ShadowingExercise::count(),
            'Achievements' => \App\Models\Achievement::count(),
            'Users' => \App\Models\User::count(),
            'User Profiles' => \App\Models\UserProfile::count(),
            'Flashcards' => \App\Models\Flashcard::count(),
            'Flashcard Reviews' => \App\Models\FlashcardReview::count(),
            'Exercise Attempts' => \App\Models\ExerciseAttempt::count(),
            'Shadowing Completions' => \App\Models\ShadowingCompletion::count(),
            'User Progress Records' => \App\Models\UserProgress::count(),
            'Study Activities' => \App\Models\StudyActivity::count(),
            'Daily Streaks' => \App\Models\DailyStreak::count(),
            'User Achievements' => \App\Models\UserAchievement::count(),
        ];

        foreach ($checks as $model => $count) {
            $status = $count > 0 ? '✓' : '✗';
            $this->command->info("  {$status} {$model}: {$count}");
        }

        // Verify relationships
        $this->command->newLine();
        $this->command->info('  Verifying relationships...');
        
        $demoUser = \App\Models\User::where('email', 'demo@example.com')->first();
        if ($demoUser) {
            $this->command->info("    ✓ Demo user has profile: " . ($demoUser->profile ? 'Yes' : 'No'));
            $this->command->info("    ✓ Demo user flashcards: " . $demoUser->flashcards()->count());
            $this->command->info("    ✓ Demo user progress records: " . $demoUser->progress()->count());
            $this->command->info("    ✓ Demo user achievements: " . $demoUser->achievements()->count());
        }

        $firstLesson = \App\Models\Lesson::first();
        if ($firstLesson) {
            $this->command->info("    ✓ First lesson has phrases: " . $firstLesson->phrases()->count());
            $this->command->info("    ✓ First lesson has dialogues: " . $firstLesson->dialogues()->count());
            $this->command->info("    ✓ First lesson has drills: " . $firstLesson->drills()->count());
            $this->command->info("    ✓ First lesson has shadowing: " . $firstLesson->shadowingExercises()->count());
        }
    }
}
