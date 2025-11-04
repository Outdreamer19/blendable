<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'model_used',
        'latency_ms',
        'input_tokens',
        'output_tokens',
        'total_tokens',
        'response_text',
        'metadata',
        'user_id',
        'chat_id',
        'message_id',
    ];

    protected $casts = [
        'metadata' => 'array',
        'latency_ms' => 'integer',
        'input_tokens' => 'integer',
        'output_tokens' => 'integer',
        'total_tokens' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Create an AI log entry with the provided data
     */
    public static function createLog(array $data): self
    {
        return self::create([
            'provider' => $data['provider'],
            'model_used' => $data['model_used'],
            'latency_ms' => $data['latency_ms'],
            'input_tokens' => $data['input_tokens'] ?? null,
            'output_tokens' => $data['output_tokens'] ?? null,
            'total_tokens' => $data['total_tokens'] ?? null,
            'response_text' => $data['response_text'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'chat_id' => $data['chat_id'] ?? null,
            'message_id' => $data['message_id'] ?? null,
        ]);
    }
}
