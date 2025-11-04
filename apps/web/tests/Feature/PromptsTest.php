<?php

namespace Tests\Feature;

use App\Models\Prompt;
use App\Models\PromptFolder;
use App\Models\Team;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromptsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Team $team;

    protected Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->team = Team::factory()->create();
        $this->workspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);

        // Attach user to team and workspace
        $this->user->teams()->attach($this->team, ['role' => 'admin']);
        $this->user->workspaces()->attach($this->workspace, ['role' => 'admin']);
        $this->user->update(['current_workspace_id' => $this->workspace->id]);
    }

    public function test_prompts_index_displays_all_prompts()
    {
        // Create test prompts
        $prompts = Prompt::factory()->count(5)->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Index')
            ->has('prompts')
            ->where('prompts', function ($prompts) {
                return count($prompts) === 5;
            })
        );
    }

    public function test_prompts_index_shows_prompts_with_folders()
    {
        // Create folder and prompts
        $folder = PromptFolder::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $prompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'prompt_folder_id' => $folder->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Index')
            ->has('prompts')
            ->where('prompts.0', function ($prompt) {
                return isset($prompt['folder']);
            })
        );
    }

    public function test_prompts_create_page_loads()
    {
        $response = $this->actingAs($this->user)
            ->get(route('prompts.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Create')
            ->has('user')
            ->has('workspaces')
            ->has('currentWorkspace')
            ->has('folders')
        );
    }

    public function test_prompts_can_be_created()
    {
        $promptData = [
            'title' => 'Test Prompt',
            'content' => 'This is a test prompt content.',
            'description' => 'A test prompt for testing',
            'tags' => ['test', 'example'],
            'is_public' => false,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('prompts.store'), $promptData);

        $response->assertRedirect(route('prompts.index'));

        $this->assertDatabaseHas('prompts', [
            'title' => 'Test Prompt',
            'workspace_id' => $this->workspace->id,
        ]);
    }

    public function test_prompts_require_authentication()
    {
        $response = $this->get(route('prompts.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_prompts_require_workspace_access()
    {
        // Create another workspace
        $otherWorkspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);

        $prompt = Prompt::factory()->create([
            'workspace_id' => $otherWorkspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.show', $prompt));

        $response->assertStatus(403);
    }

    public function test_prompts_can_be_viewed()
    {
        $prompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.show', $prompt));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Show')
            ->has('prompt')
            ->where('prompt.id', $prompt->id)
        );
    }

    public function test_prompts_can_be_updated()
    {
        $prompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $updateData = [
            'title' => 'Updated Prompt Title',
            'content' => 'Updated prompt content',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('prompts.update', $prompt), $updateData);

        $response->assertRedirect(route('prompts.show', $prompt));

        $this->assertDatabaseHas('prompts', [
            'id' => $prompt->id,
            'title' => 'Updated Prompt Title',
        ]);
    }

    public function test_prompts_can_be_deleted()
    {
        $prompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('prompts.destroy', $prompt));

        $response->assertRedirect(route('prompts.index'));

        $this->assertDatabaseMissing('prompts', [
            'id' => $prompt->id,
        ]);
    }

    public function test_prompts_validation_works()
    {
        $response = $this->actingAs($this->user)
            ->post(route('prompts.store'), []);

        $response->assertSessionHasErrors(['title', 'content']);
    }

    public function test_prompts_can_be_organized_in_folders()
    {
        $folder = PromptFolder::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $prompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'prompt_folder_id' => $folder->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Index')
            ->has('prompts')
            ->where('prompts.0', function ($prompt) use ($folder) {
                return $prompt['folder']['id'] === $folder->id;
            })
        );
    }

    public function test_prompts_can_be_tagged()
    {
        $prompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'tags' => ['ai', 'assistant', 'helpful'],
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.show', $prompt));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Show')
            ->has('prompt')
            ->where('prompt.tags', ['ai', 'assistant', 'helpful'])
        );
    }

    public function test_prompts_can_be_filtered_by_tags()
    {
        Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'tags' => ['ai', 'assistant'],
        ]);

        Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'tags' => ['coding', 'helpful'],
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.index', ['tag' => 'ai']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Index')
            ->has('prompts')
        );
    }

    public function test_prompts_can_be_searched()
    {
        Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'AI Assistant Prompt',
            'content' => 'Helpful AI assistant',
        ]);

        Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Coding Helper',
            'content' => 'Programming assistance',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.index', ['search' => 'AI']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Index')
            ->has('prompts')
        );
    }

    public function test_prompts_can_be_duplicated()
    {
        $originalPrompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Original Prompt',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('prompts.duplicate', $originalPrompt));

        $response->assertRedirect(route('prompts.index'));

        $this->assertDatabaseHas('prompts', [
            'workspace_id' => $this->workspace->id,
            'title' => 'Copy of Original Prompt',
        ]);
    }

    public function test_prompts_can_be_exported()
    {
        Prompt::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function test_prompts_can_be_imported()
    {
        $importData = [
            'prompts' => [
                [
                    'title' => 'Imported Prompt 1',
                    'content' => 'Content 1',
                    'description' => 'Description 1',
                ],
                [
                    'title' => 'Imported Prompt 2',
                    'content' => 'Content 2',
                    'description' => 'Description 2',
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('prompts.import'), $importData);

        $response->assertRedirect(route('prompts.index'));

        $this->assertDatabaseHas('prompts', [
            'title' => 'Imported Prompt 1',
            'workspace_id' => $this->workspace->id,
        ]);

        $this->assertDatabaseHas('prompts', [
            'title' => 'Imported Prompt 2',
            'workspace_id' => $this->workspace->id,
        ]);
    }

    public function test_prompts_can_be_shared()
    {
        $prompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'is_public' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('prompts.share', $prompt));

        $response->assertRedirect();

        $this->assertDatabaseHas('prompts', [
            'id' => $prompt->id,
            'is_public' => true,
        ]);
    }

    public function test_prompts_can_be_unshared()
    {
        $prompt = Prompt::factory()->create([
            'workspace_id' => $this->workspace->id,
            'is_public' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('prompts.unshare', $prompt));

        $response->assertRedirect();

        $this->assertDatabaseHas('prompts', [
            'id' => $prompt->id,
            'is_public' => false,
        ]);
    }
}
