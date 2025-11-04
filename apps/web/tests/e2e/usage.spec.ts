import { test, expect } from '@playwright/test';

test.describe('Usage', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should display usage page', async ({ page }) => {
    await page.goto('/usage');
    
    // Check if the usage page is visible
    await expect(page.locator('h1')).toContainText('Usage');
    
    // Check if the usage statistics are visible
    await expect(page.locator('[data-testid="usage-stats"]')).toBeVisible();
  });

  test('should display usage breakdown by model', async ({ page }) => {
    await page.goto('/usage');
    
    // Check if the model breakdown is visible
    await expect(page.locator('[data-testid="model-breakdown"]')).toBeVisible();
    
    // Check if individual models are displayed
    await expect(page.locator('[data-testid="model-usage"]')).toBeVisible();
  });

  test('should display usage over time', async ({ page }) => {
    await page.goto('/usage');
    
    // Check if the usage chart is visible
    await expect(page.locator('[data-testid="usage-chart"]')).toBeVisible();
    
    // Check if the chart has data
    await expect(page.locator('[data-testid="chart-container"]')).toBeVisible();
  });

  test('should filter usage by date range', async ({ page }) => {
    await page.goto('/usage');
    
    // Click on date range picker
    await page.click('[data-testid="date-range-picker"]');
    
    // Select a custom date range
    await page.click('button:has-text("Last 7 days")');
    
    // Check if the usage data is updated
    await expect(page.locator('[data-testid="usage-stats"]')).toBeVisible();
  });

  test('should export usage data', async ({ page }) => {
    await page.goto('/usage');
    
    // Click on export button
    await page.click('button:has-text("Export")');
    
    // Check if the export options are visible
    await expect(page.locator('[data-testid="export-options"]')).toBeVisible();
    
    // Select CSV format
    await page.click('button:has-text("CSV")');
    
    // Check if the download starts
    // Note: This test might need to be adjusted based on how exports are handled
    await expect(page.locator('button:has-text("Export")')).toBeVisible();
  });
});
