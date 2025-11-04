import { test, expect } from '@playwright/test';

test.describe('Personas', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should display personas page', async ({ page }) => {
    await page.goto('/personas');
    
    // Check if the personas page is visible
    await expect(page.locator('h1')).toContainText('Personas');
    
    // Check if the personas list is visible
    await expect(page.locator('[data-testid="personas-list"]')).toBeVisible();
  });

  test('should create a new persona', async ({ page }) => {
    await page.goto('/personas');
    
    // Click on "Create Persona" button
    await page.click('button:has-text("Create Persona")');
    
    // Fill in persona details
    await page.fill('input[name="name"]', 'Test Persona');
    await page.fill('input[name="slug"]', 'test-persona');
    await page.fill('textarea[name="description"]', 'A test persona for e2e testing');
    await page.fill('textarea[name="system_prompt"]', 'You are a test persona.');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the new persona is created
    await expect(page.locator('[data-testid="personas-list"]')).toContainText('Test Persona');
  });

  test('should edit an existing persona', async ({ page }) => {
    await page.goto('/personas');
    
    // Click on the first persona
    await page.click('[data-testid="persona-item"]:first-child');
    
    // Click on edit button
    await page.click('button:has-text("Edit")');
    
    // Update the persona name
    await page.fill('input[name="name"]', 'Updated Test Persona');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the persona is updated
    await expect(page.locator('[data-testid="personas-list"]')).toContainText('Updated Test Persona');
  });

  test('should attach knowledge to a persona', async ({ page }) => {
    await page.goto('/personas');
    
    // Click on the first persona
    await page.click('[data-testid="persona-item"]:first-child');
    
    // Click on knowledge tab
    await page.click('[data-testid="knowledge-tab"]');
    
    // Click on "Attach Knowledge" button
    await page.click('button:has-text("Attach Knowledge")');
    
    // Select a knowledge item
    await page.click('[data-testid="knowledge-item"]:first-child');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the knowledge is attached
    await expect(page.locator('[data-testid="attached-knowledge"]')).toBeVisible();
  });
});
