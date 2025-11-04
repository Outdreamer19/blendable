<?php

namespace App\Tools;

use App\Models\ImageJob;
use App\Tools\Contracts\Tool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class ImageGenerationTool implements Tool
{
    public function getName(): string
    {
        return 'image_generation';
    }

    public function getDescription(): string
    {
        return 'Generate images using AI models like DALL-E, Midjourney, or Stable Diffusion';
    }

    public function getParameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'prompt' => [
                    'type' => 'string',
                    'description' => 'The text prompt describing the image to generate',
                ],
                'model' => [
                    'type' => 'string',
                    'enum' => ['dall-e-3', 'dall-e-2', 'stable-diffusion', 'midjourney'],
                    'description' => 'The AI model to use for image generation',
                    'default' => 'dall-e-3',
                ],
                'size' => [
                    'type' => 'string',
                    'enum' => ['1024x1024', '1024x1792', '1792x1024', '512x512'],
                    'description' => 'The size of the generated image',
                    'default' => '1024x1024',
                ],
                'quality' => [
                    'type' => 'string',
                    'enum' => ['standard', 'hd'],
                    'description' => 'The quality of the generated image (DALL-E 3 only)',
                    'default' => 'standard',
                ],
                'style' => [
                    'type' => 'string',
                    'enum' => ['vivid', 'natural'],
                    'description' => 'The style of the generated image (DALL-E 3 only)',
                    'default' => 'vivid',
                ],
            ],
            'required' => ['prompt'],
        ];
    }

    public function execute(array $parameters): array
    {
        $prompt = $parameters['prompt'] ?? '';
        $model = $parameters['model'] ?? 'dall-e-3';
        $size = $parameters['size'] ?? '1024x1024';
        $quality = $parameters['quality'] ?? 'standard';
        $style = $parameters['style'] ?? 'vivid';

        if (empty($prompt)) {
            return [
                'error' => 'Prompt is required for image generation',
            ];
        }

        try {
            // Create image job record
            $imageJob = ImageJob::create([
                'user_id' => auth()->id(),
                'workspace_id' => auth()->user()->currentWorkspace()?->id,
                'prompt' => $prompt,
                'model' => $model,
                'size' => $size,
                'quality' => $quality,
                'style' => $style,
                'status' => 'pending',
            ]);

            // Queue the image generation job
            Queue::push(new \App\Jobs\GenerateImageJob($imageJob));

            return [
                'success' => true,
                'job_id' => $imageJob->id,
                'status' => 'pending',
                'message' => 'Image generation started. You will be notified when it\'s ready.',
            ];

        } catch (\Exception $e) {
            Log::error('Image generation error', ['error' => $e->getMessage()]);

            return [
                'error' => 'Failed to start image generation',
            ];
        }
    }

    protected function generateWithDallE(array $parameters): array
    {
        $prompt = $parameters['prompt'];
        $model = $parameters['model'];
        $size = $parameters['size'];
        $quality = $parameters['quality'] ?? 'standard';
        $style = $parameters['style'] ?? 'vivid';

        $payload = [
            'model' => $model,
            'prompt' => $prompt,
            'size' => $size,
            'n' => 1,
        ];

        if ($model === 'dall-e-3') {
            $payload['quality'] = $quality;
            $payload['style'] = $style;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.openai.api_key', env('OPENAI_API_KEY')),
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/images/generations', $payload);

        if (! $response->successful()) {
            Log::error('DALL-E API error', ['response' => $response->body()]);

            return [
                'error' => 'Failed to generate image with DALL-E',
            ];
        }

        $data = $response->json();
        $imageUrl = $data['data'][0]['url'] ?? null;

        if (! $imageUrl) {
            return [
                'error' => 'No image URL returned from DALL-E',
            ];
        }

        return [
            'success' => true,
            'image_url' => $imageUrl,
            'model' => $model,
            'size' => $size,
        ];
    }

    protected function generateWithStableDiffusion(array $parameters): array
    {
        $prompt = $parameters['prompt'];
        $size = $parameters['size'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.stability.api_key', env('STABILITY_API_KEY')),
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.stability.ai/v1/generation/stable-diffusion-xl-1024-v1-0/text-to-image', [
            'text_prompts' => [
                [
                    'text' => $prompt,
                    'weight' => 1,
                ],
            ],
            'cfg_scale' => 7,
            'height' => (int) explode('x', $size)[1],
            'width' => (int) explode('x', $size)[0],
            'samples' => 1,
            'steps' => 30,
        ]);

        if (! $response->successful()) {
            Log::error('Stable Diffusion API error', ['response' => $response->body()]);

            return [
                'error' => 'Failed to generate image with Stable Diffusion',
            ];
        }

        $data = $response->json();
        $imageBase64 = $data['artifacts'][0]['base64'] ?? null;

        if (! $imageBase64) {
            return [
                'error' => 'No image data returned from Stable Diffusion',
            ];
        }

        return [
            'success' => true,
            'image_base64' => $imageBase64,
            'model' => 'stable-diffusion',
            'size' => $size,
        ];
    }
}
