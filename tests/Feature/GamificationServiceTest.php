<?php

use App\Models\User;
use App\Models\UserProfile;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->gamificationService = app(GamificationService::class);
});

test('calculateLevel returns correct level for given XP', function () {
    expect($this->gamificationService->calculateLevel(0))->toBe(1);
    expect($this->gamificationService->calculateLevel(100))->toBe(2);
    expect($this->gamificationService->calculateLevel(250))->toBe(3);
    expect($this->gamificationService->calculateLevel(450))->toBe(4);
    expect($this->gamificationService->calculateLevel(1000))->toBe(6);
});

test('getXpForNextLevel returns correct XP threshold', function () {
    expect($this->gamificationService->getXpForNextLevel(1))->toBe(100);
    expect($this->gamificationService->getXpForNextLevel(2))->toBe(250);
    expect($this->gamificationService->getXpForNextLevel(5))->toBe(1000);
});

test('awardXP adds XP to user profile', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 1,
        'total_xp' => 0,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $this->gamificationService->awardXP($user, 50, 'Test activity');

    $user->refresh();
    expect($user->profile->total_xp)->toBe(50);
});

test('awardXP triggers level up when threshold reached', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 1,
        'total_xp' => 90,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    // Award 20 XP, bringing total to 110 (should level up to 2)
    $this->gamificationService->awardXP($user, 20, 'Test activity');

    $user->refresh();
    expect($user->profile->total_xp)->toBe(110);
    expect($user->profile->level)->toBe(2);
});

test('checkLevelUp returns new level when user has leveled up', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 1,
        'total_xp' => 250,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $newLevel = $this->gamificationService->checkLevelUp($user);

    expect($newLevel)->toBe(3);
    $user->refresh();
    expect($user->profile->level)->toBe(3);
});

test('checkLevelUp returns null when user has not leveled up', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 2,
        'total_xp' => 150,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $newLevel = $this->gamificationService->checkLevelUp($user);

    expect($newLevel)->toBeNull();
});

test('getXpProgress returns correct progress data', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 2,
        'total_xp' => 175,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $progress = $this->gamificationService->getXpProgress($user);

    expect($progress['current_level'])->toBe(2);
    expect($progress['current_xp'])->toBe(175);
    expect($progress['xp_for_current_level'])->toBe(100);
    expect($progress['xp_for_next_level'])->toBe(250);
    expect($progress['xp_progress'])->toBe(75);
    expect($progress['xp_remaining'])->toBe(75);
    expect($progress['progress_percentage'])->toBe(50.0);
});

test('awardXP creates profile if it does not exist', function () {
    $user = User::factory()->create();

    $this->gamificationService->awardXP($user, 50, 'Test activity');

    $user->refresh();
    expect($user->profile)->not->toBeNull();
    expect($user->profile->total_xp)->toBe(50);
    expect($user->profile->level)->toBe(1);
});

test('awardXP returns level up data when user levels up', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 1,
        'total_xp' => 90,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $levelUpData = $this->gamificationService->awardXP($user, 20, 'Test activity');

    expect($levelUpData)->not->toBeNull();
    expect($levelUpData['old_level'])->toBe(1);
    expect($levelUpData['new_level'])->toBe(2);
    expect($levelUpData['xp_earned'])->toBe(20);
    expect($levelUpData['bonus_xp'])->toBe(0);
    expect($levelUpData)->toHaveKey('unlocked_features');
});

test('awardXP returns null when user does not level up', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 1,
        'total_xp' => 50,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $levelUpData = $this->gamificationService->awardXP($user, 20, 'Test activity');

    expect($levelUpData)->toBeNull();
});

test('milestone level 10 awards 500 bonus XP', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 9,
        'total_xp' => 2650,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $levelUpData = $this->gamificationService->awardXP($user, 50, 'Test activity');

    expect($levelUpData)->not->toBeNull();
    expect($levelUpData['new_level'])->toBe(10);
    expect($levelUpData['bonus_xp'])->toBe(500);
    
    $user->refresh();
    expect($user->profile->total_xp)->toBe(3200); // 2650 + 50 + 500
});

test('milestone level 25 awards 1000 bonus XP', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 24,
        'total_xp' => 14950, // Level 25 threshold is 16200, so we need to be close
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $levelUpData = $this->gamificationService->awardXP($user, 1250, 'Test activity');

    expect($levelUpData)->not->toBeNull();
    expect($levelUpData['new_level'])->toBe(25);
    expect($levelUpData['bonus_xp'])->toBe(1000);
    
    $user->refresh();
    expect($user->profile->total_xp)->toBe(17200); // 14950 + 1250 + 1000
});

test('milestone level 50 awards 2500 bonus XP', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 49,
        'total_xp' => 61200, // Level 50 threshold is 63700
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $levelUpData = $this->gamificationService->awardXP($user, 2500, 'Test activity');

    expect($levelUpData)->not->toBeNull();
    expect($levelUpData['new_level'])->toBe(50);
    expect($levelUpData['bonus_xp'])->toBe(2500);
    
    $user->refresh();
    expect($user->profile->total_xp)->toBe(66200); // 61200 + 2500 + 2500
});

test('level up creates LevelUp record', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 1,
        'total_xp' => 90,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $this->gamificationService->awardXP($user, 20, 'Test activity');

    expect($user->levelUps()->count())->toBe(1);
    
    $levelUp = $user->levelUps()->first();
    expect($levelUp->old_level)->toBe(1);
    expect($levelUp->new_level)->toBe(2);
    expect($levelUp->xp_at_levelup)->toBe(110);
    expect($levelUp->bonus_xp)->toBe(0);
});

test('milestone level up creates LevelUp record with bonus XP', function () {
    $user = User::factory()->create();
    UserProfile::create([
        'user_id' => $user->id,
        'level' => 9,
        'total_xp' => 2650,
        'current_streak' => 0,
        'longest_streak' => 0,
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $this->gamificationService->awardXP($user, 50, 'Test activity');

    $levelUp = $user->levelUps()->first();
    expect($levelUp->old_level)->toBe(9);
    expect($levelUp->new_level)->toBe(10);
    expect($levelUp->xp_at_levelup)->toBe(2700);
    expect($levelUp->bonus_xp)->toBe(500);
});
