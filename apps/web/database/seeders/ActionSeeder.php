<?php

namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = [
            [
                'name' => 'Web Search',
                'description' => 'Search the web for current information and facts',
                'type' => 'web_search',
                'config' => [
                    'max_results' => 5,
                    'search_engine' => 'perplexity',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'File Manager',
                'description' => 'Manage files within the workspace',
                'type' => 'file_manager',
                'config' => [
                    'allowed_extensions' => ['txt', 'md', 'json', 'csv', 'pdf'],
                    'max_file_size' => 10485760, // 10MB
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Image Generation',
                'description' => 'Generate images from text prompts using AI',
                'type' => 'image_generation',
                'config' => [
                    'models' => ['dall-e-3', 'dall-e-2', 'stable-diffusion'],
                    'max_size' => '1792x1024',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Prompt Enhancer',
                'description' => 'Enhance and optimize prompts for better AI responses',
                'type' => 'prompt_enhancer',
                'config' => [
                    'focus_areas' => ['clarity', 'creativity', 'detail', 'conciseness'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Code Execution',
                'description' => 'Execute code snippets in a sandboxed environment',
                'type' => 'code_execution',
                'config' => [
                    'languages' => ['python', 'javascript', 'bash'],
                    'timeout' => 30,
                ],
                'is_active' => false, // Disabled by default for security
            ],
            [
                'name' => 'Data Analysis',
                'description' => 'Analyze data and create visualizations',
                'type' => 'data_analysis',
                'config' => [
                    'supported_formats' => ['csv', 'json', 'xlsx'],
                    'max_rows' => 10000,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Email Integration',
                'description' => 'Send and receive emails',
                'type' => 'email',
                'config' => [
                    'max_recipients' => 10,
                    'rate_limit' => 100, // per hour
                ],
                'is_active' => false, // Requires additional setup
            ],
            [
                'name' => 'Calendar Integration',
                'description' => 'Manage calendar events and schedules',
                'type' => 'calendar',
                'config' => [
                    'providers' => ['google', 'outlook'],
                    'max_events' => 100,
                ],
                'is_active' => false, // Requires additional setup
            ],
            [
                'name' => 'API Integration',
                'description' => 'Make API calls to external services',
                'type' => 'api_call',
                'config' => [
                    'timeout' => 30,
                    'max_requests' => 100, // per hour
                ],
                'is_active' => false, // Requires additional setup
            ],
        ];

        foreach ($actions as $action) {
            Action::firstOrCreate(
                ['name' => $action['name']],
                [
                    'description' => $action['description'],
                    'type' => $action['type'],
                    'config' => json_encode($action['config']),
                    'is_active' => $action['is_active'],
                ]
            );
        }
    }
}
