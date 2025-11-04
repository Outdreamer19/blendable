<?php

namespace App\Http\Middleware;

use App\Services\StripeService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireSubscription
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Check if user has an active subscription
        $subscriptionStatus = $this->stripeService->getSubscriptionStatus($user);

        // Allow access if user has an active subscription
        if ($subscriptionStatus['has_subscription'] && in_array($subscriptionStatus['status'], ['active', 'trialing'])) {
            return $next($request);
        }

        // Redirect to billing page if no subscription
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Subscription required',
                'message' => 'Please subscribe to a plan to access this feature.',
                'subscription_required' => true,
            ], 403);
        }

        return redirect()->route('billing.index')
            ->with('error', 'Please subscribe to a plan to access this feature.');
    }
}
