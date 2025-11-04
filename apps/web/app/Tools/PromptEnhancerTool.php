<?php

namespace App\Tools;

use App\LLM\ModelRouter;
use App\Tools\Contracts\Tool;
use Illuminate\Support\Facades\Log;

class PromptEnhancerTool implements Tool
{
    protected ModelRouter $modelRouter;

    public function __construct(ModelRouter $modelRouter)
    {
        $this->modelRouter = $modelRouter;
    }

    public function getName(): string
    {
        return 'prompt_enhancer';
    }

    public function getDescription(): string
    {
        return 'Enhance and improve prompts to get better AI responses';
    }

    public function getParameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'prompt' => [
                    'type' => 'string',
                    'description' => 'The original prompt to enhance',
                ],
                'style' => [
                    'type' => 'string',
                    'enum' => ['professional', 'creative', 'technical', 'casual', 'academic'],
                    'description' => 'The style/tone for the enhanced prompt',
                    'default' => 'professional',
                ],
                'context' => [
                    'type' => 'string',
                    'description' => 'Additional context about what the prompt is for',
                ],
                'length' => [
                    'type' => 'string',
                    'enum' => ['short', 'medium', 'long'],
                    'description' => 'Desired length of the enhanced prompt',
                    'default' => 'medium',
                ],
            ],
            'required' => ['prompt'],
        ];
    }

    public function execute(array $parameters): array
    {
        $prompt = $parameters['prompt'] ?? '';
        $style = $parameters['style'] ?? 'professional';
        $context = $parameters['context'] ?? '';
        $length = $parameters['length'] ?? 'medium';

        if (empty($prompt)) {
            return [
                'error' => 'Prompt is required for enhancement',
            ];
        }

        try {
            $enhancementPrompt = $this->buildEnhancementPrompt($prompt, $style, $context, $length);

            $client = $this->modelRouter->clientFor('gpt-4o');
            $response = $client->complete([
                [
                    'role' => 'system',
                    'content' => 'You are an expert prompt engineer. Your job is to enhance and improve prompts to get better, more accurate, and more useful responses from AI models.',
                ],
                [
                    'role' => 'user',
                    'content' => $enhancementPrompt,
                ],
            ]);

            return [
                'success' => true,
                'original_prompt' => $prompt,
                'enhanced_prompt' => $response['content'],
                'style' => $style,
                'length' => $length,
                'context' => $context,
            ];

        } catch (\Exception $e) {
            Log::error('Prompt enhancement error', ['error' => $e->getMessage()]);

            return [
                'error' => 'Failed to enhance prompt',
            ];
        }
    }

    protected function buildEnhancementPrompt(string $prompt, string $style, string $context, string $length): string
    {
        $styleInstructions = [
            'professional' => 'Use a professional, business-like tone with clear structure and formal language.',
            'creative' => 'Use a creative, engaging tone with vivid language and imaginative elements.',
            'technical' => 'Use precise technical language with specific terminology and detailed specifications.',
            'casual' => 'Use a friendly, conversational tone that is easy to understand and approachable.',
            'academic' => 'Use scholarly language with proper citations, formal structure, and academic rigor.',
        ];

        $lengthInstructions = [
            'short' => 'Keep the enhanced prompt concise and to the point (1-2 sentences).',
            'medium' => 'Provide a balanced prompt with sufficient detail (2-4 sentences).',
            'long' => 'Create a comprehensive prompt with extensive detail and context (4+ sentences).',
        ];

        $enhancementPrompt = "Please enhance the following prompt to make it more effective for AI models:\n\n";
        $enhancementPrompt .= "Original prompt: \"{$prompt}\"\n\n";

        if (! empty($context)) {
            $enhancementPrompt .= "Context: {$context}\n\n";
        }

        $enhancementPrompt .= "Requirements:\n";
        $enhancementPrompt .= "- Style: {$styleInstructions[$style]}\n";
        $enhancementPrompt .= "- Length: {$lengthInstructions[$length]}\n";
        $enhancementPrompt .= "- Make it specific and actionable\n";
        $enhancementPrompt .= "- Include relevant examples if helpful\n";
        $enhancementPrompt .= "- Ensure clarity and precision\n";
        $enhancementPrompt .= "- Optimize for better AI responses\n\n";

        $enhancementPrompt .= 'Please provide only the enhanced prompt, without any additional commentary or explanation.';

        return $enhancementPrompt;
    }
}
