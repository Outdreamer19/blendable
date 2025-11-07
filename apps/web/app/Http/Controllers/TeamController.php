<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teams = $user->teams()->with('users')->get();

        return Inertia::render('Teams/Index', [
            'teams' => $teams,
        ]);
    }

    public function create()
    {
        return Inertia::render('Teams/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $baseSlug = Str::slug($request->name);
        $slug = $this->generateUniqueSlug(Team::class, $baseSlug);

        $team = Team::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'is_active' => true,
        ]);

        // Add the creator as an admin
        $team->users()->attach($user->id, [
            'role' => 'admin',
            'permissions' => json_encode(['*']),
            'joined_at' => now(),
        ]);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        $team->load(['users', 'workspaces']);

        return Inertia::render('Teams/Show', [
            'team' => $team,
        ]);
    }

    public function edit(Team $team)
    {
        $this->authorize('update', $team);

        return Inertia::render('Teams/Edit', [
            'team' => $team,
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $baseSlug = Str::slug($request->name);
        $slug = $this->generateUniqueSlug(Team::class, $baseSlug, $team->id);

        $team->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
        ]);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }

    public function invite(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,member,viewer',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        if ($team->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this team.');
        }

        $team->users()->attach($user->id, [
            'role' => $request->role,
            'permissions' => $this->getDefaultPermissions($request->role),
            'joined_at' => now(),
        ]);

        return back()->with('success', 'User invited successfully.');
    }

    public function removeMember(Team $team, User $user)
    {
        $this->authorize('update', $team);

        $team->users()->detach($user->id);

        return back()->with('success', 'Member removed successfully.');
    }

    protected function getDefaultPermissions(string $role): array
    {
        $permissions = [
            'admin' => ['*'],
            'member' => ['teams.view', 'workspaces.create', 'workspaces.update', 'chats.create', 'chats.update'],
            'viewer' => ['teams.view', 'workspaces.view', 'chats.view'],
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
