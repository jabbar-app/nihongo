<?php

use App\Models\Achievement;
use App\Models\User;
use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\Lesson;
use App\Models\Drill;
use App\Models\ExerciseAttempt;
use App\Models\ShadowingExercise;
use App\Models\ShadowingCompletion;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed achievements
    $this->artisan('db:seed', ['--class' => 'AchievementSeeder']);
});

test('achievements are seeded correctly', function () {
    $achievements = Achievement::all();
    
    expect($achievements->count())->toBeGreaterThan(20);
    
    // Check specific achievements exist
    expect(Achievement::where('slug', 'first-review')->exists())->toBeTrue();
    expect(Achievement::where('slug', 'week-warrior')->exists())->toBeTrue();
    expect(Achievement::where('slug', 'month-master')->exists())->toBeTrue();
    expect(Achievement::where('slug', 'lesson-master')->exists())->toBeTrue();
});

test('user earns first review achievement after first flashcard review', function () {
    $user = User::factory()->create();
    $flashcard = Flashcard::factory()->create(['user_id' => $user->id]);
    
    // Create a flashcard review
    FlashcardReview::create([
        'flashcard_id' => $flashcard->id,
        'rating' => 3,
        'duration_seconds' => 10,
        'previous_interval' => 0,
        'new_interval' => 1,
        'reviewed_at' => now(),
    ]);
    
    // Check if achievement was earned
    $achievement = Achievement::where('slug', 'first-review')->first();
    $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();
    
    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->pivot->earned_at)->not->toBeNull();
});

test('user earns card collector achievement after 100 reviews', function () {
    $user = User::factory()->create();
    $flashcard = Flashcard::factory()->create(['user_id' => $user->id]);
    
    // Create 100 flashcard reviews without triggering observers
    for ($i = 0; $i < 100; $i++) {
        FlashcardReview::withoutEvents(function () use ($flashcard, $i) {
            return FlashcardReview::create([
                'flashcard_id' => $flashcard->id,
                'rating' => 3,
                'duration_seconds' => 10,
                'previous_interval' => 0,
                'new_interval' => 1,
                'reviewed_at' => now()->subMinutes(100 - $i),
            ]);
        });
    }
    
    // Manually trigger achievement check
    $gamificationService = app(GamificationService::class);
    $gamificationService->checkAchievements($user);
    
    // Check if achievement was earned
    $achievement = Achievement::where('slug', 'card-collector')->first();
    $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();
    
    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->pivot->earned_at)->not->toBeNull();
});

test('user earns exercise achievement after completing exercises', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create();
    $drill = Drill::factory()->create(['lesson_id' => $lesson->id]);
    
    // Create 10 exercise attempts without triggering observers
    for ($i = 0; $i < 10; $i++) {
        ExerciseAttempt::withoutEvents(function () use ($user, $drill, $i) {
            return ExerciseAttempt::create([
                'user_id' => $user->id,
                'drill_id' => $drill->id,
                'answers' => ['answer1' => 'test'],
                'score' => 80,
                'duration_seconds' => 60,
                'completed_at' => now()->subMinutes(10 - $i),
            ]);
        });
    }
    
    // Manually trigger achievement check
    $gamificationService = app(GamificationService::class);
    $gamificationService->checkAchievements($user);
    
    // Check if achievement was earned
    $achievement = Achievement::where('slug', 'exercise-beginner')->first();
    $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();
    
    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->pivot->earned_at)->not->toBeNull();
});

test('user earns perfectionist achievement after 10 perfect scores', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create();
    $drill = Drill::factory()->create(['lesson_id' => $lesson->id]);
    
    // Create 10 perfect exercise attempts without triggering observers
    for ($i = 0; $i < 10; $i++) {
        ExerciseAttempt::withoutEvents(function () use ($user, $drill, $i) {
            return ExerciseAttempt::create([
                'user_id' => $user->id,
                'drill_id' => $drill->id,
                'answers' => ['answer1' => 'test'],
                'score' => 100,
                'duration_seconds' => 60,
                'completed_at' => now()->subMinutes(10 - $i),
            ]);
        });
    }
    
    // Manually trigger achievement check
    $gamificationService = app(GamificationService::class);
    $gamificationService->checkAchievements($user);
    
    // Check if achievement was earned
    $achievement = Achievement::where('slug', 'perfectionist')->first();
    $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();
    
    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->pivot->earned_at)->not->toBeNull();
});

test('user earns shadowing achievement after first completion', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create();
    $exercise = ShadowingExercise::factory()->create(['lesson_id' => $lesson->id]);
    
    // Create a shadowing completion
    ShadowingCompletion::create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $exercise->id,
        'duration_seconds' => 120,
        'completed_at' => now(),
    ]);
    
    // Check if achievement was earned
    $achievement = Achievement::where('slug', 'first-shadow')->first();
    $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();
    
    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->pivot->earned_at)->not->toBeNull();
});

test('achievement awards bonus XP when earned', function () {
    $user = User::factory()->create();
    $flashcard = Flashcard::factory()->create(['user_id' => $user->id]);
    
    $initialXp = $user->fresh()->profile->total_xp;
    
    // Create a flashcard review (should trigger first-review achievement)
    FlashcardReview::create([
        'flashcard_id' => $flashcard->id,
        'rating' => 3,
        'duration_seconds' => 10,
        'previous_interval' => 0,
        'new_interval' => 1,
        'reviewed_at' => now(),
    ]);
    
    $finalXp = $user->fresh()->profile->total_xp;
    $achievement = Achievement::where('slug', 'first-review')->first();
    
    // XP should include review XP + achievement bonus XP
    expect($finalXp)->toBeGreaterThan($initialXp);
    expect($finalXp - $initialXp)->toBeGreaterThanOrEqual($achievement->xp_reward);
});

test('achievement progress is tracked even when not earned', function () {
    $user = User::factory()->create();
    $flashcard = Flashcard::factory()->create(['user_id' => $user->id]);
    
    // Create 5 flashcard reviews (card-collector needs 100)
    for ($i = 0; $i < 5; $i++) {
        FlashcardReview::create([
            'flashcard_id' => $flashcard->id,
            'rating' => 3,
            'duration_seconds' => 10,
            'previous_interval' => 0,
            'new_interval' => 1,
            'reviewed_at' => now()->subMinutes(5 - $i),
        ]);
    }
    
    // Manually trigger achievement check
    $gamificationService = app(GamificationService::class);
    $gamificationService->checkAchievements($user);
    
    // Check progress is tracked
    $achievement = Achievement::where('slug', 'card-collector')->first();
    $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();
    
    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->pivot->progress)->toBe(5);
    expect($userAchievement->pivot->earned_at)->toBeNull();
});

test('level achievements are earned when reaching level threshold', function () {
    $user = User::factory()->create();
    $gamificationService = app(GamificationService::class);
    
    // Award enough XP to reach level 5
    $xpForLevel5 = $gamificationService->getXpForNextLevel(4);
    $gamificationService->awardXP($user, $xpForLevel5, 'Test');
    
    // Check achievements
    $gamificationService->checkAchievements($user);
    
    // Check if level 5 achievement was earned
    $achievement = Achievement::where('slug', 'level-5')->first();
    $userAchievement = $user->fresh()->achievements()->where('achievement_id', $achievement->id)->first();
    
    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->pivot->earned_at)->not->toBeNull();
});

test('streak achievements are earned when maintaining streak', function () {
    $user = User::factory()->create();
    
    // Set user's current streak to 7 days
    $user->profile->update(['current_streak' => 7]);
    
    // Check achievements
    $gamificationService = app(GamificationService::class);
    $gamificationService->checkAchievements($user);
    
    // Check if week warrior achievement was earned
    $achievement = Achievement::where('slug', 'week-warrior')->first();
    $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();
    
    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->pivot->earned_at)->not->toBeNull();
});
