<?php

namespace App\Tools;

use App\Tools\Contracts\Tool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebSearchTool implements Tool
{
    public function getName(): string
    {
        return 'web_search';
    }

    public function getDescription(): string
    {
        return 'Search the web for current information on any topic';
    }

    public function getParameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => [
                    'type' => 'string',
                    'description' => 'The search query to look up on the web',
                ],
                'num_results' => [
                    'type' => 'integer',
                    'description' => 'Number of search results to return (default: 5)',
                    'default' => 5,
                ],
            ],
            'required' => ['query'],
        ];
    }

    public function execute(array $parameters): array
    {
        $query = $parameters['query'] ?? '';
        $numResults = $parameters['num_results'] ?? 5;

        if (empty($query)) {
            return [
                'error' => 'Search query is required',
            ];
        }

        try {
            // Use Perplexity API for web search
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.config('services.perplexity.api_key', env('PERPLEXITY_API_KEY')),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.perplexity.ai/chat/completions', [
                'model' => 'llama-3.1-sonar-small-128k-online',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Search for: {$query}. Return {$numResults} relevant results with titles, URLs, and brief summaries.",
                    ],
                ],
                'max_tokens' => 1000,
            ]);

            if (! $response->successful()) {
                Log::error('Perplexity API error', ['response' => $response->body()]);

                return [
                    'error' => 'Failed to perform web search',
                ];
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';

            return [
                'results' => $content,
                'query' => $query,
                'num_results' => $numResults,
            ];

        } catch (\Exception $e) {
            Log::error('Web search error', ['error' => $e->getMessage()]);

            return [
                'error' => 'An error occurred while searching the web',
            ];
        }
    }
}
