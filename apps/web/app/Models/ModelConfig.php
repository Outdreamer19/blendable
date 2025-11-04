<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'model_key',
        'display_name',
        'context_window',
        'multiplier',
        'enabled',
        'type',
        'description',
    ];

    protected $casts = [
        'context_window' => 'integer',
        'multiplier' => 'float',
        'enabled' => 'boolean',
    ];

    public function calculateTokenCost(string $text): int
    {
        // Simple token estimation (roughly 4 characters per token)
        return (int) ceil(strlen($text) / 4);
    }
}
