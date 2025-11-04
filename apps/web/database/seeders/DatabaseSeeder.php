<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ModelConfigSeeder::class,
            ActionSeeder::class,
            UserSeeder::class,
            TeamSeeder::class,
            WorkspaceSeeder::class,
            PersonaSeeder::class,
            RealPersonPersonaSeeder::class,
            KnowledgeSeeder::class,
            PromptSeeder::class,
        ]);
    }
}
