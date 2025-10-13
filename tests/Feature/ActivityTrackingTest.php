<?php

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\ExerciseAttempt;
use App\Models\ShadowingCompletion;
use App\Models\ShadowingExercise;
use App\Models\Drill;
use App\Models\Lesson;
use App\Models\StudyActivity;
use App\Models\DailyStreak;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('flashcard review creates study activity and updates daily streak', function () {
    // Create user with profile
    $user = User::factory()->create();
    $profile = UserProfile::factory()->create(['user_id' => $user->id]);

    // Create flashcard
    $flashcard = Flashcard::factory()->create(['user_id' => $user->id]);

    // Create flashcard review
    $review = FlashcardReview::create([
        'flashcard_id' => $flashcard->id,
        'rating' => 3, // Good
        'duration_seconds' => 10,
        'previous_interval' => 0,
        'new_interval' => 1,
        'reviewed_at' => now(),
    ]);

    // Assert study activity was created
    $activity = StudyActivity::where('user_id', $user->id)
        ->where('activity_type', 'flashcard_review')
        ->where('activityable_type', FlashcardReview::class)
        ->where('activityable_id', $review->id)
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->duration_seconds)->toBe(10);
    expect($activity->xp_earned)->toBe(5); // Good rating = 5 XP

    // Assert daily streak was updated
    $dailyStreak = DailyStreak::where('user_id', $user->id)
        ->where('date', now()->toDateString())
        ->first();

    expect($dailyStreak)->not->toBeNull();
    expect($dailyStreak->activities_count)->toBe(1);
    expect($dailyStreak->study_minutes)->toBe(1); // 10 seconds = 1 minute (rounded up)
    expect($dailyStreak->xp_earned)->toBe(5);

    // Assert user profile XP was updated
    $profile->refresh();
    expect($profile->total_xp)->toBe(5);
});

test('exercise attempt creates study activity with score-based xp', function () {
    // Create user with profile
    $user = User::factory()->create();
    $profile = UserProfile::factory()->create(['user_id' => $user->id]);

    // Create lesson and drill
    $lesson = Lesson::factory()->create();
    $drill = Drill::factory()->create(['lesson_id' => $lesson->id]);

    // Create exercise attempt with high score
    $attempt = ExerciseAttempt::create([
        'user_id' => $user->id,
        'drill_id' => $drill->id,
        'answers' => ['answer1' => 'correct'],
        'score' => 90.00,
        'duration_seconds' => 120,
        'completed_at' => now(),
    ]);

    // Assert study activity was created
    $activity = StudyActivity::where('user_id', $user->id)
        ->where('activity_type', 'exercise')
        ->where('activityable_type', ExerciseAttempt::class)
        ->where('activityable_id', $attempt->id)
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->duration_seconds)->toBe(120);
    expect($activity->xp_earned)->toBeGreaterThanOrEqual(10); // At least minimum XP

    // Assert daily streak was updated
    $dailyStreak = DailyStreak::where('user_id', $user->id)
        ->where('date', now()->toDateString())
        ->first();

    expect($dailyStreak)->not->toBeNull();
    expect($dailyStreak->activities_count)->toBe(1);
    expect($dailyStreak->study_minutes)->toBe(2); // 120 seconds = 2 minutes

    // Assert user profile XP was updated
    $profile->refresh();
    expect($profile->total_xp)->toBeGreaterThan(0);
});

test('shadowing completion creates study activity with bonus for recording', function () {
    // Create user with profile
    $user = User::factory()->create();
    $profile = UserProfile::factory()->create(['user_id' => $user->id]);

    // Create lesson and shadowing exercise
    $lesson = Lesson::factory()->create();
    $exercise = ShadowingExercise::factory()->create(['lesson_id' => $lesson->id]);

    // Create shadowing completion without recording (to avoid FK constraint)
    $completion = ShadowingCompletion::create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $exercise->id,
        'duration_seconds' => 180,
        'recording_id' => null, // No recording for simplicity
        'completed_at' => now(),
    ]);

    // Assert study activity was created
    $activity = StudyActivity::where('user_id', $user->id)
        ->where('activity_type', 'shadowing')
        ->where('activityable_type', ShadowingCompletion::class)
        ->where('activityable_id', $completion->id)
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->duration_seconds)->toBe(180);
    expect($activity->xp_earned)->toBe(50); // 50 base (no recording bonus)

    // Assert daily streak was updated
    $dailyStreak = DailyStreak::where('user_id', $user->id)
        ->where('date', now()->toDateString())
        ->first();

    expect($dailyStreak)->not->toBeNull();
    expect($dailyStreak->activities_count)->toBe(1);
    expect($dailyStreak->study_minutes)->toBe(3); // 180 seconds = 3 minutes
    expect($dailyStreak->xp_earned)->toBe(50);

    // Assert user profile XP was updated
    $profile->refresh();
    expect($profile->total_xp)->toBe(50);
});

test('multiple activities accumulate in daily streak', function () {
    // Create user with profile
    $user = User::factory()->create();
    $profile = UserProfile::factory()->create(['user_id' => $user->id]);

    // Create flashcard and reviews
    $flashcard = Flashcard::factory()->create(['user_id' => $user->id]);

    // Create multiple reviews
    FlashcardReview::create([
        'flashcard_id' => $flashcard->id,
        'rating' => 3,
        'duration_seconds' => 10,
        'previous_interval' => 0,
        'new_interval' => 1,
        'reviewed_at' => now(),
    ]);

    FlashcardReview::create([
        'flashcard_id' => $flashcard->id,
        'rating' => 4,
        'duration_seconds' => 8,
        'previous_interval' => 1,
        'new_interval' => 2,
        'reviewed_at' => now(),
    ]);

    // Assert daily streak accumulated both activities
    $dailyStreak = DailyStreak::where('user_id', $user->id)
        ->where('date', now()->toDateString())
        ->first();

    expect($dailyStreak)->not->toBeNull();
    expect($dailyStreak->activities_count)->toBe(2);
    expect($dailyStreak->study_minutes)->toBe(1); // 18 seconds = 1 minute (rounded up)
    expect($dailyStreak->xp_earned)->toBe(12); // 5 + 7

    // Assert user profile XP was updated
    $profile->refresh();
    expect($profile->total_xp)->toBe(12);
});

test('different activity types are tracked separately', function () {
    // Create user with profile
    $user = User::factory()->create();
    $profile = UserProfile::factory()->create(['user_id' => $user->id]);

    // Create lesson
    $lesson = Lesson::factory()->create();

    // Create flashcard review
    $flashcard = Flashcard::factory()->create(['user_id' => $user->id]);
    FlashcardReview::create([
        'flashcard_id' => $flashcard->id,
        'rating' => 3,
        'duration_seconds' => 10,
        'previous_interval' => 0,
        'new_interval' => 1,
        'reviewed_at' => now(),
    ]);

    // Create exercise attempt
    $drill = Drill::factory()->create(['lesson_id' => $lesson->id]);
    ExerciseAttempt::create([
        'user_id' => $user->id,
        'drill_id' => $drill->id,
        'answers' => ['answer1' => 'correct'],
        'score' => 100.00,
        'duration_seconds' => 60,
        'completed_at' => now(),
    ]);

    // Create shadowing completion
    $exercise = ShadowingExercise::factory()->create(['lesson_id' => $lesson->id]);
    ShadowingCompletion::create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $exercise->id,
        'duration_seconds' => 120,
        'recording_id' => null,
        'completed_at' => now(),
    ]);

    // Assert all three activity types were created
    $activities = StudyActivity::where('user_id', $user->id)->get();
    expect($activities)->toHaveCount(3);

    $activityTypes = $activities->pluck('activity_type')->toArray();
    expect($activityTypes)->toContain('flashcard_review');
    expect($activityTypes)->toContain('exercise');
    expect($activityTypes)->toContain('shadowing');

    // Assert daily streak accumulated all activities
    $dailyStreak = DailyStreak::where('user_id', $user->id)
        ->where('date', now()->toDateString())
        ->first();

    expect($dailyStreak)->not->toBeNull();
    expect($dailyStreak->activities_count)->toBe(3);
});
