<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'workspace_id',
        'message_id',
        'model_key',
        'tokens_in',
        'tokens_out',
        'words_debited',
        'multiplier',
        'cost_usd',
        'usage_date',
    ];

    protected $casts = [
        'tokens_in' => 'integer',
        'tokens_out' => 'integer',
        'words_debited' => 'integer',
        'multiplier' => 'float',
        'cost_usd' => 'float',
        'usage_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
