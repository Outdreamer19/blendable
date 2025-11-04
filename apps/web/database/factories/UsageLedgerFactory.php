<?php

namespace Database\Factories;

use App\Models\UsageLedger;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsageLedger>
 */
class UsageLedgerFactory extends Factory
{
    protected $model = UsageLedger::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tokensIn = fake()->numberBetween(10, 1000);
        $tokensOut = fake()->numberBetween(10, 2000);

        return [
            'user_id' => User::factory(),
            'workspace_id' => Workspace::factory(),
            'model_key' => fake()->randomElement(['gpt-4o', 'gpt-4', 'claude-3-opus', 'claude-3-sonnet', 'gemini-pro']),
            'tokens_in' => $tokensIn,
            'tokens_out' => $tokensOut,
            'words_debited' => fake()->numberBetween(50, 500),
            'multiplier' => fake()->randomFloat(2, 0.5, 3.0),
            'cost_usd' => fake()->randomFloat(4, 0.001, 0.1),
            'usage_date' => fake()->date(),
        ];
    }

    /**
     * Indicate that the usage is for a chat request.
     */
    public function chat(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_type' => 'chat',
        ]);
    }

    /**
     * Indicate that the usage is for an image generation request.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_type' => 'image',
            'tokens_in' => 0,
            'tokens_out' => 0,
            'words_debited' => fake()->numberBetween(100, 500),
        ]);
    }

    /**
     * Indicate that the usage is for a completion request.
     */
    public function completion(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_type' => 'completion',
        ]);
    }
}
