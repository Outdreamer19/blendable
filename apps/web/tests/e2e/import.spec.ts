import { test, expect } from '@playwright/test';

test.describe('Import', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should display import page', async ({ page }) => {
    await page.goto('/import');
    
    // Check if the import page is visible
    await expect(page.locator('h1')).toContainText('Import');
    
    // Check if the import form is visible
    await expect(page.locator('[data-testid="import-form"]')).toBeVisible();
  });

  test('should import ChatGPT data', async ({ page }) => {
    await page.goto('/import');
    
    // Select ChatGPT as the import type
    await page.selectOption('select[name="type"]', 'chatgpt');
    
    // Select a workspace
    await page.selectOption('select[name="workspace_id"]', '1');
    
    // Upload a file
    const fileInput = page.locator('input[type="file"]');
    await fileInput.setInputFiles('tests/fixtures/chatgpt-export.json');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the import is successful
    await expect(page.locator('[data-testid="import-success"]')).toBeVisible();
  });

  test('should import Claude data', async ({ page }) => {
    await page.goto('/import');
    
    // Select Claude as the import type
    await page.selectOption('select[name="type"]', 'claude');
    
    // Select a workspace
    await page.selectOption('select[name="workspace_id"]', '1');
    
    // Upload a file
    const fileInput = page.locator('input[type="file"]');
    await fileInput.setInputFiles('tests/fixtures/claude-export.json');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the import is successful
    await expect(page.locator('[data-testid="import-success"]')).toBeVisible();
  });

  test('should validate file format', async ({ page }) => {
    await page.goto('/import');
    
    // Select ChatGPT as the import type
    await page.selectOption('select[name="type"]', 'chatgpt');
    
    // Select a workspace
    await page.selectOption('select[name="workspace_id"]', '1');
    
    // Upload an invalid file
    const fileInput = page.locator('input[type="file"]');
    await fileInput.setInputFiles('tests/fixtures/invalid-file.txt');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if validation error is displayed
    await expect(page.locator('[data-testid="validation-error"]')).toBeVisible();
  });

  test('should display import progress', async ({ page }) => {
    await page.goto('/import');
    
    // Select ChatGPT as the import type
    await page.selectOption('select[name="type"]', 'chatgpt');
    
    // Select a workspace
    await page.selectOption('select[name="workspace_id"]', '1');
    
    // Upload a file
    const fileInput = page.locator('input[type="file"]');
    await fileInput.setInputFiles('tests/fixtures/chatgpt-export.json');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if progress indicator is visible
    await expect(page.locator('[data-testid="import-progress"]')).toBeVisible();
  });
});
