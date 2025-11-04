<?php

namespace Tests\Feature;

use App\Models\Action;
use App\Models\Knowledge;
use App\Models\Persona;
use App\Models\Team;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonasTest extends TestCase
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

    public function test_personas_index_displays_all_personas()
    {
        // Create test personas
        $personas = Persona::factory()->count(5)->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('personas.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Personas/Index')
            ->has('personas')
            ->where('personas', function ($personas) {
                return count($personas) === 5;
            })
        );
    }

    public function test_personas_index_shows_persona_with_knowledge_and_actions()
    {
        // Create persona with knowledge and actions
        $persona = Persona::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $knowledge = Knowledge::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $action = Action::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $persona->knowledge()->attach($knowledge);
        $persona->actions()->attach($action);

        $response = $this->actingAs($this->user)
            ->get(route('personas.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Personas/Index')
            ->has('personas')
            ->where('personas.0', function ($persona) {
                return isset($persona['knowledge_count']) &&
                       isset($persona['actions_count']);
            })
        );
    }

    public function test_personas_create_page_loads()
    {
        $response = $this->actingAs($this->user)
            ->get(route('personas.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Personas/Create')
            ->has('user')
            ->has('workspaces')
            ->has('currentWorkspace')
        );
    }

    public function test_personas_can_be_created()
    {
        $personaData = [
            'name' => 'Test Persona',
            'description' => 'A test persona for testing',
            'system_prompt' => 'You are a helpful assistant.',
            'is_public' => false,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('personas.store'), $personaData);

        $response->assertRedirect(route('personas.index'));

        $this->assertDatabaseHas('personas', [
            'name' => 'Test Persona',
            'workspace_id' => $this->workspace->id,
        ]);
    }

    public function test_personas_require_authentication()
    {
        $response = $this->get(route('personas.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_personas_require_workspace_access()
    {
        // Create another workspace
        $otherWorkspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);

        $persona = Persona::factory()->create([
            'workspace_id' => $otherWorkspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('personas.show', $persona));

        $response->assertStatus(403);
    }

    public function test_personas_can_be_viewed()
    {
        $persona = Persona::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('personas.show', $persona));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Personas/Show')
            ->has('persona')
            ->where('persona.id', $persona->id)
        );
    }

    public function test_personas_can_be_updated()
    {
        $persona = Persona::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $updateData = [
            'name' => 'Updated Persona Name',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('personas.update', $persona), $updateData);

        $response->assertRedirect(route('personas.show', $persona));

        $this->assertDatabaseHas('personas', [
            'id' => $persona->id,
            'name' => 'Updated Persona Name',
        ]);
    }

    public function test_personas_can_be_deleted()
    {
        $persona = Persona::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('personas.destroy', $persona));

        $response->assertRedirect(route('personas.index'));

        $this->assertDatabaseMissing('personas', [
            'id' => $persona->id,
        ]);
    }

    public function test_personas_validation_works()
    {
        $response = $this->actingAs($this->user)
            ->post(route('personas.store'), []);

        $response->assertSessionHasErrors(['name', 'system_prompt']);
    }

    public function test_personas_can_attach_knowledge()
    {
        $persona = Persona::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $knowledge = Knowledge::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('personas.attach-knowledge', $persona), [
                'knowledge_id' => $knowledge->id,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('persona_knowledge', [
            'persona_id' => $persona->id,
            'knowledge_id' => $knowledge->id,
        ]);
    }

    public function test_personas_can_attach_actions()
    {
        $persona = Persona::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $action = Action::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('personas.attach-action', $persona), [
                'action_id' => $action->id,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('action_persona', [
            'persona_id' => $persona->id,
            'action_id' => $action->id,
        ]);
    }
}
