<?php

namespace Database\Factories;

use App\Models\Dialogue;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class DialogueFactory extends Factory
{
    protected $model = Dialogue::class;

    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'title' => fake()->sentence(3),
            'content' => [
                ['speaker' => 'A', 'line' => 'こんにちは'],
                ['speaker' => 'B', 'line' => 'こんにちは、元気ですか？'],
            ],
            'order' => fake()->numberBetween(1, 100),
        ];
    }
}
