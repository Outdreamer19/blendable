<?php

return [
    'plans' => [
        'pro' => [
            'price_usd' => 19.99,
            'monthly_tokens' => 500000,
            'models' => ['gpt-4o', 'claude-3.5-sonnet', 'gemini-1.5-pro', 'gemini-flash', 'claude-3-haiku', 'gpt-4o-mini'],
            'features' => ['file_uploads', 'priority_queue', 'saved_prompts'],
        ],
        'business' => [
            'price_usd' => 79.00,
            'monthly_tokens' => 2000000,
            'seats_included' => 5,
            'models' => ['*'],
            'features' => ['team_workspace', 'shared_memory', 'analytics', 'roles'],
        ],
    ],

    'overage' => [
        'price_usd' => 5,
        'tokens' => 100000,
    ],

    'stripe' => [
        'prices' => [
            'pro' => env('STRIPE_PRO_PRICE_ID'),
            'business' => env('STRIPE_BUSINESS_PRICE_ID'),
        ],
    ],
];
