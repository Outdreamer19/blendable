<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $stripeService = app(StripeService::class);
        
        // Check if user is admin - admins can always access dashboard
        if ($stripeService->isAdmin($user)) {
            return redirect()->route('dashboard');
        }
        
        // For regular users, check subscription status
        $subscriptionStatus = $stripeService->getSubscriptionStatus($user);
        
        // If user has active subscription, go to dashboard
        if ($subscriptionStatus['has_subscription'] && in_array($subscriptionStatus['status'], ['active', 'trialing'])) {
            return redirect()->route('dashboard');
        }
        
        // Otherwise, redirect to billing (which will show onboarding if no subscription)
        return redirect()->route('billing.index');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
