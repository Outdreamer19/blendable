<?php

namespace App\Enums;

enum Plan: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case BUSINESS = 'business';

    public function label(): string
    {
        return match ($this) {
            self::FREE => 'Free',
            self::PRO => 'Pro',
            self::BUSINESS => 'Business',
        };
    }

    public function priceGbp(): int
    {
        return match ($this) {
            self::FREE => 0,
            self::PRO => 19,
            self::BUSINESS => 79,
        };
    }

    public function monthlyTokens(): int
    {
        return match ($this) {
            self::FREE => 25000,
            self::PRO => 500000,
            self::BUSINESS => 2000000,
        };
    }

    public function monthlyChats(): ?int
    {
        return match ($this) {
            self::FREE => 50,
            self::PRO => null, // Unlimited
            self::BUSINESS => null, // Unlimited
        };
    }

    public function allowedModels(): array
    {
        return match ($this) {
            self::FREE => ['gpt-4o-mini', 'claude-3-haiku', 'gemini-flash'],
            self::PRO => ['gpt-4o', 'claude-3.5-sonnet', 'gemini-1.5-pro', 'gemini-flash', 'claude-3-haiku', 'gpt-4o-mini'],
            self::BUSINESS => ['*'], // All models
        };
    }

    public function features(): array
    {
        return match ($this) {
            self::FREE => ['basic_chat'],
            self::PRO => ['file_uploads', 'priority_queue', 'saved_prompts'],
            self::BUSINESS => ['team_workspace', 'shared_memory', 'analytics', 'roles'],
        };
    }

    public function seatsIncluded(): ?int
    {
        return match ($this) {
            self::FREE => 1,
            self::PRO => 1,
            self::BUSINESS => 5,
        };
    }
}
