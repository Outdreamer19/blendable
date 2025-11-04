<?php

namespace App\Jobs;

use App\Models\ImageJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public ImageJob $imageJob
    ) {}

    public function handle(): void
    {
        try {
            $this->imageJob->update(['status' => 'processing']);

            $result = $this->generateImage();

            if (isset($result['error'])) {
                $this->imageJob->markAsFailed($result['error']);

                return;
            }

            // Download and store the image
            $imageUrl = $result['image_url'] ?? null;
            $imageBase64 = $result['image_base64'] ?? null;

            if ($imageUrl) {
                $storedUrl = $this->downloadAndStoreImage($imageUrl);
                $this->imageJob->markAsCompleted($storedUrl, $result);
            } elseif ($imageBase64) {
                $storedUrl = $this->storeBase64Image($imageBase64);
                $this->imageJob->markAsCompleted($storedUrl, $result);
            } else {
                $this->imageJob->markAsFailed('No image data received');
            }

        } catch (\Exception $e) {
            Log::error('Image generation job failed', [
                'job_id' => $this->imageJob->id,
                'error' => $e->getMessage(),
            ]);

            $this->imageJob->markAsFailed($e->getMessage());
        }
    }

    protected function generateImage(): array
    {
        $model = $this->imageJob->model;

        switch ($model) {
            case 'dall-e-3':
            case 'dall-e-2':
                return $this->generateWithDallE();
            case 'stable-diffusion':
                return $this->generateWithStableDiffusion();
            default:
                return ['error' => "Unsupported model: {$model}"];
        }
    }

    protected function generateWithDallE(): array
    {
        $payload = [
            'model' => $this->imageJob->model,
            'prompt' => $this->imageJob->prompt,
            'size' => $this->imageJob->size,
            'n' => 1,
        ];

        if ($this->imageJob->model === 'dall-e-3') {
            $payload['quality'] = $this->imageJob->quality;
            $payload['style'] = $this->imageJob->style;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.openai.api_key', env('OPENAI_API_KEY')),
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/images/generations', $payload);

        if (! $response->successful()) {
            Log::error('DALL-E API error', ['response' => $response->body()]);

            return ['error' => 'Failed to generate image with DALL-E'];
        }

        $data = $response->json();
        $imageUrl = $data['data'][0]['url'] ?? null;

        if (! $imageUrl) {
            return ['error' => 'No image URL returned from DALL-E'];
        }

        return [
            'image_url' => $imageUrl,
            'model' => $this->imageJob->model,
            'size' => $this->imageJob->size,
        ];
    }

    protected function generateWithStableDiffusion(): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.stability.api_key', env('STABILITY_API_KEY')),
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.stability.ai/v1/generation/stable-diffusion-xl-1024-v1-0/text-to-image', [
            'text_prompts' => [
                [
                    'text' => $this->imageJob->prompt,
                    'weight' => 1,
                ],
            ],
            'cfg_scale' => 7,
            'height' => (int) explode('x', $this->imageJob->size)[1],
            'width' => (int) explode('x', $this->imageJob->size)[0],
            'samples' => 1,
            'steps' => 30,
        ]);

        if (! $response->successful()) {
            Log::error('Stable Diffusion API error', ['response' => $response->body()]);

            return ['error' => 'Failed to generate image with Stable Diffusion'];
        }

        $data = $response->json();
        $imageBase64 = $data['artifacts'][0]['base64'] ?? null;

        if (! $imageBase64) {
            return ['error' => 'No image data returned from Stable Diffusion'];
        }

        return [
            'image_base64' => $imageBase64,
            'model' => 'stable-diffusion',
            'size' => $this->imageJob->size,
        ];
    }

    protected function downloadAndStoreImage(string $imageUrl): string
    {
        $imageData = Http::timeout(60)->get($imageUrl)->body();
        $filename = 'images/'.uniqid().'.png';

        Storage::put($filename, $imageData);

        return Storage::url($filename);
    }

    protected function storeBase64Image(string $imageBase64): string
    {
        $imageData = base64_decode($imageBase64);
        $filename = 'images/'.uniqid().'.png';

        Storage::put($filename, $imageData);

        return Storage::url($filename);
    }
}
