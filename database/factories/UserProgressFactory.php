<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProgress>
 */
class UserProgressFactory extends Factory
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
            'lesson_id' => Lesson::factory(),
            'phrases_viewed' => 0,
            'dialogues_viewed' => 0,
            'drills_completed' => 0,
            'shadowing_completed' => 0,
            'completion_percentage' => 0,
            'last_accessed_at' => now(),
        ];
    }
}
