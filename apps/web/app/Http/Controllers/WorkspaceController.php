<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WorkspaceController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $workspaces = $user->workspaces()->with('team')->get();

        return Inertia::render('Workspaces/Index', [
            'workspaces' => $workspaces,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $teams = $user->teams()->get();

        return Inertia::render('Workspaces/Create', [
            'teams' => $teams,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'required|exists:teams,id',
        ]);

        $team = \App\Models\Team::findOrFail($request->team_id);
        $this->authorize('update', $team);

        $baseSlug = Str::slug($request->name);
        $slug = $this->generateUniqueSlug(Workspace::class, $baseSlug);

        $workspace = Workspace::create([
            'team_id' => $request->team_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'is_active' => true,
        ]);

        // Add the creator as an admin
        $workspace->users()->attach($user->id, [
            'role' => 'admin',
            'permissions' => json_encode(['*']),
            'joined_at' => now(),
        ]);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Workspace created successfully.');
    }

    public function show(Workspace $workspace)
    {
        $this->authorize('view', $workspace);

        $workspace->load(['users', 'team', 'chats', 'personas']);

        return Inertia::render('Workspaces/Show', [
            'workspace' => $workspace,
        ]);
    }

    public function edit(Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $user = Auth::user();
        $teams = $user->teams()->get();

        return Inertia::render('Workspaces/Edit', [
            'workspace' => $workspace,
            'teams' => $teams,
        ]);
    }

    public function update(Request $request, Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'required|exists:teams,id',
        ]);

        $baseSlug = Str::slug($request->name);
        $slug = $this->generateUniqueSlug(Workspace::class, $baseSlug, $workspace->id);

        $workspace->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'team_id' => $request->team_id,
        ]);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Workspace updated successfully.');
    }

    public function destroy(Workspace $workspace)
    {
        $this->authorize('delete', $workspace);

        $workspace->delete();

        return redirect()->route('workspaces.index')
            ->with('success', 'Workspace deleted successfully.');
    }

    public function invite(Request $request, Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,member,viewer',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        if ($workspace->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this workspace.');
        }

        $workspace->users()->attach($user->id, [
            'role' => $request->role,
            'permissions' => $this->getDefaultPermissions($request->role),
            'joined_at' => now(),
        ]);

        return back()->with('success', 'User invited successfully.');
    }

    public function removeMember(Workspace $workspace, User $user)
    {
        $this->authorize('update', $workspace);

        $workspace->users()->detach($user->id);

        return back()->with('success', 'Member removed successfully.');
    }

    protected function getDefaultPermissions(string $role): array
    {
        $permissions = [
            'admin' => ['*'],
            'member' => ['workspaces.view', 'chats.create', 'chats.update', 'personas.create', 'personas.update'],
            'viewer' => ['workspaces.view', 'chats.view', 'personas.view'],
        ];

        return $permissions[$role] ?? [];
    }

    /**
     * Generate a unique slug for a model by appending a number if needed.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     * @param  int|null  $excludeId  Exclude this ID from the uniqueness check (for updates)
     */
    protected function generateUniqueSlug(string $modelClass, string $baseSlug, ?int $excludeId = null): string
    {
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($modelClass, $slug, $excludeId)) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug exists for a model.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     */
    protected function slugExists(string $modelClass, string $slug, ?int $excludeId = null): bool
    {
        $query = $modelClass::where('slug', $slug);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
