<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Phrase;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhraseFactory extends Factory
{
    protected $model = Phrase::class;

    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'japanese' => 'こんにちは',
            'romaji' => 'konnichiwa',
            'english' => 'Hello',
            'notes' => fake()->optional()->sentence(),
            'category' => fake()->optional()->word(),
            'order' => fake()->numberBetween(1, 100),
        ];
    }
}
