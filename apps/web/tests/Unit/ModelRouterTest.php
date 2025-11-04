<?php

namespace Tests\Unit;

use App\LLM\Clients\AnthropicClient;
use App\LLM\Clients\GoogleGenAiClient;
use App\LLM\Clients\OpenAiClient;
use App\LLM\ModelRouter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRouterTest extends TestCase
{
    use RefreshDatabase;

    protected ModelRouter $modelRouter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->modelRouter = new ModelRouter;
    }

    public function test_can_get_openai_client()
    {
        $client = $this->modelRouter->clientFor('gpt-4o');
        $this->assertInstanceOf(OpenAiClient::class, $client);
    }

    public function test_can_get_anthropic_client()
    {
        $client = $this->modelRouter->clientFor('claude-3-sonnet-20240229');
        $this->assertInstanceOf(AnthropicClient::class, $client);
    }

    public function test_can_get_google_client()
    {
        $client = $this->modelRouter->clientFor('gemini-pro');
        $this->assertInstanceOf(GoogleGenAiClient::class, $client);
    }

    public function test_auto_routing_returns_client()
    {
        $client = $this->modelRouter->clientFor('auto');
        $this->assertNotNull($client);
        $this->assertInstanceOf(OpenAiClient::class, $client);
    }

    public function test_unknown_model_returns_null()
    {
        $client = $this->modelRouter->clientFor('unknown-model');
        $this->assertNull($client);
    }

    public function test_can_get_available_models()
    {
        // Seed some model configs for testing
        \App\Models\ModelConfig::create([
            'provider' => 'openai',
            'model_key' => 'gpt-4o',
            'display_name' => 'GPT-4o',
            'context_window' => 128000,
            'multiplier' => 1.0,
            'enabled' => true,
        ]);

        $models = $this->modelRouter->getAvailableModels();
        $this->assertIsArray($models);
        $this->assertNotEmpty($models);
    }

    public function test_available_models_have_required_fields()
    {
        // Seed some model configs for testing
        \App\Models\ModelConfig::create([
            'provider' => 'openai',
            'model_key' => 'gpt-4o',
            'display_name' => 'GPT-4o',
            'context_window' => 128000,
            'multiplier' => 1.0,
            'enabled' => true,
        ]);

        $models = $this->modelRouter->getAvailableModels();

        foreach ($models as $model) {
            $this->assertArrayHasKey('provider', $model);
            $this->assertArrayHasKey('model_key', $model);
            $this->assertArrayHasKey('display_name', $model);
            $this->assertArrayHasKey('context_window', $model);
            $this->assertArrayHasKey('multiplier', $model);
            $this->assertArrayHasKey('enabled', $model);
        }
    }
}
