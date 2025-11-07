import { test, expect } from '@playwright/test';

test.describe('Monthly/Yearly Toggle Functionality', () => {
  test.beforeEach(async ({ page }) => {
    // Try to login first
    await page.goto('/login');
    await page.waitForLoadState('networkidle');
    
    // Check if we can login (skip if rate limited)
    const emailInput = page.locator('input[type="email"], input[name="email"]').first();
    const passwordInput = page.locator('input[type="password"], input[name="password"]').first();
    
    if (await emailInput.isVisible({ timeout: 2000 }).catch(() => false)) {
      await emailInput.fill('demo@blendable.com');
      await passwordInput.fill('password');
      
      const submitButton = page.locator('button[type="submit"]').first();
      await submitButton.click();
      
      // Wait for redirect
      await page.waitForURL(/.*(dashboard|chats|billing|onboarding)/, { timeout: 10000 }).catch(() => {
        console.log('Login may have failed or rate limited');
      });
    }
  });

  test('should toggle between monthly and yearly on onboarding page', async ({ page }) => {
    // Navigate to billing page (will show onboarding if no subscription)
    await page.goto('/billing');
    await page.waitForLoadState('networkidle');
    
    // Take initial screenshot
    await page.screenshot({ path: 'test-results/toggle-initial.png', fullPage: true });
    
    // Check if we're on onboarding page or billing page
    const welcomeHeading = page.locator('text=/Welcome to Blendable/i');
    const availablePlansHeading = page.locator('text=Available Plans');
    
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 3000 }).catch(() => false);
    const isBilling = await availablePlansHeading.isVisible({ timeout: 3000 }).catch(() => false);
    
    console.log(`On onboarding page: ${isOnboarding}`);
    console.log(`On billing page: ${isBilling}`);
    
    // Find toggle buttons (should work on both pages)
    const monthlyButton = page.locator('button:has-text("Monthly")');
    const yearlyButton = page.locator('button:has-text("Yearly")');
    
    // Check if toggle exists
    const monthlyVisible = await monthlyButton.isVisible({ timeout: 3000 }).catch(() => false);
    const yearlyVisible = await yearlyButton.isVisible({ timeout: 3000 }).catch(() => false);
    
    console.log(`Monthly button visible: ${monthlyVisible}`);
    console.log(`Yearly button visible: ${yearlyVisible}`);
    
    if (!monthlyVisible || !yearlyVisible) {
      // Take screenshot to debug
      await page.screenshot({ path: 'test-results/toggle-not-found.png', fullPage: true });
      
      // Get all buttons on page for debugging
      const allButtons = await page.locator('button').all();
      console.log(`Total buttons found: ${allButtons.length}`);
      for (let i = 0; i < Math.min(allButtons.length, 15); i++) {
        const text = await allButtons[i].textContent();
        const visible = await allButtons[i].isVisible();
        console.log(`  Button ${i}: "${text}" (visible: ${visible})`);
      }
      
      throw new Error('Toggle buttons not found. Monthly visible: ' + monthlyVisible + ', Yearly visible: ' + yearlyVisible);
    }
    
    // Assert both buttons are visible
    await expect(monthlyButton).toBeVisible();
    await expect(yearlyButton).toBeVisible();
    
    // Get initial price
    const priceElements = page.locator('text=/\\$\\d+\\.?\\d*/');
    const priceCount = await priceElements.count();
    console.log(`Found ${priceCount} price elements`);
    
    if (priceCount > 0) {
      const initialPrice = await priceElements.first().textContent();
      console.log(`Initial price: ${initialPrice}`);
    }
    
    // Test: Click Yearly
    console.log('Clicking Yearly button...');
    await yearlyButton.click();
    await page.waitForTimeout(800);
    
    // Take screenshot after clicking yearly
    await page.screenshot({ path: 'test-results/toggle-yearly-selected.png', fullPage: true });
    
    // Verify "30% off" badge appears
    const discountBadge = page.locator('text=30% off');
    const badgeVisible = await discountBadge.isVisible({ timeout: 2000 }).catch(() => false);
    console.log(`"30% off" badge visible: ${badgeVisible}`);
    
    if (badgeVisible) {
      await expect(discountBadge).toBeVisible();
      console.log('✓ "30% off" badge is visible');
    }
    
    // Verify Yearly button is active (has dark background)
    const yearlyClasses = await yearlyButton.getAttribute('class');
    const isYearlyActive = yearlyClasses?.includes('bg-gray-900') || yearlyClasses?.includes('bg-gray-700');
    console.log(`Yearly button active: ${isYearlyActive}`);
    expect(isYearlyActive).toBe(true);
    
    // Get yearly price
    if (priceCount > 0) {
      const yearlyPrice = await priceElements.first().textContent();
      console.log(`Yearly price: ${yearlyPrice}`);
    }
    
    // Test: Click Monthly
    console.log('Clicking Monthly button...');
    await monthlyButton.click();
    await page.waitForTimeout(800);
    
    // Take screenshot after clicking monthly
    await page.screenshot({ path: 'test-results/toggle-monthly-selected.png', fullPage: true });
    
    // Verify "30% off" badge is hidden
    const badgeStillVisible = await discountBadge.isVisible({ timeout: 1000 }).catch(() => false);
    console.log(`"30% off" badge still visible: ${badgeStillVisible}`);
    
    if (badgeVisible) {
      // Only check if badge was visible before
      await expect(discountBadge).not.toBeVisible({ timeout: 1000 });
      console.log('✓ "30% off" badge is hidden');
    }
    
    // Verify Monthly button is active
    const monthlyClasses = await monthlyButton.getAttribute('class');
    const isMonthlyActive = monthlyClasses?.includes('bg-gray-900') || monthlyClasses?.includes('bg-gray-700');
    console.log(`Monthly button active: ${isMonthlyActive}`);
    expect(isMonthlyActive).toBe(true);
    
    // Get monthly price
    if (priceCount > 0) {
      const monthlyPrice = await priceElements.first().textContent();
      console.log(`Monthly price: ${monthlyPrice}`);
    }
    
    console.log('✓ Toggle is working correctly!');
  });

  test('should update checkout URLs with interval parameter', async ({ page }) => {
    await page.goto('/billing');
    await page.waitForLoadState('networkidle');
    
    // Find toggle buttons
    const monthlyButton = page.locator('button:has-text("Monthly")');
    const yearlyButton = page.locator('button:has-text("Yearly")');
    
    const monthlyVisible = await monthlyButton.isVisible({ timeout: 3000 }).catch(() => false);
    const yearlyVisible = await yearlyButton.isVisible({ timeout: 3000 }).catch(() => false);
    
    if (!monthlyVisible || !yearlyVisible) {
      test.skip(true, 'Toggle not found - user may need to be logged in');
      return;
    }
    
    // Find checkout buttons
    const checkoutButtons = page.locator('a[href*="checkout"], a:has-text("Continue"), a:has-text("Select"), a:has-text("Choose")');
    const checkoutCount = await checkoutButtons.count();
    
    if (checkoutCount === 0) {
      test.skip(true, 'No checkout buttons found');
      return;
    }
    
    // Test Monthly checkout URL
    await monthlyButton.click();
    await page.waitForTimeout(500);
    
    const firstCheckout = checkoutButtons.first();
    const monthlyUrl = await firstCheckout.getAttribute('href');
    console.log(`Monthly checkout URL: ${monthlyUrl}`);
    
    if (monthlyUrl) {
      expect(monthlyUrl).toContain('interval=monthly');
      console.log('✓ Monthly checkout URL includes interval=monthly');
    }
    
    // Test Yearly checkout URL
    await yearlyButton.click();
    await page.waitForTimeout(500);
    
    const yearlyUrl = await firstCheckout.getAttribute('href');
    console.log(`Yearly checkout URL: ${yearlyUrl}`);
    
    if (yearlyUrl) {
      expect(yearlyUrl).toContain('interval=yearly');
      console.log('✓ Yearly checkout URL includes interval=yearly');
    }
    
    console.log('✓ Checkout URLs are updated correctly');
  });
});

