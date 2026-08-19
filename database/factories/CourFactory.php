<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Cour>
 */
class CourFactory extends Factory
{
    protected $model = \App\Models\Cour::class;

    public function definition(): array
    {
        return [
            'titre' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'niveau' => 'debutant',
            'prix' => fake()->numberBetween(5000, 30000),
            'statut' => 'non_publie',
            'user_id' => User::factory()->state(['role' => 'administrateur']),
        ];
    }
}
