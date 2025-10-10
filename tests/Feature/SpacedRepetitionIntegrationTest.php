<?php

use App\Models\Flashcard;
use App\Models\User;
use App\Services\SpacedRepetitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('complete spaced repetition workflow', function () {
    $user = User::factory()->create();
    $service = new SpacedRepetitionService();

    // Create some flashcards
    $newCard1 = Flashcard::factory()->create([
        'user_id' => $user->id,
        'front' => 'こんにちは',
        'back' => 'Hello',
        'romaji' => 'konnichiwa',
        'repetitions' => 0,
        'next_review_at' => now()->addDay(), // Not due yet
    ]);

    $newCard2 = Flashcard::factory()->create([
        'user_id' => $user->id,
        'front' => 'ありがとう',
        'back' => 'Thank you',
        'romaji' => 'arigatou',
        'repetitions' => 0,
        'next_review_at' => now()->addDay(), // Not due yet
    ]);

    $dueCard = Flashcard::factory()->create([
        'user_id' => $user->id,
        'front' => 'さようなら',
        'back' => 'Goodbye',
        'romaji' => 'sayounara',
        'repetitions' => 2,
        'interval' => 6,
        'next_review_at' => now()->subDay(),
    ]);

    // Get new cards
    $newCards = $service->getNewCards($user, 10);
    expect($newCards)->toHaveCount(2);

    // Get due cards
    $dueCards = $service->getDueCards($user, 20);
    expect($dueCards)->toHaveCount(1)
        ->and($dueCards->first()->id)->toBe($dueCard->id);

    // Review the due card with "Good" rating
    $review = $service->recordReview($dueCard, 3, 15);
    
    expect($review->rating)->toBe(3)
        ->and($review->duration_seconds)->toBe(15)
        ->and($review->previous_interval)->toBe(6);

    // Verify card was updated
    $dueCard->refresh();
    expect($dueCard->repetitions)->toBe(3)
        ->and($dueCard->interval)->toBeGreaterThan(6)
        ->and($dueCard->next_review_at)->toBeGreaterThan(now());

    // Review a new card with "Easy" rating
    $review2 = $service->recordReview($newCard1, 4, 8);
    
    $newCard1->refresh();
    expect($newCard1->repetitions)->toBe(1)
        ->and($newCard1->interval)->toBe(1);

    // Review a card with "Again" rating (failure)
    $review3 = $service->recordReview($newCard2, 1, 20);
    
    $newCard2->refresh();
    expect($newCard2->repetitions)->toBe(0) // Reset to 0
        ->and($newCard2->interval)->toBe(1); // Short interval

    // Verify no more due cards after reviewing
    $remainingDue = $service->getDueCards($user, 20);
    expect($remainingDue)->toHaveCount(0);
});

test('spaced repetition algorithm increases intervals over time', function () {
    $user = User::factory()->create();
    $service = new SpacedRepetitionService();

    $card = Flashcard::factory()->create([
        'user_id' => $user->id,
        'repetitions' => 0,
        'interval' => 0,
        'ease_factor' => 2.5,
    ]);

    // First review - Good
    $service->recordReview($card, 3, 10);
    $card->refresh();
    expect($card->interval)->toBe(1);

    // Second review - Good
    $service->recordReview($card, 3, 10);
    $card->refresh();
    expect($card->interval)->toBe(6);

    // Third review - Good
    $service->recordReview($card, 3, 10);
    $card->refresh();
    $thirdInterval = $card->interval;
    expect($thirdInterval)->toBeGreaterThan(6);

    // Fourth review - Good
    $service->recordReview($card, 3, 10);
    $card->refresh();
    expect($card->interval)->toBeGreaterThan($thirdInterval);
});

test('failed reviews reset progress', function () {
    $user = User::factory()->create();
    $service = new SpacedRepetitionService();

    $card = Flashcard::factory()->create([
        'user_id' => $user->id,
        'repetitions' => 5,
        'interval' => 30,
        'ease_factor' => 2.8,
    ]);

    // Review with "Again" rating
    $service->recordReview($card, 1, 10);
    
    $card->refresh();
    expect($card->repetitions)->toBe(0)
        ->and($card->interval)->toBe(1);
});
