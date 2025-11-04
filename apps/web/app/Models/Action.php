<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Action extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(Persona::class, 'action_persona')
            ->withPivot(['enabled', 'settings'])
            ->withTimestamps();
    }

    public function getIconAttribute(): string
    {
        $icons = [
            'web_search' => '🔍',
            'file_upload' => '📁',
            'image_generation' => '🎨',
            'code_execution' => '⚡',
            'data_analysis' => '📊',
            'email' => '📧',
            'calendar' => '📅',
            'api_call' => '🔗',
        ];

        return $icons[$this->type] ?? '⚙️';
    }

    public function isEnabledForPersona(Persona $persona): bool
    {
        $pivot = $this->personas()->where('persona_id', $persona->id)->first();

        return $pivot ? $pivot->pivot->enabled : false;
    }

    public function getSettingsForPersona(Persona $persona): array
    {
        $pivot = $this->personas()->where('persona_id', $persona->id)->first();

        return $pivot ? ($pivot->pivot->settings ?? []) : [];
    }
}
