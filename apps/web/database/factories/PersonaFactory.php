<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => \App\Models\Workspace::factory(),
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'system_prompt' => fake()->paragraph(),
            'avatar_url' => null,
            'is_public' => false,
            'is_active' => true,
        ];
    }
}
