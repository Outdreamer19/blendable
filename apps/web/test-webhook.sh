#!/bin/bash

# Quick script to test Stripe webhooks locally

echo "🚀 Starting Stripe webhook forwarding..."
echo ""
echo "This will forward webhooks to: http://localhost:8000/api/webhooks/stripe"
echo ""
echo "After this starts, you'll see a webhook signing secret."
echo "Copy it and add to your .env file as: STRIPE_WEBHOOK_SECRET=whsec_..."
echo ""
echo "Press Ctrl+C to stop"
echo ""
echo "=========================================="
echo ""

stripe listen --forward-to localhost:8000/api/webhooks/stripe

