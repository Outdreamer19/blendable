<?php

namespace App\LLM\Clients;

use App\LLM\Contracts\LlmClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiClient implements LlmClient
{
    protected string $apiKey;

    protected string $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
    }

    public function stream(array $messages, array $options = []): \Generator
    {
        // For now, use the complete method and yield the full response
        // This avoids the streaming complexity while maintaining the interface
        try {
            $response = $this->complete($messages, $options);
            $content = $response['content'] ?? '';

            // Yield the content in chunks to simulate streaming
            $words = explode(' ', $content);
            $chunkSize = 3; // Yield 3 words at a time

            for ($i = 0; $i < count($words); $i += $chunkSize) {
                $chunk = implode(' ', array_slice($words, $i, $chunkSize));
                if ($i + $chunkSize < count($words)) {
                    $chunk .= ' ';
                }
                yield $chunk;
            }
        } catch (\Exception $e) {
            Log::error('OpenAI streaming error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function complete(array $messages, array $options = []): array
    {
        $startTime = microtime(true);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl.'/chat/completions', [
            'model' => $options['model'] ?? 'gpt-4o',
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? 0.7,
            'max_tokens' => $options['max_tokens'] ?? 4000,
        ]);

        $latency = round((microtime(true) - $startTime) * 1000, 2);

        if (! $response->successful()) {
            Log::error('OpenAI API error', ['response' => $response->body()]);
            throw new \Exception('OpenAI API request failed: '.$response->body());
        }

        $data = $response->json();
        $choice = $data['choices'][0];
        $usage = $data['usage'] ?? [];

        return [
            'content' => $choice['message']['content'],
            'tokens_in' => $usage['prompt_tokens'] ?? 0,
            'tokens_out' => $usage['completion_tokens'] ?? 0,
            'model_used' => $data['model'] ?? ($options['model'] ?? 'gpt-4o'),
            'provider' => 'openai',
            'metadata' => [
                'latency_ms' => $latency,
                'total_tokens' => $usage['total_tokens'] ?? 0,
                'finish_reason' => $choice['finish_reason'] ?? null,
                'response_id' => $data['id'] ?? null,
                'created' => $data['created'] ?? null,
            ],
        ];
    }

    public function embeddings(string $text, array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl.'/embeddings', [
            'model' => $options['model'] ?? 'text-embedding-3-small',
            'input' => $text,
        ]);

        if (! $response->successful()) {
            Log::error('OpenAI Embeddings API error', ['response' => $response->body()]);
            throw new \Exception('OpenAI Embeddings API request failed: '.$response->body());
        }

        $data = $response->json();

        return [
            'embedding' => $data['data'][0]['embedding'],
        ];
    }
}
