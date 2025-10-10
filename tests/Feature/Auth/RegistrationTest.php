<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'study_goal_minutes' => 120,
        'cards_per_day_goal' => 20,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users have profile created with study preferences', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'study_goal_minutes' => 90,
        'cards_per_day_goal' => 15,
    ]);

    $this->assertAuthenticated();
    
    $user = \App\Models\User::where('email', 'test@example.com')->first();
    expect($user->profile)->not->toBeNull();
    expect($user->profile->study_goal_minutes)->toBe(90);
    expect($user->profile->cards_per_day_goal)->toBe(15);
    expect($user->profile->level)->toBe(1);
    expect($user->profile->total_xp)->toBe(0);
});
