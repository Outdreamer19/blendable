<?php

namespace App\LLM\Clients;

use App\LLM\Contracts\LlmClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnthropicClient implements LlmClient
{
    protected string $apiKey;

    protected string $baseUrl = 'https://api.anthropic.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key', env('ANTHROPIC_API_KEY'));
    }

    public function stream(array $messages, array $options = []): \Generator
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'anthropic-version' => '2023-06-01',
        ])->timeout(60)->stream(function ($stream) use ($messages, $options) {
            $payload = [
                'model' => $options['model'] ?? 'claude-3-5-sonnet-20241022',
                'messages' => $this->convertMessages($messages),
                'stream' => true,
                'temperature' => $options['temperature'] ?? 0.7,
                'max_tokens' => $options['max_tokens'] ?? 4000,
            ];

            $stream->write('data: '.json_encode($payload)."\n\n");
        }, $this->baseUrl.'/messages');

        foreach ($response->stream() as $chunk) {
            $line = trim($chunk);
            if (str_starts_with($line, 'data: ')) {
                $data = substr($line, 6);
                if ($data === '[DONE]') {
                    break;
                }

                $decoded = json_decode($data, true);
                if (isset($decoded['delta']['text'])) {
                    yield $decoded['delta']['text'];
                }
            }
        }
    }

    public function complete(array $messages, array $options = []): array
    {
        $startTime = microtime(true);

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'anthropic-version' => '2023-06-01',
        ])->post($this->baseUrl.'/messages', [
            'model' => $options['model'] ?? 'claude-3-5-sonnet-20241022',
            'messages' => $this->convertMessages($messages),
            'temperature' => $options['temperature'] ?? 0.7,
            'max_tokens' => $options['max_tokens'] ?? 4000,
        ]);

        $latency = round((microtime(true) - $startTime) * 1000, 2);

        if (! $response->successful()) {
            Log::error('Anthropic API error', ['response' => $response->body()]);
            throw new \Exception('Anthropic API request failed: '.$response->body());
        }

        $data = $response->json();
        $usage = $data['usage'] ?? [];

        return [
            'content' => $data['content'][0]['text'],
            'tokens_in' => $usage['input_tokens'] ?? 0,
            'tokens_out' => $usage['output_tokens'] ?? 0,
            'model_used' => $data['model'] ?? ($options['model'] ?? 'claude-3-5-sonnet-20241022'),
            'provider' => 'anthropic',
            'metadata' => [
                'latency_ms' => $latency,
                'total_tokens' => ($usage['input_tokens'] ?? 0) + ($usage['output_tokens'] ?? 0),
                'stop_reason' => $data['stop_reason'] ?? null,
                'id' => $data['id'] ?? null,
            ],
        ];
    }

    public function embeddings(string $text, array $options = []): array
    {
        // Anthropic doesn't have embeddings API, return empty array
        return [
            'embedding' => [],
        ];
    }

    protected function convertMessages(array $messages): array
    {
        $converted = [];
        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                // Anthropic handles system messages differently
                continue;
            }

            $converted[] = [
                'role' => $message['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $message['content'],
            ];
        }

        return $converted;
    }
}
