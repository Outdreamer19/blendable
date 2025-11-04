<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'workspace_id' => \App\Models\Workspace::factory(),
            'persona_id' => null,
            'model_key' => 'gpt-4o',
            'title' => fake()->sentence(3),
            'share_token' => null,
            'is_shared_view_only' => false,
            'settings' => [],
            'last_message_at' => now(),
        ];
    }
}
