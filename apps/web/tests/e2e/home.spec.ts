import { test, expect } from '@playwright/test';

test.describe('Home Page', () => {
  test('should display the home page', async ({ page }) => {
    await page.goto('/');
    
    // Check if the page title is correct
    await expect(page).toHaveTitle(/Omni-AI/);
    
    // Check if the main heading is visible
    await expect(page.locator('h1')).toContainText('Omni-AI');
    
    // Check if the navigation is visible
    await expect(page.locator('nav')).toBeVisible();
  });

  test('should navigate to pricing page', async ({ page }) => {
    await page.goto('/');
    
    // Click on the pricing link
    await page.click('a[href="/pricing"]');
    
    // Check if we're on the pricing page
    await expect(page).toHaveURL(/.*pricing/);
    await expect(page.locator('h1')).toContainText('Pricing');
  });

  test('should navigate to login page', async ({ page }) => {
    await page.goto('/');
    
    // Click on the login link
    await page.click('a[href="/login"]');
    
    // Check if we're on the login page
    await expect(page).toHaveURL(/.*login/);
    await expect(page.locator('h1')).toContainText('Login');
  });
});
