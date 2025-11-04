<?php

namespace App\Http\Controllers;

use App\Enums\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $plans = collect(Plan::cases())->map(function (Plan $plan) {
            return [
                'key' => $plan->value,
                'name' => $plan->label(),
                'price' => $plan->priceGbp(),
                'tokens' => $plan->monthlyTokens(),
                'chats' => $plan->monthlyChats(),
                'models' => $plan->allowedModels(),
                'features' => $plan->features(),
                'seats' => $plan->seatsIncluded(),
            ];
        });

        return Inertia::render('Marketing/Pricing', [
            'plans' => $plans,
            'currentPlan' => $request->user()?->plan ?? null,
            'isAuthenticated' => $request->user() !== null,
        ]);
    }
}
