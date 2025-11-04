<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class WorkspaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();

        if ($teams->isEmpty()) {
            return;
        }

        // Create workspaces for each team
        foreach ($teams as $team) {
            // Create a main workspace for each team
            $workspace = Workspace::firstOrCreate(
                ['slug' => $team->slug.'-workspace'],
                [
                    'team_id' => $team->id,
                    'name' => $team->name.' Workspace',
                    'description' => 'Main workspace for '.$team->name,
                    'is_active' => true,
                ]
            );

            // Add team members to the workspace
            $teamUsers = $team->users;
            foreach ($teamUsers as $user) {
                $workspace->users()->syncWithoutDetaching([$user->id => [
                    'role' => $user->pivot->role,
                    'permissions' => $user->pivot->permissions,
                    'joined_at' => now(),
                ]]);
            }
        }
    }
}
