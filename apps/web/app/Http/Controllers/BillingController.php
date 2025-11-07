<?php

namespace App\Http\Controllers;

use App\Enums\Plan;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\IncompletePayment;

class BillingController extends Controller
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get subscription status using our StripeService
        $subscriptionStatus = $this->stripeService->getSubscriptionStatus($user);
        
        // If user doesn't have an active subscription, show onboarding page
        if (!$subscriptionStatus['has_subscription'] || !in_array($subscriptionStatus['status'], ['active', 'trialing'])) {
            // Get plan data for the onboarding page
            $plans = collect(Plan::cases())->map(function (Plan $plan) {
                return [
                    'key' => $plan->value,
                    'name' => $plan->label(),
                    'price' => $plan->priceUsd(),
                    'tokens' => $plan->monthlyTokens(),
                    'chats' => $plan->monthlyChats(),
                    'models' => $plan->allowedModels(),
                    'features' => $plan->features(),
                    'seats' => $plan->seatsIncluded(),
                ];
            });

            return Inertia::render('Onboarding/Subscribe', [
                'plans' => $plans,
            ]);
        }

        // User has active subscription - show full billing page with AppLayout
        $currentWorkspace = $user->currentWorkspace();
        $paymentMethod = $this->stripeService->getPaymentMethod($user);
        $invoices = $this->stripeService->getInvoices($user, 5);

        // Get current usage from UsageLedger for current month
        $billingPeriodStart = $user->billing_period_start ?? now()->startOfMonth();
        $monthlyUsage = \App\Models\UsageLedger::where('user_id', $user->id)
            ->where('usage_date', '>=', $billingPeriodStart)
            ->selectRaw('
                COALESCE(SUM(words_debited), 0) as words_used,
                COUNT(*) as api_calls
            ')
            ->first();

        // Get plan limits from Plan enum
        $planKey = $subscriptionStatus['plan'];
        $planEnum = null;
        $planConfig = null;
        
        if ($planKey) {
            try {
                $planEnum = \App\Enums\Plan::from($planKey);
                $planConfig = config("billing.plans.{$planKey}");
            } catch (\ValueError $e) {
                // Plan not found
            }
        }

        $wordsUsed = $monthlyUsage->words_used ?? $user->token_usage_month ?? 0;
        $apiCalls = $monthlyUsage->api_calls ?? $user->chat_count_month ?? 0;
        
        // Calculate limits from plan
        $wordsLimit = 0;
        $apiCallsLimit = 100; // Default
        
        if ($planConfig) {
            // Convert tokens to approximate words (rough estimate: 1 token ≈ 0.75 words)
            $wordsLimit = (int) ($planConfig['monthly_tokens'] * 0.75);
        }
        
        // Calculate cost
        $cost = $this->stripeService->calculateUsageCost('openai', 'gpt-4o', $wordsUsed);

        $currentUsage = [
            'words_used' => $wordsUsed,
            'words_limit' => $wordsLimit,
            'api_calls' => $apiCalls,
            'api_calls_limit' => $apiCallsLimit,
            'cost' => $cost,
            'cost_percentage' => 0,
            'usage_percentage' => $wordsLimit > 0 ? ($wordsUsed / $wordsLimit) * 100 : 0,
        ];

        // Get plan data for the billing page
        $plans = collect(Plan::cases())->map(function (Plan $plan) {
            return [
                'key' => $plan->value,
                'name' => $plan->label(),
                'price' => $plan->priceUsd(),
                'tokens' => $plan->monthlyTokens(),
                'chats' => $plan->monthlyChats(),
                'models' => $plan->allowedModels(),
                'features' => $plan->features(),
                'seats' => $plan->seatsIncluded(),
            ];
        });

        return Inertia::render('Billing/Index', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'plan' => $user->plan,
            ],
            'workspaces' => $user->workspaces()->get(),
            'currentWorkspace' => $currentWorkspace,
            'subscription' => [
                'id' => $team->stripe_id ?? null,
                'plan_name' => $this->getPlanName($subscriptionStatus['plan']),
                'plan_price' => $this->getPlanPrice($subscriptionStatus['plan']),
                'status' => $subscriptionStatus['status'],
                'current_period_end' => $subscriptionStatus['current_period_end'],
            ],
            'paymentMethod' => $paymentMethod,
            'invoices' => $invoices,
            'currentUsage' => $currentUsage,
            'plans' => $plans,
        ]);
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $plan = $request->input('plan') ?? $request->query('plan');
        $interval = $request->input('interval') ?? $request->query('interval') ?? 'monthly';
        
        if (!$plan) {
            return redirect()->route('pricing')
                ->with('error', 'Please select a plan.');
        }

        $request->validate([
            'plan' => 'required|in:pro,business',
            'interval' => 'nullable|in:monthly,yearly',
        ]);

        // If user is not authenticated, redirect to registration with plan parameter
        if (!$user) {
            return redirect()->route('register', [
                'plan' => $plan,
                'interval' => $interval,
            ])
                ->with('info', 'Please create an account to continue with checkout.');
        }

        try {
            $priceId = $this->getPriceId($plan, $interval);
        } catch (\InvalidArgumentException $e) {
            \Log::error('Stripe price ID not configured', [
                'plan' => $plan,
                'interval' => $interval,
                'error' => $e->getMessage(),
            ]);
            
            $errorMessage = $interval === 'yearly' 
                ? 'Yearly billing is not yet available. Please select monthly billing or contact support.'
                : 'This plan is not available at the moment. Please contact support.';
            
            return redirect()->route('pricing')
                ->with('error', $errorMessage);
        }

        try {
            // Authenticated user - use Cashier
            $checkout = $user->newSubscription('default', $priceId)
                ->checkout([
                    'success_url' => route('billing.success'),
                    'cancel_url' => route('pricing'),
                    'metadata' => [
                        'plan' => $plan,
                        'interval' => $interval,
                    ],
                ]);

            return redirect($checkout->url);
        } catch (IncompletePayment $exception) {
            return redirect()->route('cashier.payment', $exception->payment->id);
        } catch (\Exception $e) {
            \Log::error('Stripe checkout failed', [
                'user_id' => $user->id,
                'plan' => $plan,
                'price_id' => $priceId,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('pricing')
                ->with('error', 'Unable to process checkout. Please try again or contact support.');
        }
    }

    public function portal()
    {
        $user = Auth::user();

        return $user->redirectToBillingPortal(route('billing.index'));
    }

    public function success(Request $request)
    {
        $user = Auth::user();
        
        // Refresh subscription data from Stripe/Cashier
        // Cashier automatically syncs subscriptions, but we can force a refresh
        if ($user->stripe_id) {
            $user->syncStripeCustomerDetails();
        }
        
        // Check subscription status using Cashier directly
        $subscription = $user->subscription('default');
        
        if ($subscription && $subscription->active()) {
            // Get plan from subscription metadata or price ID
            $priceId = $subscription->stripe_price;
            $plan = $this->getPlanFromPriceId($priceId);
            
            if ($plan) {
                $user->update(['plan' => $plan]);
            }
            
            // Redirect to dashboard or billing page
            return redirect()->route('dashboard')
                ->with('success', 'Subscription activated successfully! Welcome to Blendable!');
        }
        
        // If subscription not active yet, redirect to billing (will show onboarding)
        return redirect()->route('billing.index')
            ->with('info', 'Your subscription is being processed. Please wait a moment...');
    }
    
    protected function getPlanFromPriceId(string $priceId): ?string
    {
        $prices = config('billing.stripe.prices');
        
        foreach ($prices as $planKey => $planPrices) {
            foreach ($planPrices as $interval => $planPriceId) {
                if ($planPriceId === $priceId) {
                    return $planKey;
                }
            }
        }
        
        return null;
    }

    public function cancel()
    {
        return redirect()->route('billing.index')
            ->with('error', 'Subscription was cancelled.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('cashier.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
        }

        return response('OK', 200);
    }

    protected function getPriceId(string $plan, string $interval = 'monthly'): string
    {
        $priceIds = config('billing.stripe.prices');

        if (!isset($priceIds[$plan])) {
            throw new \InvalidArgumentException("Invalid plan: {$plan}");
        }

        if (!isset($priceIds[$plan][$interval])) {
            throw new \InvalidArgumentException("Invalid interval: {$interval} for plan: {$plan}");
        }

        $priceId = $priceIds[$plan][$interval];

        if (empty($priceId)) {
            throw new \InvalidArgumentException("Price ID not configured for plan: {$plan}, interval: {$interval}");
        }

        return $priceId;
    }

    protected function createAnonymousCheckout(string $priceId, string $plan, string $email)
    {
        \Stripe\Stripe::setApiKey(config('cashier.secret'));

        return \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('billing.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('pricing'),
            'customer_email' => $email,
            'metadata' => [
                'plan' => $plan,
                'anonymous' => 'true',
            ],
        ]);
    }

    protected function handleSubscriptionUpdated($subscription)
    {
        // Handle subscription updates
        $user = \App\Models\User::where('stripe_id', $subscription->customer)->first();
        if ($user) {
            // Update user's subscription status
            // This would typically be handled by Cashier automatically
        }
    }

    protected function handleSubscriptionDeleted($subscription)
    {
        // Handle subscription cancellation
        $user = \App\Models\User::where('stripe_id', $subscription->customer)->first();
        if ($user) {
            // Handle subscription cancellation
        }
    }

    protected function handlePaymentSucceeded($invoice)
    {
        // Handle successful payment
    }

    protected function handlePaymentFailed($invoice)
    {
        // Handle failed payment
    }

    /**
     * Get plan name from key
     */
    protected function getPlanName(?string $planKey): ?string
    {
        if (! $planKey) {
            return null;
        }

        try {
            $plan = \App\Enums\Plan::from($planKey);
            return $plan->label();
        } catch (\ValueError $e) {
            return null;
        }
    }

    /**
     * Get plan price from key
     */
    protected function getPlanPrice(?string $planKey): ?float
    {
        if (! $planKey) {
            return null;
        }

        try {
            $plan = \App\Enums\Plan::from($planKey);
            return (float) $plan->priceUsd();
        } catch (\ValueError $e) {
            return null;
        }
    }
}
