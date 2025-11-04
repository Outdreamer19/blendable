<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workspace_id',
        'folder_id',
        'name',
        'description',
        'content',
        'tags',
        'is_public',
        'is_favorite',
        'usage_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_public' => 'boolean',
        'is_favorite' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(PromptFolder::class);
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function getContentPreviewAttribute(): string
    {
        return substr(strip_tags($this->content), 0, 150).'...';
    }

    public function getTagsStringAttribute(): string
    {
        return is_array($this->tags) ? implode(', ', $this->tags) : '';
    }
}
