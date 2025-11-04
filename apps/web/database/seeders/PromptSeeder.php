<?php

namespace Database\Seeders;

use App\Models\Prompt;
use App\Models\PromptFolder;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class PromptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workspaces = Workspace::all();
        $users = User::all();

        if ($workspaces->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach ($workspaces as $workspace) {
            $workspaceUsers = $workspace->users;
            if ($workspaceUsers->isEmpty()) {
                continue;
            }

            $user = $workspaceUsers->first();

            // Create prompt folders
            $folders = [
                [
                    'name' => 'Development',
                    'description' => 'Prompts for software development tasks',
                    'color' => '#3B82F6',
                    'is_public' => false,
                ],
                [
                    'name' => 'Writing',
                    'description' => 'Prompts for writing and content creation',
                    'color' => '#10B981',
                    'is_public' => false,
                ],
                [
                    'name' => 'Analysis',
                    'description' => 'Prompts for data analysis and research',
                    'color' => '#F59E0B',
                    'is_public' => false,
                ],
            ];

            $createdFolders = [];
            foreach ($folders as $folderData) {
                $folder = PromptFolder::create(array_merge($folderData, [
                    'user_id' => $user->id,
                    'workspace_id' => $workspace->id,
                ]));
                $createdFolders[] = $folder;
            }

            // Create prompts
            $prompts = [
                [
                    'name' => 'Code Review',
                    'description' => 'Review code for bugs, performance, and best practices',
                    'content' => 'Please review the following code for:\n1. Bugs and potential issues\n2. Performance optimizations\n3. Code quality and best practices\n4. Security vulnerabilities\n5. Suggestions for improvement\n\nCode:\n{code}',
                    'tags' => ['development', 'code-review', 'quality'],
                    'folder_id' => $createdFolders[0]->id,
                    'is_public' => false,
                    'is_favorite' => true,
                ],
                [
                    'name' => 'Bug Fix',
                    'description' => 'Help identify and fix bugs in code',
                    'content' => 'I have a bug in my code. Please help me:\n1. Identify the root cause\n2. Explain why it\'s happening\n3. Provide a fix\n4. Suggest how to prevent similar issues\n\nError message: {error}\nCode: {code}',
                    'tags' => ['development', 'debugging', 'bug-fix'],
                    'folder_id' => $createdFolders[0]->id,
                    'is_public' => false,
                    'is_favorite' => false,
                ],
                [
                    'name' => 'Article Outline',
                    'description' => 'Create a structured outline for articles',
                    'content' => 'Create a detailed outline for an article about "{topic}". Include:\n1. Introduction with hook\n2. Main sections with key points\n3. Supporting evidence and examples\n4. Conclusion with call-to-action\n5. Suggested subheadings\n\nTarget audience: {audience}\nWord count: {word_count}',
                    'tags' => ['writing', 'outline', 'structure'],
                    'folder_id' => $createdFolders[1]->id,
                    'is_public' => false,
                    'is_favorite' => true,
                ],
                [
                    'name' => 'Content Summary',
                    'description' => 'Summarize long content into key points',
                    'content' => 'Please summarize the following content into key points:\n1. Main arguments or themes\n2. Supporting evidence\n3. Key takeaways\n4. Action items (if any)\n\nContent: {content}',
                    'tags' => ['writing', 'summary', 'analysis'],
                    'folder_id' => $createdFolders[1]->id,
                    'is_public' => false,
                    'is_favorite' => false,
                ],
                [
                    'name' => 'Data Analysis',
                    'description' => 'Analyze data and provide insights',
                    'content' => 'Please analyze the following data and provide:\n1. Key trends and patterns\n2. Statistical insights\n3. Potential correlations\n4. Recommendations based on findings\n5. Visualizations that would be helpful\n\nData: {data}\nContext: {context}',
                    'tags' => ['analysis', 'data', 'insights'],
                    'folder_id' => $createdFolders[2]->id,
                    'is_public' => false,
                    'is_favorite' => true,
                ],
                [
                    'name' => 'Research Questions',
                    'description' => 'Generate research questions for a topic',
                    'content' => 'Generate 10 research questions for the topic "{topic}". Include:\n1. Open-ended questions\n2. Specific, measurable questions\n3. Questions that explore different angles\n4. Questions that could lead to actionable insights\n\nResearch goal: {goal}',
                    'tags' => ['research', 'questions', 'methodology'],
                    'folder_id' => $createdFolders[2]->id,
                    'is_public' => false,
                    'is_favorite' => false,
                ],
            ];

            foreach ($prompts as $promptData) {
                Prompt::create(array_merge($promptData, [
                    'user_id' => $user->id,
                    'workspace_id' => $workspace->id,
                ]));
            }
        }
    }
}
