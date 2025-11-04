<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Knowledge;
use App\Models\Persona;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PersonaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $personas = Persona::where('workspace_id', $currentWorkspace->id)
            ->with(['knowledge', 'actions'])
            ->withCount(['knowledge', 'actions'])
            ->orderBy('name')
            ->get();

        return Inertia::render('Personas/Index', [
            'personas' => $personas,
            'user' => $user,
            'workspaces' => $user->workspaces()->with('team')->get(),
            'currentWorkspace' => $currentWorkspace,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $availableKnowledge = Knowledge::where('workspace_id', $currentWorkspace->id)
            ->where('is_active', true)
            ->get();

        $availableActions = Action::where('is_active', true)->get();

        return Inertia::render('Personas/Create', [
            'availableKnowledge' => $availableKnowledge,
            'availableActions' => $availableActions,
            'currentWorkspace' => $currentWorkspace,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'nullable|string',
            'avatar_url' => 'nullable|url',
            'is_public' => 'boolean',
            'knowledge_ids' => 'array',
            'action_ids' => 'array',
        ]);

        $persona = Persona::create([
            'workspace_id' => $currentWorkspace->id,
            'name' => $request->name,
            'description' => $request->description,
            'system_prompt' => $request->system_prompt,
            'avatar_url' => $request->avatar_url,
            'is_public' => $request->boolean('is_public'),
            'is_active' => true,
        ]);

        // Attach knowledge
        if ($request->knowledge_ids) {
            foreach ($request->knowledge_ids as $knowledgeId) {
                $knowledge = Knowledge::find($knowledgeId);
                if ($knowledge) {
                    $persona->attachKnowledge($knowledge);
                }
            }
        }

        // Attach actions
        if ($request->action_ids) {
            foreach ($request->action_ids as $actionId) {
                $action = Action::find($actionId);
                if ($action) {
                    $persona->enableAction($action);
                }
            }
        }

        return redirect()->route('personas.show', $persona)
            ->with('success', 'Persona created successfully.');
    }

    public function show(Persona $persona)
    {
        $this->authorize('view', $persona);

        $persona->load(['knowledge', 'actions', 'chats']);

        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        return Inertia::render('Personas/Show', [
            'persona' => $persona,
            'user' => $user,
            'workspaces' => $user->workspaces()->with('team')->get(),
            'currentWorkspace' => $currentWorkspace,
        ]);
    }

    public function edit(Persona $persona)
    {
        $this->authorize('update', $persona);

        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $availableKnowledge = Knowledge::where('workspace_id', $currentWorkspace->id)
            ->where('is_active', true)
            ->get();

        $availableActions = Action::where('is_active', true)->get();

        $persona->load(['knowledge', 'actions']);

        return Inertia::render('Personas/Edit', [
            'persona' => $persona,
            'availableKnowledge' => $availableKnowledge,
            'availableActions' => $availableActions,
            'currentWorkspace' => $currentWorkspace,
        ]);
    }

    public function update(Request $request, Persona $persona)
    {
        $this->authorize('update', $persona);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'nullable|string',
            'avatar_url' => 'nullable|url',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
            'knowledge_ids' => 'array',
            'action_ids' => 'array',
        ]);

        $persona->update([
            'name' => $request->name,
            'description' => $request->description,
            'system_prompt' => $request->system_prompt,
            'avatar_url' => $request->avatar_url,
            'is_public' => $request->boolean('is_public'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Sync knowledge
        if ($request->has('knowledge_ids')) {
            $persona->knowledge()->sync($request->knowledge_ids);
        }

        // Sync actions
        if ($request->has('action_ids')) {
            $persona->actions()->sync($request->action_ids);
        }

        return redirect()->route('personas.show', $persona)
            ->with('success', 'Persona updated successfully.');
    }

    public function destroy(Persona $persona)
    {
        $this->authorize('delete', $persona);

        $persona->delete();

        return redirect()->route('personas.index')
            ->with('success', 'Persona deleted successfully.');
    }

    public function attachKnowledge(Request $request, Persona $persona)
    {
        $this->authorize('update', $persona);

        $request->validate([
            'knowledge_id' => 'required|exists:knowledge,id',
            'weight' => 'numeric|min:0|max:1',
        ]);

        $knowledge = Knowledge::findOrFail($request->knowledge_id);
        $persona->attachKnowledge($knowledge, $request->weight ?? 1.0);

        return response()->json(['success' => true]);
    }

    public function detachKnowledge(Persona $persona, Knowledge $knowledge)
    {
        $this->authorize('update', $persona);

        $persona->detachKnowledge($knowledge);

        return response()->json(['success' => true]);
    }
}
