<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class StripeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StripeService $stripeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stripeService = new StripeService;
    }

    public function test_can_calculate_usage_cost()
    {
        $cost = $this->stripeService->calculateUsageCost('openai', 'gpt-4o', 1000);
        $this->assertIsFloat($cost);
        $this->assertGreaterThan(0, $cost);
    }

    public function test_usage_cost_calculation_is_accurate()
    {
        $words = 1000;
        $cost = $this->stripeService->calculateUsageCost('openai', 'gpt-4o', $words);

        // GPT-4o rate is 0.00003 per word
        $expectedCost = $words * 0.00003;
        $this->assertEquals($expectedCost, $cost);
    }

    public function test_unknown_provider_uses_default_rate()
    {
        $cost = $this->stripeService->calculateUsageCost('unknown', 'unknown-model', 1000);
        $this->assertIsFloat($cost);
        $this->assertGreaterThan(0, $cost);
    }

    public function test_get_subscription_status_for_user_without_subscription()
    {
        $user = User::factory()->create();

        $status = $this->stripeService->getSubscriptionStatus($user);

        $this->assertArrayHasKey('has_subscription', $status);
        $this->assertArrayHasKey('status', $status);
        $this->assertArrayHasKey('plan', $status);
        $this->assertArrayHasKey('trial_ends_at', $status);
        $this->assertArrayHasKey('current_period_end', $status);

        $this->assertFalse($status['has_subscription']);
        $this->assertEquals('free', $status['status']);
        $this->assertNull($status['plan']);
    }

    public function test_has_exceeded_limits_returns_correct_structure()
    {
        $team = \App\Models\Team::factory()->create([
            'stripe_plan' => 'starter',
            'words_used_this_month' => 1000,
            'api_calls_used_this_month' => 50,
        ]);

        $result = $this->stripeService->hasExceededLimits($team);

        $this->assertArrayHasKey('exceeded', $result);
        $this->assertArrayHasKey('limits', $result);
        $this->assertArrayHasKey('words', $result['limits']);
        $this->assertArrayHasKey('api_calls', $result['limits']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
