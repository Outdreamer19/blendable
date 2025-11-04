<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for Stripe integration.
    | You can find your API keys in the Stripe Dashboard.
    |
    */

    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    |
    | Define your subscription plans here. Each plan should have a unique
    | identifier and corresponding Stripe price ID.
    |
    */

    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'price_id' => env('STRIPE_STARTER_PRICE_ID'),
            'price' => 19.00,
            'words_limit' => 50000,
            'api_calls_limit' => 1000,
            'workspaces_limit' => 1,
            'features' => [
                'All AI models',
                'Basic tools',
                'Email support',
            ],
        ],
        'pro' => [
            'name' => 'Pro',
            'price_id' => env('STRIPE_PRO_PRICE_ID'),
            'price' => 49.00,
            'words_limit' => 200000,
            'api_calls_limit' => 5000,
            'workspaces_limit' => 5,
            'features' => [
                'All AI models',
                'All tools',
                'Team collaboration',
                'Priority support',
            ],
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price_id' => env('STRIPE_ENTERPRISE_PRICE_ID'),
            'price' => 199.00,
            'words_limit' => 1000000,
            'api_calls_limit' => 25000,
            'workspaces_limit' => -1, // Unlimited
            'features' => [
                'All AI models',
                'All tools + custom',
                'Unlimited workspaces',
                'Advanced RBAC',
                'Dedicated support',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Usage Pricing
    |--------------------------------------------------------------------------
    |
    | Define the cost per word for different models and providers.
    | These rates are used to calculate usage costs.
    |
    */

    'usage_rates' => [
        'openai' => [
            'gpt-4o' => 0.00003, // $0.03 per 1K words
            'gpt-4' => 0.00003,
            'gpt-3.5-turbo' => 0.000002, // $0.002 per 1K words
        ],
        'anthropic' => [
            'claude-3-opus-20240229' => 0.000075, // $0.075 per 1K words
            'claude-3-sonnet-20240229' => 0.000015, // $0.015 per 1K words
            'claude-3-haiku-20240307' => 0.0000025, // $0.0025 per 1K words
        ],
        'google' => [
            'gemini-pro' => 0.00001, // $0.01 per 1K words
            'gemini-pro-vision' => 0.00001,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Events
    |--------------------------------------------------------------------------
    |
    | Define which Stripe webhook events to handle and their corresponding
    | actions.
    |
    */

    'webhook_events' => [
        'customer.subscription.created' => 'handleSubscriptionCreated',
        'customer.subscription.updated' => 'handleSubscriptionUpdated',
        'customer.subscription.deleted' => 'handleSubscriptionDeleted',
        'invoice.payment_succeeded' => 'handlePaymentSucceeded',
        'invoice.payment_failed' => 'handlePaymentFailed',
        'customer.subscription.trial_will_end' => 'handleTrialWillEnd',
    ],

    /*
    |--------------------------------------------------------------------------
    | Trial Settings
    |--------------------------------------------------------------------------
    |
    | Configure trial period settings for new subscriptions.
    |
    */

    'trial_days' => 14,

    /*
    |--------------------------------------------------------------------------
    | Billing Portal
    |--------------------------------------------------------------------------
    |
    | Configure the Stripe Customer Portal settings.
    |
    */

    'portal' => [
        'return_url' => env('APP_URL').'/billing',
        'features' => [
            'payment_method_update' => true,
            'subscription_update' => true,
            'subscription_cancel' => true,
            'invoice_history' => true,
        ],
    ],
];
