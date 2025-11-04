<?php

return [
    'models' => [
        ['provider' => 'openai', 'model_key' => 'gpt-4o', 'display_name' => 'GPT‑4o', 'context_window' => 128000, 'multiplier' => 1.0, 'enabled' => true],
        ['provider' => 'anthropic', 'model_key' => 'claude-3-7-sonnet', 'display_name' => 'Claude 3.7 Sonnet', 'context_window' => 200000, 'multiplier' => 0.7, 'enabled' => true],
        ['provider' => 'google', 'model_key' => 'gemini-pro', 'display_name' => 'Gemini Pro', 'context_window' => 1000000, 'multiplier' => 0.1, 'enabled' => true],
    ],
];
