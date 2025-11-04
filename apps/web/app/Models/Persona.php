<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'name',
        'description',
        'system_prompt',
        'avatar_url',
        'settings',
        'is_public',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    public function knowledge(): BelongsToMany
    {
        return $this->belongsToMany(Knowledge::class, 'persona_knowledge')
            ->withPivot(['weight', 'created_at'])
            ->withTimestamps();
    }

    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'action_persona')
            ->withPivot(['enabled', 'settings'])
            ->withTimestamps();
    }

    public function getSystemPromptAttribute($value): string
    {
        return $value ?: $this->getDefaultSystemPrompt();
    }

    protected function getDefaultSystemPrompt(): string
    {
        return "You are {$this->name}. {$this->description}";
    }

    public function attachKnowledge(Knowledge $knowledge, float $weight = 1.0): void
    {
        $this->knowledge()->syncWithoutDetaching([
            $knowledge->id => ['weight' => $weight],
        ]);
    }

    public function detachKnowledge(Knowledge $knowledge): void
    {
        $this->knowledge()->detach($knowledge->id);
    }

    public function enableAction(Action $action, array $settings = []): void
    {
        $this->actions()->syncWithoutDetaching([
            $action->id => [
                'enabled' => true,
                'settings' => json_encode($settings),
            ],
        ]);
    }

    public function disableAction(Action $action): void
    {
        $this->actions()->updateExistingPivot($action->id, ['enabled' => false]);
    }
}
