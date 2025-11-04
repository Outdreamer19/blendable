<?php

namespace App\Tools;

use App\LLM\ModelRouter;
use App\Tools\Contracts\Tool;
use Illuminate\Support\Facades\Log;

class ToolManager
{
    protected array $tools = [];

    protected ModelRouter $modelRouter;

    public function __construct(ModelRouter $modelRouter)
    {
        $this->modelRouter = $modelRouter;
        $this->registerDefaultTools();
    }

    public function registerTool(Tool $tool): void
    {
        $this->tools[$tool->getName()] = $tool;
    }

    public function getTool(string $name): ?Tool
    {
        return $this->tools[$name] ?? null;
    }

    public function getAllTools(): array
    {
        return $this->tools;
    }

    public function getAvailableTools(): array
    {
        return array_map(function (Tool $tool) {
            return [
                'name' => $tool->getName(),
                'description' => $tool->getDescription(),
                'parameters' => $tool->getParameters(),
            ];
        }, $this->tools);
    }

    public function executeTool(string $name, array $parameters): array
    {
        $tool = $this->getTool($name);

        if (! $tool) {
            return [
                'error' => "Tool '{$name}' not found",
            ];
        }

        try {
            Log::info('Executing tool', [
                'tool' => $name,
                'parameters' => $parameters,
            ]);

            $result = $tool->execute($parameters);

            Log::info('Tool execution completed', [
                'tool' => $name,
                'success' => ! isset($result['error']),
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Tool execution error', [
                'tool' => $name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'error' => 'Tool execution failed: '.$e->getMessage(),
            ];
        }
    }

    public function canExecuteTool(string $name): bool
    {
        return isset($this->tools[$name]);
    }

    protected function registerDefaultTools(): void
    {
        $this->registerTool(new WebSearchTool);
        $this->registerTool(new FileTool);
        $this->registerTool(new ImageGenerationTool);
        $this->registerTool(new PromptEnhancerTool($this->modelRouter));
    }
}
