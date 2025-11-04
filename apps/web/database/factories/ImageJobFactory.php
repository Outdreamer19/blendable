<?php

namespace Database\Factories;

use App\Models\ImageJob;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImageJob>
 */
class ImageJobFactory extends Factory
{
    protected $model = ImageJob::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'processing', 'completed', 'failed'];
        $status = fake()->randomElement($statuses);

        return [
            'user_id' => User::factory(),
            'workspace_id' => Workspace::factory(),
            'prompt' => fake()->sentence(),
            'model' => fake()->randomElement(['dall-e-3', 'dall-e-2', 'stable-diffusion']),
            'size' => fake()->randomElement(['1024x1024', '1024x1792', '1792x1024']),
            'quality' => fake()->randomElement(['standard', 'hd']),
            'style' => fake()->randomElement(['vivid', 'natural']),
            'status' => $status,
            'image_url' => $status === 'completed' ? fake()->imageUrl() : null,
            'error_message' => $status === 'failed' ? fake()->sentence() : null,
            'metadata' => [
                'generation_time' => fake()->numberBetween(1, 10),
                'api_provider' => fake()->randomElement(['openai', 'stability']),
            ],
        ];
    }

    /**
     * Indicate that the image job is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'image_url' => fake()->imageUrl(),
            'error_message' => null,
        ]);
    }

    /**
     * Indicate that the image job failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'image_url' => null,
            'error_message' => fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the image job is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'image_url' => null,
            'error_message' => null,
        ]);
    }
}
