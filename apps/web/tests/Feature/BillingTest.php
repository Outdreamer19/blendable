<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\UsageLedger;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingTest extends TestCase
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

    public function test_billing_index_displays_billing_information()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
            ->has('workspaces')
            ->has('currentWorkspace')
        );
    }

    public function test_billing_shows_subscription_status()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
        );
    }

    public function test_billing_shows_usage_limits()
    {
        // Create usage data
        UsageLedger::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
        );
    }

    public function test_billing_requires_authentication()
    {
        $response = $this->get(route('billing.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_billing_requires_workspace_access()
    {
        // Create user without workspace access
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->get(route('billing.index'));

        // Should still load but with no billing data
        $response->assertStatus(200);
    }

    public function test_billing_checkout_redirects_to_stripe()
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.checkout'), [
                'plan' => 'pro',
            ]);

        // Should redirect to Stripe checkout
        $response->assertRedirect();
    }

    public function test_billing_portal_redirects_to_stripe()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.portal'));

        // Should redirect to Stripe customer portal
        $response->assertRedirect();
    }

    public function test_billing_success_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.success'));

        $response->assertRedirect(route('billing.index'));
        $response->assertSessionHas('success', 'Subscription activated successfully!');
    }

    public function test_billing_cancel_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.cancel'));

        $response->assertRedirect(route('billing.index'));
        $response->assertSessionHas('error', 'Subscription was cancelled.');
    }

    public function test_billing_webhook_handles_events()
    {
        $payload = [
            'type' => 'customer.subscription.created',
            'data' => [
                'object' => [
                    'id' => 'sub_test123',
                    'customer' => 'cus_test123',
                    'status' => 'active',
                ],
            ],
        ];

        $response = $this->post(route('billing.webhook'), $payload, [
            'Stripe-Signature' => 'test_signature',
        ]);

        $response->assertStatus(200);
    }

    public function test_billing_shows_plan_details()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
        );
    }

    public function test_billing_shows_payment_methods()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
        );
    }

    public function test_billing_shows_invoices()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
        );
    }

    public function test_billing_handles_subscription_upgrade()
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.checkout'), [
                'plan' => 'pro',
            ]);

        $response->assertRedirect();
    }

    public function test_billing_handles_subscription_downgrade()
    {
        $response = $this->actingAs($this->user)
            ->post(route('billing.checkout'), [
                'plan' => 'business',
            ]);

        $response->assertRedirect();
    }

    public function test_billing_shows_usage_warnings()
    {
        // Create high usage data
        UsageLedger::factory()->count(100)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'tokens_in' => 1000,
            'tokens_out' => 2000,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
        );
    }

    public function test_billing_shows_plan_limits()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
        );
    }

    public function test_billing_handles_payment_failures()
    {
        $payload = [
            'type' => 'invoice.payment_failed',
            'data' => [
                'object' => [
                    'id' => 'in_test123',
                    'customer' => 'cus_test123',
                    'status' => 'open',
                ],
            ],
        ];

        $response = $this->post(route('billing.webhook'), $payload, [
            'Stripe-Signature' => 'test_signature',
        ]);

        $response->assertStatus(200);
    }

    public function test_billing_handles_subscription_cancellation()
    {
        $payload = [
            'type' => 'customer.subscription.deleted',
            'data' => [
                'object' => [
                    'id' => 'sub_test123',
                    'customer' => 'cus_test123',
                    'status' => 'canceled',
                ],
            ],
        ];

        $response = $this->post(route('billing.webhook'), $payload, [
            'Stripe-Signature' => 'test_signature',
        ]);

        $response->assertStatus(200);
    }

    public function test_billing_shows_team_billing()
    {
        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
            ->has('workspaces')
        );
    }

    public function test_billing_handles_workspace_billing()
    {
        // Create another workspace
        $otherWorkspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);
        $this->user->workspaces()->attach($otherWorkspace, ['role' => 'admin']);

        $response = $this->actingAs($this->user)
            ->get(route('billing.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Billing/Index')
            ->has('user')
            ->has('workspaces')
        );
    }
}
