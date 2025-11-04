<?php

namespace App\LLM;

use App\LLM\Clients\AnthropicClient;
use App\LLM\Clients\GoogleGenAiClient;
use App\LLM\Clients\OpenAiClient;
use App\LLM\Contracts\LlmClient;
use App\Models\ModelConfig;

class ModelRouter
{
    /** @var array<string,LlmClient> */
    protected array $clients = [];

    public function __construct()
    {
        $this->registerClients();
    }

    public function register(string $key, LlmClient $client): void
    {
        $this->clients[$key] = $client;
    }

    public function clientFor(string $modelKey): ?LlmClient
    {
        // Handle Auto routing
        if ($modelKey === 'auto') {
            return $this->getAutoClient();
        }

        // Find client by model key prefix
        if (str_starts_with($modelKey, 'gpt-') || str_starts_with($modelKey, 'dall-e-')) {
            return $this->clients['openai'];
        }

        if (str_starts_with($modelKey, 'claude-')) {
            return $this->clients['anthropic'];
        }

        if (str_starts_with($modelKey, 'gemini-')) {
            return $this->clients['google'];
        }

        // Return null for unknown models
        return null;
    }

    public function getAvailableModels(): array
    {
        return ModelConfig::where('enabled', true)->get()->toArray();
    }

    public function streamChatResponse(string $modelKey, array $messages, callable $callback): void
    {
        $client = $this->clientFor($modelKey);

        if (! $client) {
            throw new \Exception("No client available for model: {$modelKey}");
        }

        // If modelKey is 'auto', use a default model for the client
        $actualModel = $modelKey;
        if ($modelKey === 'auto') {
            $actualModel = $this->getDefaultModelForClient($client);
        }

        foreach ($client->stream($messages, ['model' => $actualModel]) as $chunk) {
            $callback($chunk, null);
        }
    }

    public function getChatResponse(string $modelKey, array $messages): string
    {
        $client = $this->clientFor($modelKey);

        if (! $client) {
            throw new \Exception("No client available for model: {$modelKey}");
        }

        // If modelKey is 'auto', use a default model for the client
        $actualModel = $modelKey;
        if ($modelKey === 'auto') {
            $actualModel = $this->getDefaultModelForClient($client);
        }

        $response = $client->complete($messages, ['model' => $actualModel]);

        return $response['content'] ?? '';
    }

    /**
     * Get enhanced chat response with detailed metadata
     */
    public function getEnhancedChatResponse(string $modelKey, array $messages, array $context = []): array
    {
        $client = $this->clientFor($modelKey);

        if (! $client) {
            throw new \Exception("No client available for model: {$modelKey}");
        }

        // If modelKey is 'auto', use a default model for the client
        $actualModel = $modelKey;
        if ($modelKey === 'auto') {
            $actualModel = $this->getDefaultModelForClient($client);
        }

        $response = $client->complete($messages, ['model' => $actualModel]);

        // Extract provider name from client class
        $provider = $this->getProviderFromClient($client);

        // Build enhanced response
        $enhancedResponse = [
            'success' => true,
            'provider' => $provider,
            'modelUsed' => $response['model_used'] ?? $actualModel,
            'latencyMs' => $response['metadata']['latency_ms'] ?? 0,
            'inputTokens' => $response['tokens_in'] ?? 0,
            'outputTokens' => $response['tokens_out'] ?? 0,
            'totalTokens' => $response['metadata']['total_tokens'] ?? (($response['tokens_in'] ?? 0) + ($response['tokens_out'] ?? 0)),
            'responseText' => $response['content'] ?? '',
            'metadata' => $response['metadata'] ?? [],
        ];

        // Add context data if provided
        if (! empty($context)) {
            $enhancedResponse['context'] = $context;
        }

        return $enhancedResponse;
    }

    /**
     * Get provider name from client instance
     */
    protected function getProviderFromClient($client): string
    {
        $className = get_class($client);

        if (str_contains($className, 'OpenAiClient')) {
            return 'openai';
        }
        if (str_contains($className, 'AnthropicClient')) {
            return 'anthropic';
        }
        if (str_contains($className, 'GoogleGenAiClient')) {
            return 'google';
        }

        return 'unknown';
    }

    protected function registerClients(): void
    {
        $this->register('openai', new OpenAiClient);
        $this->register('anthropic', new AnthropicClient);
        $this->register('google', new GoogleGenAiClient);
    }

    protected function getAutoClient(): LlmClient
    {
        // Simple auto routing logic - can be enhanced with heuristics
        // For now, default to GPT-4o for general tasks
        return $this->clients['openai'];
    }

    protected function getDefaultModelForClient(LlmClient $client): string
    {
        // Return appropriate default models for each client
        if ($client instanceof OpenAiClient) {
            return 'gpt-4o';
        }
        if ($client instanceof AnthropicClient) {
            return 'claude-3-5-sonnet-20241022';
        }
        if ($client instanceof GoogleGenAiClient) {
            return 'gemini-1.5-pro';
        }

        // Fallback
        return 'gpt-4o';
    }
}
