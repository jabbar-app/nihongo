<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
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
            'level' => 1,
            'total_xp' => 0,
            'current_streak' => 0,
            'longest_streak' => 0,
            'study_goal_minutes' => 120,
            'cards_per_day_goal' => 20,
        ];
    }
}
