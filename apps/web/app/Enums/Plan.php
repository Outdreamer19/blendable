<?php

namespace App\Enums;

enum Plan: string
{
    case PRO = 'pro';
    case BUSINESS = 'business';

    public function label(): string
    {
        return match ($this) {
            self::PRO => 'Pro',
            self::BUSINESS => 'Business',
        };
    }

    public function priceUsd(): float
    {
        return match ($this) {
            self::PRO => 19.99,
            self::BUSINESS => 79.00,
        };
    }

    public function monthlyTokens(): int
    {
        return match ($this) {
            self::PRO => 500000,
            self::BUSINESS => 2000000,
        };
    }

    public function monthlyChats(): ?int
    {
        return match ($this) {
            self::PRO => null, // Unlimited
            self::BUSINESS => null, // Unlimited
        };
    }

    public function allowedModels(): array
    {
        return match ($this) {
            self::PRO => ['gpt-4o', 'claude-3.5-sonnet', 'gemini-1.5-pro', 'gemini-flash', 'claude-3-haiku', 'gpt-4o-mini'],
            self::BUSINESS => ['*'], // All models
        };
    }

    public function features(): array
    {
        return match ($this) {
            self::PRO => ['file_uploads', 'priority_queue', 'saved_prompts'],
            self::BUSINESS => array_merge(
                self::PRO->features(),
                ['team_workspace', 'shared_memory', 'analytics', 'roles']
            ),
        };
    }

    public function seatsIncluded(): ?int
    {
        return match ($this) {
            self::PRO => 1,
            self::BUSINESS => 5,
        };
    }
}
