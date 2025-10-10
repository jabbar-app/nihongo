<?php

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Flashcard;
use App\Models\Lesson;
use App\Models\UserProgress;
use App\Models\DailyStreak;
use Carbon\Carbon;

test('dashboard displays for authenticated user', function () {
    $user = User::factory()->create();
    UserProfile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Welcome back');
    $response->assertSee($user->name);
});

test('dashboard shows flashcard statistics', function () {
    $user = User::factory()->create();
    UserProfile::factory()->create(['user_id' => $user->id]);

    // Create some flashcards
    Flashcard::factory()->count(5)->create([
        'user_id' => $user->id,
        'next_review_at' => now()->subHour(), // Due cards
    ]);

    Flashcard::factory()->count(3)->create([
        'user_id' => $user->id,
        'next_review_at' => now()->addDays(2), // Upcoming cards
        'repetitions' => 1,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Cards Due Today');
    $response->assertSee('5'); // Should show 5 due cards
});

test('dashboard shows level and XP information', function () {
    $user = User::factory()->create();
    UserProfile::factory()->create([
        'user_id' => $user->id,
        'level' => 5,
        'total_xp' => 1500,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Level 5');
    $response->assertSee('XP');
});

test('dashboard shows current streak', function () {
    $user = User::factory()->create();
    UserProfile::factory()->create([
        'user_id' => $user->id,
        'current_streak' => 7,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Current Streak');
    $response->assertSee('7');
});

test('dashboard shows study time today', function () {
    $user = User::factory()->create();
    UserProfile::factory()->create(['user_id' => $user->id]);

    DailyStreak::factory()->create([
        'user_id' => $user->id,
        'date' => Carbon::today(),
        'study_minutes' => 45,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Study Time Today');
    $response->assertSee('45');
});

test('dashboard shows recent lessons', function () {
    $user = User::factory()->create();
    UserProfile::factory()->create(['user_id' => $user->id]);

    $lesson = Lesson::factory()->create([
        'title' => 'Test Lesson',
        'slug' => 'test-lesson',
    ]);

    UserProgress::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
        'last_accessed_at' => now(),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Recent Lessons');
    $response->assertSee('Test Lesson');
});

test('dashboard shows quick action buttons', function () {
    $user = User::factory()->create();
    UserProfile::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Quick Actions');
    $response->assertSee('Start Review');
    $response->assertSee('View Progress');
});

test('dashboard redirects unauthenticated users', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});
