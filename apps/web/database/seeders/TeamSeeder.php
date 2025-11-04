<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        // Create a demo team
        $demoTeam = Team::firstOrCreate(
            ['slug' => 'demo-team'],
            [
                'name' => 'Demo Team',
                'description' => 'A demo team for testing purposes',
                'is_active' => true,
            ]
        );

        // Add the first user as admin
        $demoTeam->users()->syncWithoutDetaching([$users->first()->id => [
            'role' => 'admin',
            'permissions' => json_encode(['*']),
            'joined_at' => now(),
        ]]);

        // Create additional teams
        $additionalTeams = [
            ['name' => 'Development Team', 'slug' => 'dev-team', 'description' => 'Development team'],
            ['name' => 'Marketing Team', 'slug' => 'marketing-team', 'description' => 'Marketing team'],
            ['name' => 'Support Team', 'slug' => 'support-team', 'description' => 'Customer support team'],
        ];

        foreach ($additionalTeams as $teamData) {
            $team = Team::firstOrCreate(
                ['slug' => $teamData['slug']],
                [
                    'name' => $teamData['name'],
                    'description' => $teamData['description'],
                    'is_active' => true,
                ]
            );

            // Add random users to each team
            $teamUsers = $users->random(rand(1, min(3, $users->count())));
            foreach ($teamUsers as $user) {
                $team->users()->syncWithoutDetaching([$user->id => [
                    'role' => rand(0, 1) ? 'admin' : 'member',
                    'permissions' => json_encode(['read', 'write']),
                    'joined_at' => now(),
                ]]);
            }
        }
    }
}
