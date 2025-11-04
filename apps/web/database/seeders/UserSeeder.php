<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo user
        User::firstOrCreate(
            ['email' => 'demo@omni-ai.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'settings' => json_encode([
                    'theme' => 'light',
                    'language' => 'en',
                    'notifications' => true,
                ]),
            ]
        );

        // Create additional test users
        User::factory(10)->create();
    }
}
