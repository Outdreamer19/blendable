<?php
namespace App\LLM;

use App\LLM\Contracts\LlmClient;

class ModelRouter
{
    /** @var array<string,LlmClient> */
    protected array $clients = [];

    public function register(string $key, LlmClient $client): void
    {
        $this->clients[$key] = $client;
    }

    public function clientFor(string $modelKey): LlmClient
    {
        // very simple: choose by prefix
        foreach ($this->clients as $key => $client) {
            if (str_starts_with($modelKey, $key)) return $client;
        }
        // default to openai
        return $this->clients['openai'] ?? array_values($this->clients)[0];
    }
}
