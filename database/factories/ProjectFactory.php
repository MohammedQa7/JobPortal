<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'assigned_user' => null,
            'title' => fake()->jobTitle(),
            'slug' => fake()->slug(),
            'description' => fake()->text(150),
            'budget' => fake()->numberBetween(5, 1000000),
            'duration' => fake()->numberBetween(1, 365),
            'status' => fake()->randomElement(['Closed', 'Opened', 'Canceled']),
            'skills' => implode(',', fake()->randomElements(['PHP', 'JS', 'PYTHON', 'TAILWIND', 'ALPINE', 'MECATRONICS'], 3)),
        ];
    }
}