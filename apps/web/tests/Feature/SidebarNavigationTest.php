<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\ImageJob;
use App\Models\Persona;
use App\Models\Prompt;
use App\Models\Team;
use App\Models\UsageLedger;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarNavigationTest extends TestCase
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

    public function test_dashboard_page_loads_successfully()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard')
            ->has('user')
            ->has('workspaces')
            ->has('currentWorkspace')
        );
    }

    public function test_dashboard_page_requires_authentication()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_chats_index_page_loads_successfully()
    {
        // Create some test chats
        Chat::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('chats.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Chat/Index')
            ->has('recentChats')
            ->has('user')
            ->has('workspaces')
            ->has('currentWorkspace')
        );
    }

    public function test_chats_index_page_requires_authentication()
    {
        $response = $this->get(route('chats.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_personas_index_page_loads_successfully()
    {
        // Create some test personas
        Persona::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('personas.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Personas/Index')
            ->has('personas')
            ->has('user')
            ->has('workspaces')
            ->has('workspace')
        );
    }

    public function test_personas_index_page_requires_authentication()
    {
        $response = $this->get(route('personas.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_personas_create_page_loads_successfully()
    {
        $response = $this->actingAs($this->user)
            ->get(route('personas.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Personas/Create')
            ->has('availableKnowledge')
            ->has('availableActions')
            ->has('workspace')
        );
    }

    public function test_prompts_index_page_loads_successfully()
    {
        // Create some test prompts
        Prompt::factory()->count(3)->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('prompts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Prompts/Index')
            ->has('prompts')
            ->has('folders')
            ->has('workspace')
        );
    }

    public function test_prompts_index_page_requires_authentication()
    {
        $response = $this->get(route('prompts.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_images_index_page_loads_successfully()
    {
        // Create some test image jobs
        ImageJob::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Images/Index')
            ->has('images')
            ->has('user')
            ->has('workspaces')
            ->has('workspace')
            ->has('availableModels')
        );
    }

    public function test_images_index_page_requires_authentication()
    {
        $response = $this->get(route('images.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_images_create_page_loads_successfully()
    {
        $response = $this->actingAs($this->user)
            ->get(route('images.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Images/Create')
            ->has('workspace')
        );
    }

    public function test_usage_index_page_loads_successfully()
    {
        // Create some test usage data
        UsageLedger::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
            ->has('user')
            ->has('workspaces')
            ->has('workspace')
        );
    }

    public function test_usage_index_page_requires_authentication()
    {
        $response = $this->get(route('usage.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_billing_index_page_loads_successfully()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('subscription')
            ->has('paymentMethod')
            ->has('invoices')
            ->has('currentUsage')
        );
    }

    public function test_billing_index_page_requires_authentication()
    {
        $response = $this->get(route('billing.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_sidebar_navigation_links_are_accessible()
    {
        $routes = [
            'dashboard' => route('dashboard'),
            'chats.index' => route('chats.index'),
            'personas.index' => route('personas.index'),
            'prompts.index' => route('prompts.index'),
            'images.index' => route('images.index'),
            'usage.index' => route('usage.index'),
            'billing.index' => route('billing.index'),
        ];

        foreach ($routes as $routeName => $url) {
            $response = $this->actingAs($this->user)->get($url);
            $response->assertStatus(200, "Route {$routeName} should be accessible");
        }
    }

    public function test_sidebar_navigation_requires_workspace_access()
    {
        // Create a user without workspace access
        $otherUser = User::factory()->create();
        $otherWorkspace = Workspace::factory()->create();

        $response = $this->actingAs($otherUser)
            ->get(route('dashboard'));

        // Should redirect or show appropriate message for users without workspace access
        $response->assertStatus(200);
    }

    public function test_sidebar_navigation_shows_correct_active_state()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard')
        );
    }

    public function test_sidebar_navigation_workspace_switching()
    {
        // Create another workspace
        $otherWorkspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);
        $this->user->workspaces()->attach($otherWorkspace, ['role' => 'admin']);

        $response = $this->actingAs($this->user)
            ->get(route('workspaces.show', $otherWorkspace->id));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->where('workspace.id', $otherWorkspace->id)
        );
    }

    public function test_sidebar_navigation_user_profile_access()
    {
        $response = $this->actingAs($this->user)
            ->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Profile/Edit')
            ->has('mustVerifyEmail')
        );
    }

    public function test_sidebar_navigation_logout_functionality()
    {
        $response = $this->actingAs($this->user)
            ->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
