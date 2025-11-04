<?php

namespace App\LLM\Clients;

use App\LLM\Contracts\LlmClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleGenAiClient implements LlmClient
{
    protected string $apiKey;

    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    public function __construct()
    {
        $this->apiKey = config('services.google.api_key', env('GOOGLE_API_KEY'));
    }

    public function stream(array $messages, array $options = []): \Generator
    {
        $response = Http::withQueryParameters([
            'key' => $this->apiKey,
        ])->timeout(60)->stream(function ($stream) use ($messages, $options) {
            $payload = [
                'contents' => $this->convertMessages($messages),
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? 0.7,
                    'maxOutputTokens' => $options['max_tokens'] ?? 4000,
                ],
            ];

            $stream->write('data: '.json_encode($payload)."\n\n");
        }, $this->baseUrl.'/models/'.($options['model'] ?? 'gemini-1.5-pro').':streamGenerateContent');

        foreach ($response->stream() as $chunk) {
            $line = trim($chunk);
            if (str_starts_with($line, 'data: ')) {
                $data = substr($line, 6);
                if ($data === '[DONE]') {
                    break;
                }

                $decoded = json_decode($data, true);
                if (isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                    yield $decoded['candidates'][0]['content']['parts'][0]['text'];
                }
            }
        }
    }

    public function complete(array $messages, array $options = []): array
    {
        $startTime = microtime(true);

        $response = Http::withQueryParameters([
            'key' => $this->apiKey,
        ])->post($this->baseUrl.'/models/'.($options['model'] ?? 'gemini-1.5-pro').':generateContent', [
            'contents' => $this->convertMessages($messages),
            'generationConfig' => [
                'temperature' => $options['temperature'] ?? 0.7,
                'maxOutputTokens' => $options['max_tokens'] ?? 4000,
            ],
        ]);

        $latency = round((microtime(true) - $startTime) * 1000, 2);

        if (! $response->successful()) {
            Log::error('Google GenAI API error', ['response' => $response->body()]);
            throw new \Exception('Google GenAI API request failed: '.$response->body());
        }

        $data = $response->json();
        $candidate = $data['candidates'][0];
        $usageMetadata = $data['usageMetadata'] ?? [];

        return [
            'content' => $candidate['content']['parts'][0]['text'],
            'tokens_in' => $usageMetadata['promptTokenCount'] ?? 0,
            'tokens_out' => $usageMetadata['candidatesTokenCount'] ?? 0,
            'model_used' => $options['model'] ?? 'gemini-1.5-pro',
            'provider' => 'google',
            'metadata' => [
                'latency_ms' => $latency,
                'total_tokens' => ($usageMetadata['promptTokenCount'] ?? 0) + ($usageMetadata['candidatesTokenCount'] ?? 0),
                'finish_reason' => $candidate['finishReason'] ?? null,
                'safety_ratings' => $candidate['safetyRatings'] ?? null,
            ],
        ];
    }

    public function embeddings(string $text, array $options = []): array
    {
        $response = Http::withQueryParameters([
            'key' => $this->apiKey,
        ])->post($this->baseUrl.'/models/'.($options['model'] ?? 'text-embedding-004').':embedContent', [
            'content' => [
                'parts' => [
                    ['text' => $text],
                ],
            ],
        ]);

        if (! $response->successful()) {
            Log::error('Google Embeddings API error', ['response' => $response->body()]);
            throw new \Exception('Google Embeddings API request failed: '.$response->body());
        }

        $data = $response->json();

        return [
            'embedding' => $data['embedding']['values'],
        ];
    }

    protected function convertMessages(array $messages): array
    {
        $contents = [];
        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                // Google handles system messages as the first user message
                $contents[] = [
                    'role' => 'user',
                    'parts' => [['text' => 'System: '.$message['content']]],
                ];
            } else {
                $contents[] = [
                    'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $message['content']]],
                ];
            }
        }

        return $contents;
    }
}
