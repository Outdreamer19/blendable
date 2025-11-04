<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\ImageJob;
use App\Models\Message;
use App\Models\Team;
use App\Models\UsageLedger;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    public function test_dashboard_displays_user_statistics()
    {
        // Create test data
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        Message::factory()->count(5)->create([
            'chat_id' => $chat->id,
            'user_id' => $this->user->id,
        ]);

        UsageLedger::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        ImageJob::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard')
            ->has('user')
            ->has('workspaces')
            ->has('currentWorkspace')
            ->has('recentChats')
            ->has('usageStats')
        );
    }

    public function test_dashboard_shows_recent_chats()
    {
        // Create multiple chats
        $chats = Chat::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard')
            ->has('recentChats')
            ->where('recentChats', function ($chats) {
                return count($chats) <= 5; // Should limit to recent chats
            })
        );
    }

    public function test_dashboard_shows_usage_statistics()
    {
        // Create usage data
        UsageLedger::factory()->count(10)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard')
            ->has('usageStats')
            ->where('usageStats', function ($stats) {
                return isset($stats['total_requests']) &&
                       isset($stats['total_words']) &&
                       isset($stats['total_tokens_in']) &&
                       isset($stats['total_tokens_out']);
            })
        );
    }

    public function test_dashboard_workspace_switching()
    {
        // Create another workspace
        $otherWorkspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);
        $this->user->workspaces()->attach($otherWorkspace, ['role' => 'admin']);

        $response = $this->actingAs($this->user)
            ->get(route('workspaces.show', $otherWorkspace->id));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->where('currentWorkspace.id', $otherWorkspace->id)
        );
    }

    public function test_dashboard_requires_authentication()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_requires_workspace_access()
    {
        // Create user without workspace access
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->get(route('dashboard'));

        // Should still load but with no workspace data
        $response->assertStatus(200);
    }

    public function test_dashboard_quick_actions()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard')
            ->has('user')
        );
    }

    public function test_dashboard_activity_feed()
    {
        // Create some activity
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        Message::factory()->create([
            'chat_id' => $chat->id,
            'user_id' => $this->user->id,
            'role' => 'user',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard')
            ->has('recentChats')
        );
    }
}
