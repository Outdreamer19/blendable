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
        $currentWorkspace = $user->currentWorkspace();

        // Get subscription status using our StripeService
        $subscriptionStatus = $this->stripeService->getSubscriptionStatus($user);
        $paymentMethod = $this->stripeService->getPaymentMethod($user);
        $invoices = $this->stripeService->getInvoices($user, 5);

        // Get current usage
        $currentUsage = [
            'words_used' => 0,
            'words_limit' => 0, // No free tier
            'api_calls' => 0,
            'api_calls_limit' => 100,
            'cost' => 0,
            'cost_percentage' => 0,
            'usage_percentage' => 0,
        ];

        // Get team usage if user has a team
        $team = $user->teams()->first();
        if ($team && $subscriptionStatus['has_subscription']) {
            $plan = config("stripe.plans.{$subscriptionStatus['plan']}");
            if ($plan) {
                $currentUsage = [
                    'words_used' => $team->words_used_this_month ?? 0,
                    'words_limit' => $plan['words_limit'],
                    'api_calls' => $team->api_calls_used_this_month ?? 0,
                    'api_calls_limit' => $plan['api_calls_limit'],
                    'cost' => $this->stripeService->calculateUsageCost('openai', 'gpt-4o', $team->words_used_this_month ?? 0),
                    'cost_percentage' => 0, // Calculate based on plan limits
                    'usage_percentage' => $plan['words_limit'] > 0 ?
                        (($team->words_used_this_month ?? 0) / $plan['words_limit']) * 100 : 0,
                ];
            }
        }

        // Get plan data for the billing page
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

        return Inertia::render('Billing/Index', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'plan' => $user->plan,
            ],
            'workspaces' => $user->workspaces()->get(),
            'currentWorkspace' => $currentWorkspace,
            'subscription' => $subscriptionStatus['has_subscription'] ? [
                'id' => $team->stripe_id ?? null,
                'plan_name' => $this->getPlanName($subscriptionStatus['plan']),
                'plan_price' => $this->getPlanPrice($subscriptionStatus['plan']),
                'status' => $subscriptionStatus['status'],
                'current_period_end' => $subscriptionStatus['current_period_end'],
            ] : null,
            'paymentMethod' => $paymentMethod,
            'invoices' => $invoices,
            'currentUsage' => $currentUsage,
            'plans' => $plans,
        ]);
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'plan' => 'required|in:pro,business',
            'email' => 'required_if:user,null|email',
        ]);

        $plan = $request->plan;
        $priceId = $this->getPriceId($plan);

        try {
            if ($user) {
                // Authenticated user - use Cashier
                $checkout = $user->newSubscription('default', $priceId)
                    ->checkout([
                        'success_url' => route('billing.success'),
                        'cancel_url' => route('pricing'),
                        'metadata' => [
                            'plan' => $plan,
                        ],
                    ]);
            } else {
                // Anonymous user - create Stripe checkout session directly
                $checkout = $this->createAnonymousCheckout($priceId, $plan, $request->email);
            }

            return redirect($checkout->url);
        } catch (IncompletePayment $exception) {
            return redirect()->route('cashier.payment', $exception->payment->id);
        }
    }

    public function portal()
    {
        $user = Auth::user();

        return $user->redirectToBillingPortal(route('billing.index'));
    }

    public function success()
    {
        return redirect()->route('billing.index')
            ->with('success', 'Subscription activated successfully!');
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

    protected function getPriceId(string $plan): string
    {
        $priceIds = config('billing.stripe.prices');

        return $priceIds[$plan] ?? throw new \InvalidArgumentException("Invalid plan: {$plan}");
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

        $plan = config("stripe.plans.{$planKey}");

        return $plan ? $plan['name'] : null;
    }

    /**
     * Get plan price from key
     */
    protected function getPlanPrice(?string $planKey): ?float
    {
        if (! $planKey) {
            return null;
        }

        $plan = config("stripe.plans.{$planKey}");

        return $plan ? $plan['price'] : null;
    }
}
