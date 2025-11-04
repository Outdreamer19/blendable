<?php

namespace Database\Factories;

use App\Models\Prompt;
use App\Models\PromptFolder;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prompt>
 */
class PromptFactory extends Factory
{
    protected $model = Prompt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'workspace_id' => Workspace::factory(),
            'folder_id' => null,
            'name' => fake()->words(3, true),
            'content' => fake()->paragraphs(3, true),
            'description' => fake()->sentence(),
            'tags' => fake()->words(3),
            'is_public' => fake()->boolean(20), // 20% chance of being public
            'is_favorite' => fake()->boolean(10), // 10% chance of being favorite
            'usage_count' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the prompt is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }

    /**
     * Indicate that the prompt is in a folder.
     */
    public function inFolder(PromptFolder $folder): static
    {
        return $this->state(fn (array $attributes) => [
            'folder_id' => $folder->id,
        ]);
    }
}
