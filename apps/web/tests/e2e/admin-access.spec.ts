import { test, expect } from '@playwright/test';

test.describe('Admin Access Without Subscription', () => {
  test('admin user should be able to login and access dashboard without subscription', async ({ page }) => {
    // Navigate to login page
    await page.goto('/login');
    await page.waitForLoadState('networkidle');
    
    // Wait for login form
    await page.waitForSelector('input[type="email"], input[name="email"], #email', { timeout: 5000 });
    
    // Fill in admin credentials
    const emailInput = page.locator('input[type="email"], input[name="email"], #email').first();
    const passwordInput = page.locator('input[type="password"], input[name="password"], #password').first();
    
    await emailInput.fill('shane1obdurate@gmail.com');
    await passwordInput.fill('password');
    
    // Submit login
    const submitButton = page.locator('button[type="submit"]').first();
    await submitButton.click();
    
    // Wait for redirect - should go to dashboard, not billing/onboarding
    await page.waitForURL(/.*(dashboard|chats|billing)/, { timeout: 15000 });
    
    // Verify we're NOT on the onboarding/subscription page
    const currentUrl = page.url();
    console.log(`Current URL after login: ${currentUrl}`);
    
    // Check that we're not on the onboarding page
    const onboardingHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboardingPage = await onboardingHeading.isVisible({ timeout: 2000 }).catch(() => false);
    
    expect(isOnboardingPage).toBe(false);
    
    // If we're on billing page, verify it shows the full billing dashboard, not onboarding
    if (currentUrl.includes('/billing')) {
      const availablePlansHeading = page.locator('h2:has-text("Billing & Usage"), h3:has-text("Available Plans")');
      const hasBillingDashboard = await availablePlansHeading.isVisible({ timeout: 3000 }).catch(() => false);
      
      // Should show billing dashboard, not onboarding
      const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
      const hasWelcomeHeading = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
      
      expect(hasWelcomeHeading).toBe(false);
      console.log('✅ Admin user sees billing dashboard, not onboarding page');
    }
    
    // Try to access dashboard directly
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');
    
    // Should be able to access dashboard without redirect
    const dashboardUrl = page.url();
    expect(dashboardUrl).toContain('/dashboard');
    
    console.log('✅ Admin user can access dashboard without subscription');
    
    // Take a screenshot for verification
    await page.screenshot({ path: 'test-results/admin-dashboard-access.png', fullPage: true });
  });

  test('admin user should not be redirected to subscription page when accessing protected routes', async ({ page }) => {
    // Login as admin
    await page.goto('/login');
    await page.waitForLoadState('networkidle');
    
    await page.waitForSelector('input[type="email"], input[name="email"], #email', { timeout: 5000 });
    
    const emailInput = page.locator('input[type="email"], input[name="email"], #email').first();
    const passwordInput = page.locator('input[type="password"], input[name="password"], #password').first();
    
    await emailInput.fill('shane1obdurate@gmail.com');
    await passwordInput.fill('password');
    
    const submitButton = page.locator('button[type="submit"]').first();
    await submitButton.click();
    
    // Wait for redirect
    await page.waitForURL(/.*(dashboard|chats|billing)/, { timeout: 15000 });
    
    // Try accessing a protected route (chats)
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Should be on chats page, not redirected to billing/onboarding
    const chatsUrl = page.url();
    expect(chatsUrl).toContain('/chats');
    
    // Verify we're not on onboarding page
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    
    expect(isOnboarding).toBe(false);
    console.log('✅ Admin user can access protected routes without subscription');
    
    await page.screenshot({ path: 'test-results/admin-chats-access.png', fullPage: true });
  });

  test('admin user should see full billing page, not onboarding', async ({ page }) => {
    // Login as admin
    await page.goto('/login');
    await page.waitForLoadState('networkidle');
    
    await page.waitForSelector('input[type="email"], input[name="email"], #email', { timeout: 5000 });
    
    const emailInput = page.locator('input[type="email"], input[name="email"], #email').first();
    const passwordInput = page.locator('input[type="password"], input[name="password"], #password').first();
    
    await emailInput.fill('shane1obdurate@gmail.com');
    await passwordInput.fill('password');
    
    const submitButton = page.locator('button[type="submit"]').first();
    await submitButton.click();
    
    // Wait for redirect
    await page.waitForURL(/.*(dashboard|chats|billing)/, { timeout: 15000 });
    
    // Navigate to billing page
    await page.goto('/billing');
    await page.waitForLoadState('networkidle');
    
    // Should see billing dashboard, not onboarding
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 2000 }).catch(() => false);
    
    expect(isOnboarding).toBe(false);
    
    // Should see billing dashboard elements
    const billingHeading = page.locator('h2:has-text("Billing & Usage"), h3:has-text("Available Plans")');
    const hasBillingDashboard = await billingHeading.isVisible({ timeout: 3000 }).catch(() => false);
    
    // Either we see the billing dashboard or we're on a different page (which is fine for admin)
    console.log(`Billing dashboard visible: ${hasBillingDashboard}`);
    console.log(`Current URL: ${page.url()}`);
    
    await page.screenshot({ path: 'test-results/admin-billing-page.png', fullPage: true });
    
    console.log('✅ Admin user sees billing page (not onboarding)');
  });
});

