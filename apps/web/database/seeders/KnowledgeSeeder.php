<?php

namespace Database\Seeders;

use App\Models\Knowledge;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class KnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workspaces = Workspace::all();

        if ($workspaces->isEmpty()) {
            return;
        }

        $knowledgeItems = [
            [
                'name' => 'Laravel Best Practices',
                'description' => 'Best practices for Laravel development',
                'content' => 'Laravel is a PHP web application framework with expressive, elegant syntax. Key best practices include: using Eloquent ORM for database operations, following MVC architecture, implementing proper validation, using middleware for authentication, and following PSR standards.',
                'type' => 'document',
                'source' => 'Laravel Documentation',
                'is_active' => true,
            ],
            [
                'name' => 'Vue.js Component Patterns',
                'description' => 'Common patterns for Vue.js component development',
                'content' => 'Vue.js components should follow these patterns: use single-file components, implement proper prop validation, use computed properties for derived data, implement proper event handling, and follow the composition API for complex components.',
                'type' => 'document',
                'source' => 'Vue.js Guide',
                'is_active' => true,
            ],
            [
                'name' => 'API Design Guidelines',
                'description' => 'Guidelines for designing RESTful APIs',
                'content' => 'RESTful API design guidelines: use HTTP methods appropriately (GET, POST, PUT, DELETE), implement proper status codes, use consistent naming conventions, implement pagination for large datasets, include proper error handling, and provide comprehensive documentation.',
                'type' => 'document',
                'source' => 'API Design Best Practices',
                'is_active' => true,
            ],
            [
                'name' => 'Database Optimization Tips',
                'description' => 'Tips for optimizing database performance',
                'content' => 'Database optimization tips: use proper indexing, avoid N+1 queries, implement query caching, use database connection pooling, optimize table structure, implement proper foreign key constraints, and use database-specific optimization features.',
                'type' => 'document',
                'source' => 'Database Performance Guide',
                'is_active' => true,
            ],
            [
                'name' => 'Security Best Practices',
                'description' => 'Security best practices for web applications',
                'content' => 'Security best practices: implement proper authentication and authorization, use HTTPS for all communications, validate and sanitize all inputs, implement CSRF protection, use secure session management, keep dependencies updated, and implement proper error handling without exposing sensitive information.',
                'type' => 'document',
                'source' => 'Web Security Guide',
                'is_active' => true,
            ],
        ];

        foreach ($workspaces as $workspace) {
            foreach ($knowledgeItems as $knowledgeData) {
                Knowledge::create(array_merge($knowledgeData, [
                    'workspace_id' => $workspace->id,
                ]));
            }
        }
    }
}
