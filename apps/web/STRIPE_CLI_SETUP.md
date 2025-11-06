# Stripe CLI Setup for Local Development

## Installation

### macOS (using Homebrew)
```bash
brew install stripe/stripe-cli/stripe
```

### Other Platforms
Download from: https://stripe.com/docs/stripe-cli

## Login to Stripe CLI

1. Run the login command:
```bash
stripe login
```

2. This will open your browser to authenticate with Stripe

## Forward Webhooks to Local Server

### Start Webhook Forwarding

Run this command to forward Stripe webhooks to your local Laravel application:

```bash
stripe listen --forward-to localhost:8000/api/webhooks/stripe
```

Or if using Laravel Herd/Valet with a custom domain:
```bash
stripe listen --forward-to your-app.test/api/webhooks/stripe
```

### Get Webhook Signing Secret

When you run `stripe listen`, it will output a webhook signing secret that looks like:
```
> Ready! Your webhook signing secret is whsec_xxxxxxxxxxxxxxxxxxxxx
```

**Copy this secret and add it to your `.env` file:**
```env
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxx
```

## Test Webhooks Locally

### Trigger Test Events

You can trigger test events directly from the CLI:

```bash
# Test subscription created
stripe trigger customer.subscription.created

# Test subscription updated
stripe trigger customer.subscription.updated

# Test payment succeeded
stripe trigger invoice.payment_succeeded

# Test payment failed
stripe trigger invoice.payment_failed
```

### Monitor Webhook Events

The `stripe listen` command will show you all webhook events in real-time:
```
2025-01-20 10:30:45  --> customer.subscription.created [evt_xxx]
2025-01-20 10:30:45  <-- [200] POST http://localhost:8000/api/webhooks/stripe [evt_xxx]
```

## Useful Commands

### View Recent Events
```bash
stripe events list
```

### View Specific Event
```bash
stripe events retrieve evt_xxxxxxxxxxxxx
```

### Test Full Subscription Flow
```bash
# Create a test customer
stripe customers create --email test@example.com

# Create a test subscription
stripe subscriptions create \
  --customer cus_xxxxxxxxxxxxx \
  --items[0][price]=price_xxxxxxxxxxxxx

# This will automatically trigger webhooks
```

## Development Workflow

1. **Start your Laravel server:**
   ```bash
   php artisan serve
   # or if using Herd, it's already running
   ```

2. **Start Stripe CLI webhook forwarding:**
   ```bash
   stripe listen --forward-to localhost:8000/api/webhooks/stripe
   ```

3. **Update your .env with the webhook secret** from step 2

4. **Test subscriptions** through your app - webhooks will be forwarded automatically

5. **Check Laravel logs** to see webhook processing:
   ```bash
   tail -f storage/logs/laravel.log | grep -i stripe
   ```

## Troubleshooting

### Webhook Not Received
- Make sure Laravel server is running
- Check the webhook URL is correct
- Verify `STRIPE_WEBHOOK_SECRET` is set in `.env`
- Check Laravel logs for errors

### Webhook Signature Verification Failed
- Make sure you're using the webhook secret from `stripe listen` output
- Clear config cache: `php artisan config:clear`
- Restart your Laravel server

### Connection Refused
- Ensure Laravel server is running on the correct port
- Check firewall settings
- Try using `127.0.0.1` instead of `localhost`

## Production Webhook Setup

For production, you'll need to:
1. Create a webhook endpoint in Stripe Dashboard
2. Use your production URL: `https://yourdomain.com/api/webhooks/stripe`
3. Select the 6 required events (see STRIPE_WEBHOOK_SETUP.md)
4. Use the production webhook signing secret in your production `.env`

