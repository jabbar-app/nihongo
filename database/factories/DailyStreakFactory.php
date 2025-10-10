<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyStreak>
 */
class DailyStreakFactory extends Factory
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
            'date' => Carbon::today(),
            'activities_count' => fake()->numberBetween(1, 10),
            'study_minutes' => fake()->numberBetween(10, 180),
            'xp_earned' => fake()->numberBetween(50, 500),
        ];
    }
}
