import { test, expect } from '@playwright/test';

test.describe('Billing Functionality', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should display billing page', async ({ page }) => {
    await page.goto('/billing');
    
    // Check if the billing page is visible
    await expect(page.locator('h1')).toContainText('Billing');
    
    // Check if subscription status is displayed
    await expect(page.locator('[data-testid="subscription-status"]')).toBeVisible();
    
    // Check if usage information is displayed
    await expect(page.locator('[data-testid="usage-info"]')).toBeVisible();
  });

  test('should display pricing plans', async ({ page }) => {
    await page.goto('/billing');
    
    // Check if pricing plans are visible
    await expect(page.locator('[data-testid="pricing-plans"]')).toBeVisible();
    
    // Check if all three plans are displayed
    await expect(page.locator('[data-testid="plan-starter"]')).toBeVisible();
    await expect(page.locator('[data-testid="plan-pro"]')).toBeVisible();
    await expect(page.locator('[data-testid="plan-enterprise"]')).toBeVisible();
  });

  test('should allow plan selection', async ({ page }) => {
    await page.goto('/billing');
    
    // Click on the Pro plan
    await page.click('[data-testid="plan-pro"] button');
    
    // Check if we're redirected to Stripe checkout
    await expect(page).toHaveURL(/.*stripe.*checkout/);
  });

  test('should display usage statistics', async ({ page }) => {
    await page.goto('/billing');
    
    // Check if usage statistics are displayed
    await expect(page.locator('[data-testid="usage-stats"]')).toBeVisible();
    
    // Check if words used is displayed
    await expect(page.locator('[data-testid="words-used"]')).toBeVisible();
    
    // Check if API calls used is displayed
    await expect(page.locator('[data-testid="api-calls-used"]')).toBeVisible();
  });

  test('should display monthly/yearly toggle on billing page', async ({ page }) => {
    await page.goto('/billing');
    
    // Wait for the page to load
    await page.waitForLoadState('networkidle');
    
    // Check if "Available Plans" heading is visible
    await expect(page.locator('text=Available Plans')).toBeVisible();
    
    // Check if Monthly button is visible
    const monthlyButton = page.locator('button:has-text("Monthly")');
    await expect(monthlyButton).toBeVisible();
    
    // Check if Yearly button is visible
    const yearlyButton = page.locator('button:has-text("Yearly")');
    await expect(yearlyButton).toBeVisible();
    
    // Check if toggle container is visible
    const toggleContainer = page.locator('text=Monthly').locator('..').locator('..');
    await expect(toggleContainer).toBeVisible();
    
    // Take a screenshot for debugging
    await page.screenshot({ path: 'test-results/billing-toggle-test.png', fullPage: true });
    
    // Test clicking the toggle
    await yearlyButton.click();
    
    // Check if "30% off" badge appears when yearly is selected
    await expect(page.locator('text=30% off')).toBeVisible({ timeout: 2000 });
    
    // Click back to monthly
    await monthlyButton.click();
    
    // Verify "30% off" is hidden
    await expect(page.locator('text=30% off')).not.toBeVisible({ timeout: 1000 });
  });
});
