<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_pricing_page_loads_for_guests()
    {
        $response = $this->get(route('pricing'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Pricing')
            ->has('plans')
            ->has('currentPlan')
        );
    }

    public function test_pricing_page_loads_for_authenticated_users()
    {
        $user = User::factory()->create(['plan' => 'free']);

        $response = $this->actingAs($user)->get(route('pricing'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Pricing')
            ->has('plans')
            ->where('currentPlan', 'free')
        );
    }

    public function test_pricing_page_shows_all_plans()
    {
        $response = $this->get(route('pricing'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Pricing')
            ->has('plans', 3)
            ->where('plans.0.key', 'free')
            ->where('plans.1.key', 'pro')
            ->where('plans.2.key', 'business')
        );
    }

    public function test_pricing_page_shows_correct_plan_details()
    {
        $response = $this->get(route('pricing'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Pricing')
            ->where('plans.0.name', 'Free')
            ->where('plans.0.price', 0)
            ->where('plans.1.name', 'Pro')
            ->where('plans.1.price', 19)
            ->where('plans.2.name', 'Business')
            ->where('plans.2.price', 79)
        );
    }
}
