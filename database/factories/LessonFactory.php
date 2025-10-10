<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        
        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'description' => fake()->paragraph(),
            'content_path' => 'content/lang-001-test',
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
