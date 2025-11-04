<?php

namespace Database\Seeders;

use App\Models\ModelConfig;
use Illuminate\Database\Seeder;

class ModelConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            // OpenAI Models
            [
                'provider' => 'openai',
                'model_key' => 'gpt-4o',
                'display_name' => 'GPT-4o',
                'context_window' => 128000,
                'multiplier' => 1.0,
                'cost_per_1k_tokens_in' => 0.005,
                'cost_per_1k_tokens_out' => 0.015,
                'capabilities' => ['text', 'vision'],
                'enabled' => true,
            ],
            [
                'provider' => 'openai',
                'model_key' => 'gpt-4',
                'display_name' => 'GPT-4',
                'context_window' => 8192,
                'multiplier' => 1.0,
                'cost_per_1k_tokens_in' => 0.03,
                'cost_per_1k_tokens_out' => 0.06,
                'capabilities' => ['text'],
                'enabled' => true,
            ],
            [
                'provider' => 'openai',
                'model_key' => 'gpt-3.5-turbo',
                'display_name' => 'GPT-3.5 Turbo',
                'context_window' => 16384,
                'multiplier' => 0.5,
                'cost_per_1k_tokens_in' => 0.0015,
                'cost_per_1k_tokens_out' => 0.002,
                'capabilities' => ['text'],
                'enabled' => true,
            ],

            // Anthropic Models
            [
                'provider' => 'anthropic',
                'model_key' => 'claude-3-5-sonnet-20241022',
                'display_name' => 'Claude 3.5 Sonnet',
                'context_window' => 200000,
                'multiplier' => 1.0,
                'cost_per_1k_tokens_in' => 0.003,
                'cost_per_1k_tokens_out' => 0.015,
                'capabilities' => ['text', 'vision'],
                'enabled' => true,
            ],
            [
                'provider' => 'anthropic',
                'model_key' => 'claude-3-haiku-20240307',
                'display_name' => 'Claude 3 Haiku',
                'context_window' => 200000,
                'multiplier' => 0.8,
                'cost_per_1k_tokens_in' => 0.00025,
                'cost_per_1k_tokens_out' => 0.00125,
                'capabilities' => ['text', 'vision'],
                'enabled' => true,
            ],

            // Google Models
            [
                'provider' => 'google',
                'model_key' => 'gemini-2.5-pro',
                'display_name' => 'Gemini 2.5 Pro',
                'context_window' => 2000000,
                'multiplier' => 1.0,
                'cost_per_1k_tokens_in' => 0.00125,
                'cost_per_1k_tokens_out' => 0.005,
                'capabilities' => ['text', 'vision'],
                'enabled' => true,
            ],
            [
                'provider' => 'google',
                'model_key' => 'gemini-2.5-flash',
                'display_name' => 'Gemini 2.5 Flash',
                'context_window' => 1000000,
                'multiplier' => 0.5,
                'cost_per_1k_tokens_in' => 0.000075,
                'cost_per_1k_tokens_out' => 0.0003,
                'capabilities' => ['text', 'vision'],
                'enabled' => true,
            ],
            [
                'provider' => 'google',
                'model_key' => 'gemini-2.0-flash',
                'display_name' => 'Gemini 2.0 Flash',
                'context_window' => 1000000,
                'multiplier' => 0.3,
                'cost_per_1k_tokens_in' => 0.000075,
                'cost_per_1k_tokens_out' => 0.0003,
                'capabilities' => ['text', 'vision'],
                'enabled' => true,
            ],
        ];

        foreach ($models as $model) {
            ModelConfig::firstOrCreate(
                ['model_key' => $model['model_key']],
                [
                    'provider' => $model['provider'],
                    'display_name' => $model['display_name'],
                    'context_window' => $model['context_window'],
                    'multiplier' => $model['multiplier'],
                    'cost_per_1k_tokens_in' => $model['cost_per_1k_tokens_in'],
                    'cost_per_1k_tokens_out' => $model['cost_per_1k_tokens_out'],
                    'capabilities' => json_encode($model['capabilities']),
                    'enabled' => $model['enabled'],
                ]
            );
        }
    }
}
