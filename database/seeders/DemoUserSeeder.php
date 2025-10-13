<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Lesson;
use App\Models\Phrase;
use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\ExerciseAttempt;
use App\Models\ShadowingCompletion;
use App\Models\UserProgress;
use App\Models\StudyActivity;
use App\Models\DailyStreak;
use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoUserSeeder extends Seeder
{
    private User $demoUser;
    private array $lessons;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating demo user with sample progress...');

        // Create demo user
        $this->createDemoUser();

        // Load lessons
        $this->lessons = Lesson::orderBy('order')->get()->all();

        if (empty($this->lessons)) {
            $this->command->warn('No lessons found. Please run LessonSeeder first.');
            return;
        }

        // Disable observers to prevent automatic DailyStreak creation
        FlashcardReview::unsetEventDispatcher();
        ExerciseAttempt::unsetEventDispatcher();
        ShadowingCompletion::unsetEventDispatcher();

        // Create sample data
        $this->createFlashcardsAndReviews();
        $this->createExerciseAttempts();
        $this->createShadowingCompletions();
        $this->createProgressRecords();
        $this->createStudyActivities();
        $this->createDailyStreaks();
        $this->awardAchievements();

        $this->command->info('✓ Demo user created successfully!');
        $this->command->info("  Email: demo@example.com");
        $this->command->info("  Password: password");
        $this->command->info("  Level: {$this->demoUser->profile->level}");
        $this->command->info("  Total XP: {$this->demoUser->profile->total_xp}");
        $this->command->info("  Current Streak: {$this->demoUser->profile->current_streak} days");
    }

    /**
     * Create demo user with profile
     */
    private function createDemoUser(): void
    {
        // Find existing demo user and delete all related data
        $existingUser = User::where('email', 'demo@example.com')->first();
        if ($existingUser) {
            $this->command->info('  Cleaning up existing demo user data...');
            
            // Delete all related data
            $existingUser->flashcards()->delete();
            $existingUser->exerciseAttempts()->delete();
            $existingUser->shadowingCompletions()->delete();
            $existingUser->progress()->delete();
            $existingUser->studyActivities()->delete();
            $existingUser->dailyStreaks()->delete();
            $existingUser->achievements()->detach();
            $existingUser->profile()->delete();
            $existingUser->delete();
        }

        // Create fresh demo user
        $this->demoUser = User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create user profile
        UserProfile::create([
            'user_id' => $this->demoUser->id,
            'level' => 8,
            'total_xp' => 2450,
            'current_streak' => 12,
            'longest_streak' => 15,
            'study_goal_minutes' => 120,
            'cards_per_day_goal' => 25,
        ]);

        $this->demoUser->load('profile');
    }

    /**
     * Create sample flashcards and reviews
     */
    private function createFlashcardsAndReviews(): void
    {
        $this->command->info('  Creating flashcards and reviews...');

        // Get phrases from first 3 lessons
        $phrases = Phrase::whereIn('lesson_id', array_slice(array_column($this->lessons, 'id'), 0, 3))
            ->limit(50)
            ->get();

        $flashcardCount = 0;
        $reviewCount = 0;

        foreach ($phrases as $index => $phrase) {
            // Create flashcard
            $flashcard = Flashcard::create([
                'user_id' => $this->demoUser->id,
                'phrase_id' => $phrase->id,
                'front' => $phrase->japanese,
                'back' => $phrase->english,
                'romaji' => $phrase->romaji,
                'ease_factor' => 2.5 + (rand(-5, 10) / 10), // Vary between 2.0 and 3.5
                'interval' => rand(0, 30),
                'repetitions' => rand(0, 10),
                'next_review_at' => now()->addDays(rand(-5, 10)),
            ]);

            $flashcardCount++;

            // Create 2-5 review records for each card
            $numReviews = rand(2, 5);
            for ($i = 0; $i < $numReviews; $i++) {
                FlashcardReview::create([
                    'flashcard_id' => $flashcard->id,
                    'rating' => rand(2, 4), // Hard, Good, or Easy
                    'duration_seconds' => rand(5, 30),
                    'previous_interval' => max(0, $flashcard->interval - rand(1, 5)),
                    'new_interval' => $flashcard->interval,
                    'reviewed_at' => now()->subDays(rand(1, 30)),
                ]);
                $reviewCount++;
            }
        }

        $this->command->info("    ✓ Created {$flashcardCount} flashcards with {$reviewCount} reviews");
    }

    /**
     * Create sample exercise attempts
     */
    private function createExerciseAttempts(): void
    {
        $this->command->info('  Creating exercise attempts...');

        $attemptCount = 0;

        // Get drills from first 3 lessons
        foreach (array_slice($this->lessons, 0, 3) as $lesson) {
            $drills = $lesson->drills()->limit(5)->get();

            foreach ($drills as $drill) {
                // Create 1-3 attempts per drill
                $numAttempts = rand(1, 3);
                for ($i = 0; $i < $numAttempts; $i++) {
                    $score = rand(60, 100);
                    
                    ExerciseAttempt::create([
                        'user_id' => $this->demoUser->id,
                        'drill_id' => $drill->id,
                        'answers' => ['answer1' => 'sample', 'answer2' => 'data'],
                        'score' => $score,
                        'duration_seconds' => rand(60, 300),
                        'completed_at' => now()->subDays(rand(1, 20)),
                    ]);
                    $attemptCount++;
                }
            }
        }

        $this->command->info("    ✓ Created {$attemptCount} exercise attempts");
    }

    /**
     * Create sample shadowing completions
     */
    private function createShadowingCompletions(): void
    {
        $this->command->info('  Creating shadowing completions...');

        $completionCount = 0;

        // Get shadowing exercises from first 2 lessons
        foreach (array_slice($this->lessons, 0, 2) as $lesson) {
            $exercises = $lesson->shadowingExercises()->limit(3)->get();

            foreach ($exercises as $exercise) {
                ShadowingCompletion::create([
                    'user_id' => $this->demoUser->id,
                    'shadowing_exercise_id' => $exercise->id,
                    'duration_seconds' => rand(120, 600),
                    'recording_id' => null, // No actual recordings for demo
                    'completed_at' => now()->subDays(rand(1, 15)),
                ]);
                $completionCount++;
            }
        }

        $this->command->info("    ✓ Created {$completionCount} shadowing completions");
    }

    /**
     * Create progress records for lessons
     */
    private function createProgressRecords(): void
    {
        $this->command->info('  Creating progress records...');

        $progressCount = 0;

        // Create progress for first 4 lessons
        foreach (array_slice($this->lessons, 0, 4) as $index => $lesson) {
            $completion = match($index) {
                0 => 100, // First lesson fully completed
                1 => 85,  // Second lesson mostly completed
                2 => 60,  // Third lesson in progress
                3 => 25,  // Fourth lesson just started
                default => 0,
            };

            UserProgress::create([
                'user_id' => $this->demoUser->id,
                'lesson_id' => $lesson->id,
                'phrases_viewed' => (int)($lesson->phrases()->count() * ($completion / 100)),
                'dialogues_viewed' => (int)($lesson->dialogues()->count() * ($completion / 100)),
                'drills_completed' => (int)($lesson->drills()->count() * ($completion / 100)),
                'shadowing_completed' => (int)($lesson->shadowingExercises()->count() * ($completion / 100)),
                'completion_percentage' => $completion,
                'last_accessed_at' => now()->subDays(rand(0, 5)),
            ]);
            $progressCount++;
        }

        $this->command->info("    ✓ Created {$progressCount} progress records");
    }

    /**
     * Create study activities
     */
    private function createStudyActivities(): void
    {
        $this->command->info('  Creating study activities...');

        $activityCount = 0;

        // Create activities for the past 15 days
        for ($i = 0; $i < 15; $i++) {
            $date = now()->subDays($i);
            $numActivities = rand(3, 8);

            for ($j = 0; $j < $numActivities; $j++) {
                $activityType = ['flashcard_review', 'exercise', 'shadowing', 'content_view'][rand(0, 3)];
                
                StudyActivity::create([
                    'user_id' => $this->demoUser->id,
                    'activity_type' => $activityType,
                    'activityable_type' => 'App\\Models\\Flashcard', // Simplified for demo
                    'activityable_id' => 1,
                    'duration_seconds' => rand(60, 600),
                    'xp_earned' => rand(5, 25),
                    'created_at' => $date->copy()->addMinutes(rand(0, 1440)),
                ]);
                $activityCount++;
            }
        }

        $this->command->info("    ✓ Created {$activityCount} study activities");
    }

    /**
     * Create daily streaks
     */
    private function createDailyStreaks(): void
    {
        $this->command->info('  Creating daily streaks...');

        $streakCount = 0;

        // Create streak records for the past 12 days (matching current_streak)
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subDays($i)->toDateString();
            
            DailyStreak::updateOrCreate(
                [
                    'user_id' => $this->demoUser->id,
                    'date' => $date,
                ],
                [
                    'activities_count' => rand(5, 15),
                    'study_minutes' => rand(60, 180),
                    'xp_earned' => rand(50, 200),
                ]
            );
            $streakCount++;
        }

        $this->command->info("    ✓ Created {$streakCount} daily streak records");
    }

    /**
     * Award achievements to demo user
     */
    private function awardAchievements(): void
    {
        $this->command->info('  Awarding achievements...');

        $achievements = Achievement::whereIn('slug', [
            'first-review',
            'card-collector',
            'getting-started',
            'week-warrior',
            'first-lesson',
            'exercise-beginner',
            'first-shadow',
            'study-starter',
            'level-5',
        ])->get();

        $awardedCount = 0;

        foreach ($achievements as $achievement) {
            UserAchievement::create([
                'user_id' => $this->demoUser->id,
                'achievement_id' => $achievement->id,
                'progress' => $achievement->requirement_value,
                'earned_at' => now()->subDays(rand(1, 10)),
            ]);
            $awardedCount++;
        }

        $this->command->info("    ✓ Awarded {$awardedCount} achievements");
    }
}
