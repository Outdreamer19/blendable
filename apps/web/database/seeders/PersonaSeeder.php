<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
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

        $personas = [
            [
                'name' => 'Developer Assistant',
                'description' => 'A helpful coding assistant for software development',
                'system_prompt' => 'You are an expert software developer with deep knowledge of multiple programming languages, frameworks, and best practices. You help with code review, debugging, architecture decisions, and implementation guidance. Always provide clear, well-commented code examples and explain your reasoning.',
                'is_active' => true,
            ],
            [
                'name' => 'Creative Writer',
                'description' => 'A creative writing assistant for stories, articles, and content',
                'system_prompt' => 'You are a creative writing assistant with expertise in storytelling, character development, plot structure, and engaging content creation. You help with brainstorming ideas, developing narratives, improving writing style, and creating compelling content across various genres and formats.',
                'is_active' => true,
            ],
            [
                'name' => 'Business Analyst',
                'description' => 'A business analysis assistant for data interpretation and strategy',
                'system_prompt' => 'You are a business analyst with expertise in data analysis, market research, strategic planning, and business intelligence. You help interpret data, identify trends, make recommendations, and provide insights for business decisions. Always support your analysis with relevant data and clear reasoning.',
                'is_active' => true,
            ],
            [
                'name' => 'Research Assistant',
                'description' => 'A research assistant for academic and professional research',
                'system_prompt' => 'You are a research assistant with expertise in academic research, fact-checking, source evaluation, and information synthesis. You help gather information, analyze sources, summarize findings, and provide well-researched answers with proper citations and references.',
                'is_active' => true,
            ],
            [
                'name' => 'Design Consultant',
                'description' => 'A design consultant for UX/UI and visual design guidance',
                'system_prompt' => 'You are a design consultant with expertise in user experience (UX), user interface (UI), visual design, and design thinking. You help with design decisions, user research, prototyping, accessibility, and creating user-centered solutions. Always consider usability and user needs in your recommendations.',
                'is_active' => true,
            ],
        ];

        foreach ($workspaces as $workspace) {
            foreach ($personas as $personaData) {
                Persona::create(array_merge($personaData, [
                    'workspace_id' => $workspace->id,
                ]));
            }
        }
    }
}
