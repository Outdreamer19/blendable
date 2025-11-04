<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Inertia\Inertia;

class PublicPersonaController extends Controller
{
    /**
     * Display a listing of public personas.
     */
    public function index()
    {
        // Get all public personas, but group by name to avoid duplicates
        $personas = Persona::where('is_public', true)
            ->where('is_active', true)
            ->with(['knowledge', 'actions'])
            ->withCount(['knowledge', 'actions'])
            ->orderBy('name')
            ->get();

        // Group by name and take the first one of each (or the one with most knowledge/actions)
        $uniquePersonas = $personas->groupBy('name')->map(function ($group) {
            // Return the persona with the most knowledge items, or first if tied
            return $group->sortByDesc('knowledge_count')->first();
        })->values();

        return Inertia::render('Personas/Public', [
            'personas' => $uniquePersonas,
        ]);
    }

    /**
     * Display the specified persona.
     */
    public function show(Persona $persona)
    {
        if (! $persona->is_public || ! $persona->is_active) {
            abort(404);
        }

        $persona->load(['knowledge', 'actions']);

        return Inertia::render('Personas/PublicShow', [
            'persona' => $persona,
        ]);
    }
}
