<?php

namespace App\Services;

use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpacedRepetitionService
{
    public function __construct(
        protected CacheService $cacheService
    ) {}

    /**
     * Calculate the next review parameters using the SM-2 algorithm.
     * 
     * @param Flashcard $card The flashcard being reviewed
     * @param int $rating Rating from 1-4 (1=Again, 2=Hard, 3=Good, 4=Easy)
     * @return array ['ease_factor' => float, 'interval' => int, 'next_review_at' => Carbon]
     */
    public function calculateNextReview(Flashcard $card, int $rating): array
    {
        // Validate rating
        if ($rating < 1 || $rating > 4) {
            throw new \InvalidArgumentException('Rating must be between 1 and 4');
        }

        $easeFactor = $card->ease_factor;
        $interval = $card->interval;
        $repetitions = $card->repetitions;

        // Convert rating (1-4) to quality (0-5) for SM-2 algorithm
        // 1 (Again) -> 0, 2 (Hard) -> 3, 3 (Good) -> 4, 4 (Easy) -> 5
        $quality = match($rating) {
            1 => 0,  // Again - complete failure
            2 => 3,  // Hard - correct with difficulty
            3 => 4,  // Good - correct with hesitation
            4 => 5,  // Easy - perfect recall
        };

        // SM-2 Algorithm
        // Update ease factor: EF' = EF + (0.1 - (5 - q) * (0.08 + (5 - q) * 0.02))
        $newEaseFactor = $easeFactor + (0.1 - (5 - $quality) * (0.08 + (5 - $quality) * 0.02));
        
        // Ease factor should not go below 1.3
        $newEaseFactor = max(1.3, $newEaseFactor);

        // Calculate new interval and repetitions
        if ($quality < 3) {
            // Failed recall - reset repetitions and set short interval
            $newRepetitions = 0;
            $newInterval = 1; // Review again tomorrow
        } else {
            // Successful recall
            $newRepetitions = $repetitions + 1;
            
            if ($newRepetitions === 1) {
                $newInterval = 1; // First successful review: 1 day
            } elseif ($newRepetitions === 2) {
                $newInterval = 6; // Second successful review: 6 days
            } else {
                // Subsequent reviews: multiply previous interval by ease factor
                $newInterval = (int) round($interval * $newEaseFactor);
            }
        }

        // Calculate next review date
        $nextReviewAt = now()->addDays($newInterval);

        return [
            'ease_factor' => round($newEaseFactor, 2),
            'interval' => $newInterval,
            'repetitions' => $newRepetitions,
            'next_review_at' => $nextReviewAt,
        ];
    }

    /**
     * Get flashcards that are due for review.
     * 
     * @param User $user The user whose cards to fetch
     * @param int $limit Maximum number of cards to return
     * @return Collection
     */
    public function getDueCards(User $user, int $limit = 20): Collection
    {
        return $user->flashcards()
            ->due()
            ->orderBy('next_review_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get new flashcards that haven't been reviewed yet.
     * 
     * @param User $user The user whose cards to fetch
     * @param int $limit Maximum number of cards to return
     * @return Collection
     */
    public function getNewCards(User $user, int $limit = 10): Collection
    {
        return $user->flashcards()
            ->new()
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Record a flashcard review and update the card's state.
     * 
     * @param Flashcard $card The flashcard being reviewed
     * @param int $rating Rating from 1-4
     * @param int $duration Duration of the review in seconds
     * @return FlashcardReview
     */
    public function recordReview(Flashcard $card, int $rating, int $duration): FlashcardReview
    {
        return DB::transaction(function () use ($card, $rating, $duration) {
            // Store previous interval for the review record
            $previousInterval = $card->interval;

            // Calculate new review parameters
            $reviewData = $this->calculateNextReview($card, $rating);

            // Create the review record
            $review = FlashcardReview::create([
                'flashcard_id' => $card->id,
                'rating' => $rating,
                'duration_seconds' => $duration,
                'previous_interval' => $previousInterval,
                'new_interval' => $reviewData['interval'],
                'reviewed_at' => now(),
            ]);

            // Update the flashcard with new parameters
            $card->update([
                'ease_factor' => $reviewData['ease_factor'],
                'interval' => $reviewData['interval'],
                'repetitions' => $reviewData['repetitions'],
                'next_review_at' => $reviewData['next_review_at'],
            ]);

            // Clear flashcard cache for the user
            $this->cacheService->clearFlashcardCache($card->user);

            return $review;
        });
    }
}
