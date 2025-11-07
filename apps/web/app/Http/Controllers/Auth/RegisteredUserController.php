<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        $plan = $request->query('plan');

        return Inertia::render('Auth/Register', [
            'plan' => $plan && in_array($plan, ['pro', 'business']) ? $plan : null,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Auto-create a personal team for the user
        $baseSlug = Str::slug($user->name.'s team');
        $teamSlug = $this->generateUniqueSlug(Team::class, $baseSlug);

        $personalTeam = Team::create([
            'name' => $user->name."'s Team",
            'slug' => $teamSlug,
            'description' => 'Personal team for '.$user->name,
            'is_active' => true,
        ]);

        // Add the user as an admin of their personal team
        $personalTeam->users()->attach($user->id, [
            'role' => 'admin',
            'permissions' => json_encode(['*']),
            'joined_at' => now(),
        ]);

        // Auto-create a default workspace in the personal team
        $baseWorkspaceSlug = Str::slug($user->name.'s workspace');
        $workspaceSlug = $this->generateUniqueSlug(Workspace::class, $baseWorkspaceSlug);

        $defaultWorkspace = Workspace::create([
            'team_id' => $personalTeam->id,
            'name' => 'My Workspace',
            'slug' => $workspaceSlug,
            'description' => 'Default workspace for '.$user->name,
            'is_active' => true,
        ]);

        // Add the user to the workspace
        $defaultWorkspace->users()->attach($user->id, [
            'role' => 'admin',
            'permissions' => json_encode(['*']),
            'joined_at' => now(),
        ]);

        // Set the default workspace as current for the user
        $user->update(['current_workspace_id' => $defaultWorkspace->id]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to onboarding/subscription page (no AppLayout)
        return redirect(route('billing.index', absolute: false))
            ->with('success', 'Welcome! Choose a plan to get started.');
    }

    /**
     * Generate a unique slug for a model by appending a number if needed.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     */
    protected function generateUniqueSlug(string $modelClass, string $baseSlug): string
    {
        $slug = $baseSlug;
        $counter = 1;

        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
