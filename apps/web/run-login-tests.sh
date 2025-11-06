#!/bin/bash

# Login Page Test Runner
# This script runs the Playwright tests for the login page with animated gradient background

echo "🚀 Starting Login Page Tests..."
echo "==============================="

# Check if the Laravel server is running
if ! curl -s http://127.0.0.1:8002 > /dev/null; then
    echo "❌ Laravel server is not running on port 8002"
    echo "Please start the server with: php artisan serve --port=8002"
    exit 1
fi

echo "✅ Laravel server is running on port 8002"

# Check if we have the demo user
echo "🔍 Checking for demo user..."
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'demo@blendable.com')->first();
if (\$user) {
    echo '✅ Demo user exists: ' . \$user->email;
} else {
    echo '❌ Demo user not found. Creating demo user...';
    \$user = App\Models\User::create([
        'name' => 'Demo User',
        'email' => 'demo@blendable.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);
    echo '✅ Demo user created: ' . \$user->email;
}
"

echo ""
echo "🧪 Running Login Page Tests..."
echo "=============================="

# Run the login page tests
echo "📋 Testing Login Page Functionality..."
npx playwright test tests/e2e/login-page.spec.ts --headed --timeout=60000

echo ""
echo "🎨 Testing Animated Gradient Background..."
npx playwright test tests/e2e/gradient-background.spec.ts --headed --timeout=60000

echo ""
echo "📊 Test Results Summary"
echo "======================="
echo "Check the HTML report in: playwright-report/index.html"
echo ""

# Optional: Run with different browsers
read -p "Would you like to run tests on all browsers? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🌐 Running tests on all browsers..."
    npx playwright test tests/e2e/login-page.spec.ts --project=chromium --project=firefox --project=webkit
    npx playwright test tests/e2e/gradient-background.spec.ts --project=chromium --project=firefox --project=webkit
fi

echo "✅ Login page tests completed!"
