# Stripe Webhook Configuration Guide

## Webhook Endpoint URL

Your webhook endpoint URL should be:
```
https://your-domain.com/api/webhooks/stripe
```

For local development with Laravel Herd, use:
```
https://your-app-name.test/api/webhooks/stripe
```

Or if using ngrok or similar:
```
https://your-ngrok-url.ngrok.io/api/webhooks/stripe
```

## Required Webhook Events

In the Stripe Dashboard, when creating/editing your webhook endpoint, select these events:

### Subscription Events
- ✅ `customer.subscription.created`
- ✅ `customer.subscription.updated`
- ✅ `customer.subscription.deleted`
- ✅ `customer.subscription.trial_will_end`

### Invoice Events
- ✅ `invoice.payment_succeeded`
- ✅ `invoice.payment_failed`

## Setup Steps

### 1. In Stripe Dashboard
1. Go to **Developers** → **Webhooks**
2. Click **Add endpoint**
3. Enter your webhook URL: `https://your-domain.com/api/webhooks/stripe`
4. Select the events listed above
5. Click **Add endpoint**

### 2. Get Webhook Signing Secret
1. After creating the webhook, click on it
2. In the **Signing secret** section, click **Reveal**
3. Copy the secret (starts with `whsec_...`)

### 3. Add to .env File
Add the webhook secret to your `.env` file:
```env
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxx
```

### 4. Test the Webhook
1. In Stripe Dashboard, go to your webhook endpoint
2. Click **Send test webhook**
3. Select an event (e.g., `customer.subscription.created`)
4. Click **Send test webhook**
5. Check your Laravel logs to verify it was received

## What Each Event Does

- **customer.subscription.created**: Updates user's plan when subscription is created
- **customer.subscription.updated**: Updates user's plan when subscription changes
- **customer.subscription.deleted**: Removes user's plan when subscription is cancelled
- **invoice.payment_succeeded**: Resets usage counters for new billing period
- **invoice.payment_failed**: Logs failed payment (you can add email notifications)
- **customer.subscription.trial_will_end**: Notifies before trial ends (you can add email notifications)

## Important Notes

⚠️ **For Local Development**: Use Stripe CLI or ngrok to forward webhooks to your local server:
```bash
stripe listen --forward-to localhost:8000/api/webhooks/stripe
```

This will give you a webhook signing secret for local testing.

## Verification

After setup, test by:
1. Creating a subscription through your app
2. Check Laravel logs: `storage/logs/laravel.log`
3. Look for "Stripe webhook received" messages
4. Verify the subscription was saved to the `subscriptions` table

