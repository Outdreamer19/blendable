<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Knowledge extends Model
{
    protected $fillable = [
        'workspace_id',
        'name',
        'description',
        'content',
        'type',
        'source',
        'author',
        'category',
        'importance_score',
        'tags',
        'metadata',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(Persona::class, 'persona_knowledge')
            ->withPivot(['weight', 'created_at'])
            ->withTimestamps();
    }

    public function getContentSummaryAttribute(): string
    {
        return substr(strip_tags($this->content), 0, 200).'...';
    }

    public function getTypeIconAttribute(): string
    {
        $icons = [
            'document' => '📄',
            'url' => '🔗',
            'text' => '📝',
            'code' => '💻',
            'data' => '📊',
            'image' => '🖼️',
        ];

        return $icons[$this->type] ?? '📄';
    }
}
