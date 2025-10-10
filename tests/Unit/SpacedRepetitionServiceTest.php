<?php

use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\User;
use App\Services\SpacedRepetitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->service = new SpacedRepetitionService();
    $this->user = User::factory()->create();
});

test('calculateNextReview increases interval for good rating', function () {
    $card = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'ease_factor' => 2.5,
        'interval' => 0,
        'repetitions' => 0,
    ]);

    $result = $this->service->calculateNextReview($card, 3); // Good rating

    expect($result['interval'])->toBe(1)
        ->and($result['repetitions'])->toBe(1)
        ->and($result['ease_factor'])->toBeGreaterThanOrEqual(2.5)
        ->and($result['next_review_at'])->toBeInstanceOf(\Carbon\Carbon::class);
});

test('calculateNextReview resets interval for again rating', function () {
    $card = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'ease_factor' => 2.5,
        'interval' => 10,
        'repetitions' => 3,
    ]);

    $result = $this->service->calculateNextReview($card, 1); // Again rating

    expect($result['interval'])->toBe(1)
        ->and($result['repetitions'])->toBe(0);
});

test('calculateNextReview follows SM-2 algorithm for multiple reviews', function () {
    $card = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'ease_factor' => 2.5,
        'interval' => 0,
        'repetitions' => 0,
    ]);

    // First review - Good
    $result1 = $this->service->calculateNextReview($card, 3);
    expect($result1['interval'])->toBe(1)
        ->and($result1['repetitions'])->toBe(1);

    // Update card for second review
    $card->update($result1);

    // Second review - Good
    $result2 = $this->service->calculateNextReview($card, 3);
    expect($result2['interval'])->toBe(6)
        ->and($result2['repetitions'])->toBe(2);

    // Update card for third review
    $card->update($result2);

    // Third review - Good
    $result3 = $this->service->calculateNextReview($card, 3);
    expect($result3['interval'])->toBeGreaterThan(6)
        ->and($result3['repetitions'])->toBe(3);
});

test('calculateNextReview maintains minimum ease factor', function () {
    $card = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'ease_factor' => 1.3,
        'interval' => 5,
        'repetitions' => 2,
    ]);

    $result = $this->service->calculateNextReview($card, 2); // Hard rating

    expect($result['ease_factor'])->toBeGreaterThanOrEqual(1.3);
});

test('calculateNextReview throws exception for invalid rating', function () {
    $card = Flashcard::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->service->calculateNextReview($card, 5);
})->throws(\InvalidArgumentException::class);

test('getDueCards returns cards due for review', function () {
    // Create cards with different review dates
    Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'next_review_at' => now()->subDay(), // Due yesterday
    ]);
    
    Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'next_review_at' => now(), // Due now
    ]);
    
    Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'next_review_at' => now()->addDay(), // Due tomorrow
    ]);

    $dueCards = $this->service->getDueCards($this->user);

    expect($dueCards)->toHaveCount(2);
});

test('getDueCards respects limit parameter', function () {
    // Create 5 due cards
    Flashcard::factory()->count(5)->create([
        'user_id' => $this->user->id,
        'next_review_at' => now()->subDay(),
    ]);

    $dueCards = $this->service->getDueCards($this->user, 3);

    expect($dueCards)->toHaveCount(3);
});

test('getDueCards orders by next_review_at ascending', function () {
    $card1 = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'next_review_at' => now()->subDays(3),
    ]);
    
    $card2 = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'next_review_at' => now()->subDays(1),
    ]);

    $dueCards = $this->service->getDueCards($this->user);

    expect($dueCards->first()->id)->toBe($card1->id)
        ->and($dueCards->last()->id)->toBe($card2->id);
});

test('getNewCards returns unreviewed cards', function () {
    // Create new cards (repetitions = 0)
    Flashcard::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'repetitions' => 0,
    ]);
    
    // Create reviewed card
    Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'repetitions' => 2,
    ]);

    $newCards = $this->service->getNewCards($this->user);

    expect($newCards)->toHaveCount(3);
});

test('getNewCards respects limit parameter', function () {
    Flashcard::factory()->count(15)->create([
        'user_id' => $this->user->id,
        'repetitions' => 0,
    ]);

    $newCards = $this->service->getNewCards($this->user, 5);

    expect($newCards)->toHaveCount(5);
});

test('getNewCards orders by created_at ascending', function () {
    $card1 = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'repetitions' => 0,
        'created_at' => now()->subDays(2),
    ]);
    
    $card2 = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'repetitions' => 0,
        'created_at' => now()->subDay(),
    ]);

    $newCards = $this->service->getNewCards($this->user);

    expect($newCards->first()->id)->toBe($card1->id)
        ->and($newCards->last()->id)->toBe($card2->id);
});

test('recordReview creates review record and updates card', function () {
    $card = Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'ease_factor' => 2.5,
        'interval' => 0,
        'repetitions' => 0,
    ]);

    $review = $this->service->recordReview($card, 3, 10);

    expect($review)->toBeInstanceOf(FlashcardReview::class)
        ->and($review->flashcard_id)->toBe($card->id)
        ->and($review->rating)->toBe(3)
        ->and($review->duration_seconds)->toBe(10)
        ->and($review->previous_interval)->toBe(0)
        ->and($review->new_interval)->toBe(1);

    // Verify card was updated
    $card->refresh();
    expect($card->interval)->toBe(1)
        ->and($card->repetitions)->toBe(1)
        ->and($card->next_review_at)->not->toBeNull();
});

test('recordReview uses database transaction', function () {
    $card = Flashcard::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $initialReviewCount = FlashcardReview::count();
    $initialInterval = $card->interval;

    $this->service->recordReview($card, 3, 10);

    // Verify both review was created and card was updated
    expect(FlashcardReview::count())->toBe($initialReviewCount + 1)
        ->and($card->fresh()->interval)->not->toBe($initialInterval);
});

test('getDueCards only returns cards for specific user', function () {
    $otherUser = User::factory()->create();

    // Create cards for both users
    Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'next_review_at' => now()->subDay(),
    ]);
    
    Flashcard::factory()->create([
        'user_id' => $otherUser->id,
        'next_review_at' => now()->subDay(),
    ]);

    $dueCards = $this->service->getDueCards($this->user);

    expect($dueCards)->toHaveCount(1)
        ->and($dueCards->first()->user_id)->toBe($this->user->id);
});

test('getNewCards only returns cards for specific user', function () {
    $otherUser = User::factory()->create();

    Flashcard::factory()->create([
        'user_id' => $this->user->id,
        'repetitions' => 0,
    ]);
    
    Flashcard::factory()->create([
        'user_id' => $otherUser->id,
        'repetitions' => 0,
    ]);

    $newCards = $this->service->getNewCards($this->user);

    expect($newCards)->toHaveCount(1)
        ->and($newCards->first()->user_id)->toBe($this->user->id);
});
