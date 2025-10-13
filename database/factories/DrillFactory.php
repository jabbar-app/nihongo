<?php

namespace Database\Factories;

use App\Models\Drill;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class DrillFactory extends Factory
{
    protected $model = Drill::class;

    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'type' => $this->faker->randomElement(['substitution', 'transformation', 'cloze']),
            'title' => $this->faker->sentence(),
            'content' => [
                'question' => $this->faker->sentence(),
                'prompt' => $this->faker->sentence(),
            ],
            'answers' => [
                'correct' => $this->faker->word(),
            ],
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
