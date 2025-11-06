<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    /**
     * Handle Stripe webhook events
     */
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook: Invalid payload', ['error' => $e->getMessage()]);

            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook: Invalid signature', ['error' => $e->getMessage()]);

            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook received', ['type' => $event->type, 'id' => $event->id]);

        // Handle the event
        switch ($event->type) {
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;
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
            case 'customer.subscription.trial_will_end':
                $this->handleTrialWillEnd($event->data->object);
                break;
            default:
                Log::info('Stripe webhook: Unhandled event type', ['type' => $event->type]);
        }

        return response('OK', 200);
    }

    /**
     * Handle subscription created event
     */
    protected function handleSubscriptionCreated($subscription): void
    {
        $customerId = $subscription->customer;
        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            Log::error('Stripe webhook: User not found for customer', ['customer_id' => $customerId]);

            return;
        }

        // Sync subscription with Cashier - this will create/update the subscription record
        $user->syncStripeCustomerDetails();
        
        // Get subscription name from metadata (Cashier uses 'default' by default)
        $subscriptionName = $subscription->metadata->subscription_name ?? 'default';
        
        // Ensure subscription is synced in database
        $user->subscriptions()->updateOrCreate(
            ['stripe_id' => $subscription->id],
            [
                'user_id' => $user->id,
                'type' => $subscriptionName,
                'stripe_status' => $subscription->status,
                'stripe_price' => $subscription->items->data[0]->price->id,
                'quantity' => $subscription->items->data[0]->quantity ?? 1,
                'trial_ends_at' => $subscription->trial_end ? now()->setTimestamp($subscription->trial_end) : null,
                'ends_at' => $subscription->cancel_at ? now()->setTimestamp($subscription->cancel_at) : null,
            ]
        );

        $plan = $this->getPlanFromPriceId($subscription->items->data[0]->price->id);

        $user->update([
            'plan' => $plan,
            'token_usage_month' => 0,
            'chat_count_month' => 0,
            'billing_period_start' => now()->startOfMonth(),
        ]);

        Log::info('Stripe webhook: Subscription created', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'plan' => $plan,
            'status' => $subscription->status,
        ]);
    }

    /**
     * Handle subscription updated event
     */
    protected function handleSubscriptionUpdated($subscription): void
    {
        $customerId = $subscription->customer;
        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            Log::error('Stripe webhook: User not found for customer', ['customer_id' => $customerId]);

            return;
        }

        // Sync subscription with Cashier
        $user->syncStripeCustomerDetails();
        
        // Update subscription record
        $subscriptionName = $subscription->metadata->subscription_name ?? 'default';
        
        $user->subscriptions()->updateOrCreate(
            ['stripe_id' => $subscription->id],
            [
                'user_id' => $user->id,
                'type' => $subscriptionName,
                'stripe_status' => $subscription->status,
                'stripe_price' => $subscription->items->data[0]->price->id,
                'quantity' => $subscription->items->data[0]->quantity ?? 1,
                'trial_ends_at' => $subscription->trial_end ? now()->setTimestamp($subscription->trial_end) : null,
                'ends_at' => $subscription->cancel_at ? now()->setTimestamp($subscription->cancel_at) : null,
            ]
        );

        $plan = $this->getPlanFromPriceId($subscription->items->data[0]->price->id);

        $user->update([
            'plan' => $plan,
        ]);

        Log::info('Stripe webhook: Subscription updated', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'plan' => $plan,
            'status' => $subscription->status,
        ]);
    }

    /**
     * Handle subscription deleted event
     */
    protected function handleSubscriptionDeleted($subscription): void
    {
        $customerId = $subscription->customer;
        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            Log::error('Stripe webhook: User not found for customer', ['customer_id' => $customerId]);

            return;
        }

        // Cancel/delete subscription record
        $user->subscriptions()
            ->where('stripe_id', $subscription->id)
            ->update([
                'stripe_status' => 'canceled',
                'ends_at' => now(),
            ]);

        // Block access when subscription is deleted
        $user->update([
            'plan' => null,
        ]);

        Log::info('Stripe webhook: Subscription deleted', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
        ]);
    }

    /**
     * Handle payment succeeded event
     */
    protected function handlePaymentSucceeded($invoice): void
    {
        $customerId = $invoice->customer;
        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            Log::error('Stripe webhook: User not found for customer', ['customer_id' => $customerId]);

            return;
        }

        // Reset usage counters for the new billing period
        $user->update([
            'token_usage_month' => 0,
            'chat_count_month' => 0,
            'billing_period_start' => now()->startOfMonth(),
        ]);

        Log::info('Stripe webhook: Payment succeeded', [
            'user_id' => $user->id,
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount_paid,
        ]);
    }

    /**
     * Handle payment failed event
     */
    protected function handlePaymentFailed($invoice): void
    {
        $customerId = $invoice->customer;
        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            Log::error('Stripe webhook: User not found for customer', ['customer_id' => $customerId]);

            return;
        }

        Log::info('Stripe webhook: Payment failed', [
            'user_id' => $user->id,
            'invoice_id' => $invoice->id,
        ]);
    }

    /**
     * Handle trial will end event
     */
    protected function handleTrialWillEnd($subscription): void
    {
        $customerId = $subscription->customer;
        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            Log::error('Stripe webhook: User not found for customer', ['customer_id' => $customerId]);

            return;
        }

        // Send notification email about trial ending
        // This would typically trigger an email notification
        Log::info('Stripe webhook: Trial will end', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'trial_end' => $subscription->trial_end,
        ]);
    }

    /**
     * Get plan name from Stripe price ID
     */
    protected function getPlanFromPriceId(string $priceId): ?string
    {
        $prices = config('billing.stripe.prices');

        foreach ($prices as $planKey => $planPriceId) {
            if ($planPriceId === $priceId) {
                return $planKey;
            }
        }

        return null;
    }
}
