<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'user_id',
        'role',
        'model_key',
        'tokens_in',
        'tokens_out',
        'words_out',
        'tool_calls_json',
        'meta_json',
        'content',
    ];

    protected $casts = [
        'tool_calls_json' => 'array',
        'meta_json' => 'array',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getToolCallsAttribute(): array
    {
        return $this->tool_calls_json ?? [];
    }

    public function setToolCallsAttribute(array $value): void
    {
        $this->tool_calls_json = $value;
    }

    public function getMetaAttribute(): array
    {
        return $this->meta_json ?? [];
    }

    public function setMetaAttribute(array $value): void
    {
        $this->meta_json = $value;
    }
}
