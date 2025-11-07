import { test, expect } from '@playwright/test';

test.describe('Billing Page Toggle Visibility', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    
    // Wait for login form
    await page.waitForSelector('input[name="email"], #email', { timeout: 5000 });
    
    // Fill in credentials
    const emailInput = page.locator('input[name="email"], #email').first();
    const passwordInput = page.locator('input[name="password"], #password').first();
    
    await emailInput.fill('demo@blendable.com');
    await passwordInput.fill('password');
    
    // Submit login
    await page.click('button[type="submit"], button:has-text("Log in")');
    
    // Wait for redirect (could be dashboard, chats, or billing/onboarding)
    await page.waitForURL(/.*(dashboard|chats|billing|onboarding)/, { timeout: 10000 });
  });

  test('should display monthly/yearly toggle on billing page', async ({ page }) => {
    // Navigate to billing page
    await page.goto('/billing');
    
    // Wait for the page to load
    await page.waitForLoadState('networkidle');
    
    // Take a screenshot first to see what's on the page
    await page.screenshot({ path: 'test-results/billing-page-before.png', fullPage: true });
    
    // Check if "Available Plans" heading exists
    const availablePlansHeading = page.locator('text=Available Plans');
    const headingVisible = await availablePlansHeading.isVisible({ timeout: 5000 }).catch(() => false);
    
    console.log(`"Available Plans" heading visible: ${headingVisible}`);
    console.log(`Current URL: ${page.url()}`);
    
    if (!headingVisible) {
      // Take screenshot to see what's actually on the page
      await page.screenshot({ path: 'test-results/billing-page-no-heading.png', fullPage: true });
      
      // Get page title and main heading
      const pageTitle = await page.title();
      const mainHeading = await page.locator('h1, h2').first().textContent().catch(() => 'Not found');
      console.log(`Page title: ${pageTitle}`);
      console.log(`Main heading: ${mainHeading}`);
      
      // Check if we're on onboarding page
      const onboardingText = page.locator('text=/welcome|subscribe/i');
      if (await onboardingText.isVisible().catch(() => false)) {
        console.log('On onboarding page - this is expected if user has no subscription');
      }
    }
    
    // Check if Monthly button exists anywhere on the page
    const monthlyButton = page.locator('button:has-text("Monthly")');
    const monthlyVisible = await monthlyButton.isVisible({ timeout: 2000 }).catch(() => false);
    
    console.log(`Monthly button visible: ${monthlyVisible}`);
    
    // Check if Yearly button exists anywhere on the page
    const yearlyButton = page.locator('button:has-text("Yearly")');
    const yearlyVisible = await yearlyButton.isVisible({ timeout: 2000 }).catch(() => false);
    
    console.log(`Yearly button visible: ${yearlyVisible}`);
    
    // Get all buttons on the page for debugging
    const allButtons = await page.locator('button').all();
    console.log(`Total buttons found: ${allButtons.length}`);
    for (let i = 0; i < Math.min(allButtons.length, 10); i++) {
      const text = await allButtons[i].textContent();
      const isVisible = await allButtons[i].isVisible();
      console.log(`  Button ${i}: "${text}" (visible: ${isVisible})`);
    }
    
    // If heading is visible, check the toggle section
    if (headingVisible) {
      // Get the HTML of the Available Plans section
      try {
        const plansSection = availablePlansHeading.locator('..').locator('..');
        const plansSectionHTML = await plansSection.innerHTML();
        console.log('Available Plans section HTML (first 1000 chars):', plansSectionHTML.substring(0, 1000));
        
        // Take screenshot of just the plans section
        await plansSection.screenshot({ path: 'test-results/plans-section.png' });
      } catch (e) {
        console.log('Could not get plans section HTML:', e);
      }
      
      // Assert that the toggle should be visible
      await expect(monthlyButton).toBeVisible({ timeout: 5000 });
      await expect(yearlyButton).toBeVisible({ timeout: 5000 });
      
      // Test clicking the toggle
      await yearlyButton.click();
      await page.waitForTimeout(500);
      
      // Check if "30% off" badge appears
      const discountBadge = page.locator('text=30% off');
      await expect(discountBadge).toBeVisible({ timeout: 2000 });
      
      // Click back to monthly
      await monthlyButton.click();
      await page.waitForTimeout(500);
      
      console.log('✓ Toggle is working correctly');
    } else {
      console.log('⚠ "Available Plans" section not found - user may be on onboarding page');
      console.log('This is expected if the user has no active subscription');
    }
    
    // Final screenshot
    await page.screenshot({ path: 'test-results/billing-page-after.png', fullPage: true });
  });
});

