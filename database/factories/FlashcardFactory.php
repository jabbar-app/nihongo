<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Phrase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flashcard>
 */
class FlashcardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phrase_id' => null,
            'front' => fake()->sentence(3),
            'back' => fake()->sentence(4),
            'romaji' => fake()->sentence(3),
            'ease_factor' => 2.5,
            'interval' => 0,
            'repetitions' => 0,
            'next_review_at' => now(),
        ];
    }
}
