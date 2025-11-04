<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\UsageLedger;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsageTest extends TestCase
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

    public function test_usage_index_displays_usage_statistics()
    {
        // Create test usage data
        UsageLedger::factory()->count(10)->create([
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

    public function test_usage_shows_daily_statistics()
    {
        // Create usage data for different days
        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'created_at' => now()->subDays(1),
            'tokens_in' => 100,
            'tokens_out' => 200,
            'words_debited' => 50,
        ]);

        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'created_at' => now(),
            'tokens_in' => 150,
            'tokens_out' => 300,
            'words_debited' => 75,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
            ->has('dailyUsage')
        );
    }

    public function test_usage_shows_model_breakdown()
    {
        // Create usage data for different models
        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'model_key' => 'gpt-4o',
            'tokens_in' => 100,
            'tokens_out' => 200,
        ]);

        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'model_key' => 'claude-3-sonnet-20240229',
            'tokens_in' => 150,
            'tokens_out' => 300,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
            ->has('modelUsage')
        );
    }

    public function test_usage_shows_hourly_patterns()
    {
        // Create usage data for different hours
        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'created_at' => now()->setHour(9),
        ]);

        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'created_at' => now()->setHour(14),
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
            ->has('hourlyUsage')
        );
    }

    public function test_usage_shows_workspace_comparison()
    {
        // Create another workspace
        $otherWorkspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);
        $this->user->workspaces()->attach($otherWorkspace, ['role' => 'admin']);

        // Create usage data for both workspaces
        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'tokens_in' => 100,
        ]);

        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $otherWorkspace->id,
            'tokens_in' => 200,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
            ->has('workspaceUsage')
        );
    }

    public function test_usage_shows_cost_estimation()
    {
        // Create usage data
        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'tokens_in' => 1000,
            'tokens_out' => 2000,
            'model_key' => 'gpt-4o',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
            ->where('usageStats', function ($stats) {
                return isset($stats['estimated_cost']) &&
                       is_numeric($stats['estimated_cost']);
            })
        );
    }

    public function test_usage_requires_authentication()
    {
        $response = $this->get(route('usage.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_usage_requires_workspace_access()
    {
        // Create user without workspace access
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->get(route('usage.index'));

        // Should still load but with no usage data
        $response->assertStatus(200);
    }

    public function test_usage_filters_by_date_range()
    {
        // Create usage data for different dates
        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'created_at' => now()->subDays(7),
        ]);

        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'created_at' => now()->subDays(1),
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index', [
                'start_date' => now()->subDays(3)->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
            ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
        );
    }

    public function test_usage_filters_by_model()
    {
        // Create usage data for different models
        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'model_key' => 'gpt-4o',
        ]);

        UsageLedger::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'model_key' => 'claude-3-sonnet-20240229',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index', ['model' => 'gpt-4o']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
        );
    }

    public function test_usage_shows_export_functionality()
    {
        // Create usage data
        UsageLedger::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Usage/Index')
            ->has('usageStats')
        );
    }

    public function test_usage_export_csv()
    {
        // Create usage data
        UsageLedger::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.export', ['format' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_usage_export_json()
    {
        // Create usage data
        UsageLedger::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('usage.export', ['format' => 'json']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }
}
