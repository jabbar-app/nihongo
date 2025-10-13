<?php

namespace Database\Factories;

use App\Models\ShadowingExercise;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShadowingExerciseFactory extends Factory
{
    protected $model = ShadowingExercise::class;

    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'title' => $this->faker->sentence(),
            'content' => [
                ['speaker' => 'A', 'line' => $this->faker->sentence()],
                ['speaker' => 'B', 'line' => $this->faker->sentence()],
            ],
            'audio_url' => null,
            'duration_seconds' => $this->faker->numberBetween(60, 300),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
