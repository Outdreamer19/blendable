<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\BillingPortal\Session as PortalSession;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Subscription;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    /**
     * Create or retrieve Stripe customer
     */
    public function createOrGetCustomer(User $user): string
    {
        if ($user->stripe_id) {
            return $user->stripe_id;
        }

        try {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            $user->update(['stripe_id' => $customer->id]);

            return $customer->id;
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Failed to create customer', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create checkout session for subscription
     */
    public function createCheckoutSession(User $user, string $plan, ?string $successUrl = null, ?string $cancelUrl = null): Session
    {
        $customerId = $this->createOrGetCustomer($user);
        $planConfig = config("stripe.plans.{$plan}");

        if (! $planConfig) {
            throw new \InvalidArgumentException("Plan '{$plan}' not found");
        }

        $successUrl = $successUrl ?: route('billing');
        $cancelUrl = $cancelUrl ?: route('billing');

        try {
            $session = Session::create([
                'customer' => $customerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $planConfig['price_id'],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'subscription_data' => [
                    'trial_period_days' => config('stripe.trial_days'),
                    'metadata' => [
                        'user_id' => $user->id,
                        'plan' => $plan,
                    ],
                ],
            ]);

            return $session;
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Failed to create checkout session', [
                'user_id' => $user->id,
                'plan' => $plan,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create billing portal session
     */
    public function createPortalSession(User $user, ?string $returnUrl = null): PortalSession
    {
        $customerId = $this->createOrGetCustomer($user);
        $returnUrl = $returnUrl ?: route('billing');

        try {
            $session = PortalSession::create([
                'customer' => $customerId,
                'return_url' => $returnUrl,
            ]);

            return $session;
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Failed to create portal session', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get subscription details
     */
    public function getSubscription(string $subscriptionId): ?Subscription
    {
        try {
            return Subscription::retrieve($subscriptionId);
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Failed to retrieve subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(string $subscriptionId, bool $immediately = false): bool
    {
        try {
            $subscription = Subscription::retrieve($subscriptionId);

            if ($immediately) {
                $subscription->cancel();
            } else {
                $subscription->cancel_at_period_end = true;
                $subscription->save();
            }

            return true;
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Failed to cancel subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Resume subscription
     */
    public function resumeSubscription(string $subscriptionId): bool
    {
        try {
            $subscription = Subscription::retrieve($subscriptionId);
            $subscription->cancel_at_period_end = false;
            $subscription->save();

            return true;
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Failed to resume subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Calculate usage cost
     */
    public function calculateUsageCost(string $provider, string $model, int $words): float
    {
        $rates = config('stripe.usage_rates');
        $rate = $rates[$provider][$model] ?? 0.00001; // Default rate

        return $words * $rate;
    }

    /**
     * Check if user has exceeded usage limits
     */
    public function hasExceededLimits(Team $team): array
    {
        $plan = config("stripe.plans.{$team->stripe_plan}");

        if (! $plan) {
            return ['exceeded' => false, 'limits' => []];
        }

        $limits = [
            'words' => $team->words_used_this_month >= $plan['words_limit'],
            'api_calls' => $team->api_calls_used_this_month >= $plan['api_calls_limit'],
        ];

        return [
            'exceeded' => in_array(true, $limits),
            'limits' => $limits,
        ];
    }

    /**
     * Get subscription status for user
     */
    public function getSubscriptionStatus(User $user): array
    {
        $team = $user->teams()->first();

        if (! $team || ! $team->stripe_id) {
            return [
                'has_subscription' => false,
                'status' => 'free',
                'plan' => null,
                'trial_ends_at' => null,
                'current_period_end' => null,
            ];
        }

        $subscription = $this->getSubscription($team->stripe_id);

        if (! $subscription) {
            return [
                'has_subscription' => false,
                'status' => 'free',
                'plan' => null,
                'trial_ends_at' => null,
                'current_period_end' => null,
            ];
        }

        return [
            'has_subscription' => true,
            'status' => $subscription->status,
            'plan' => $team->stripe_plan,
            'trial_ends_at' => $subscription->trial_end ? now()->timestamp($subscription->trial_end) : null,
            'current_period_end' => now()->timestamp($subscription->current_period_end),
        ];
    }

    /**
     * Get payment method details
     */
    public function getPaymentMethod(User $user): ?array
    {
        if (! $user->stripe_id) {
            return null;
        }

        try {
            $customer = Customer::retrieve($user->stripe_id);

            if (! $customer->paymentMethods) {
                return null;
            }

            $paymentMethods = $customer->paymentMethods->all(['type' => 'card']);

            if (empty($paymentMethods->data)) {
                return null;
            }

            $paymentMethod = $paymentMethods->data[0];

            return [
                'id' => $paymentMethod->id,
                'brand' => $paymentMethod->card->brand,
                'last_four' => $paymentMethod->card->last4,
                'exp_month' => $paymentMethod->card->exp_month,
                'exp_year' => $paymentMethod->card->exp_year,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Failed to retrieve payment method', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get recent invoices
     */
    public function getInvoices(User $user, int $limit = 10): array
    {
        if (! $user->stripe_id) {
            return [];
        }

        try {
            $invoices = \Stripe\Invoice::all([
                'customer' => $user->stripe_id,
                'limit' => $limit,
            ]);

            return array_map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'number' => $invoice->number,
                    'amount_paid' => $invoice->amount_paid / 100, // Convert from cents
                    'status' => $invoice->status,
                    'created_at' => now()->timestamp($invoice->created),
                ];
            }, $invoices->data);
        } catch (ApiErrorException $e) {
            Log::error('Stripe: Failed to retrieve invoices', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
