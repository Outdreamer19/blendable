import { test, expect } from '@playwright/test';

test.describe('Prompts', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should display prompts page', async ({ page }) => {
    await page.goto('/prompts');
    
    // Check if the prompts page is visible
    await expect(page.locator('h1')).toContainText('Prompts');
    
    // Check if the prompts list is visible
    await expect(page.locator('[data-testid="prompts-list"]')).toBeVisible();
  });

  test('should create a new prompt', async ({ page }) => {
    await page.goto('/prompts');
    
    // Click on "Create Prompt" button
    await page.click('button:has-text("Create Prompt")');
    
    // Fill in prompt details
    await page.fill('input[name="name"]', 'Test Prompt');
    await page.fill('textarea[name="description"]', 'A test prompt for e2e testing');
    await page.fill('textarea[name="content"]', 'This is a test prompt: {{variable}}');
    await page.fill('input[name="tags"]', 'test, e2e');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the new prompt is created
    await expect(page.locator('[data-testid="prompts-list"]')).toContainText('Test Prompt');
  });

  test('should edit an existing prompt', async ({ page }) => {
    await page.goto('/prompts');
    
    // Click on the first prompt
    await page.click('[data-testid="prompt-item"]:first-child');
    
    // Click on edit button
    await page.click('button:has-text("Edit")');
    
    // Update the prompt name
    await page.fill('input[name="name"]', 'Updated Test Prompt');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the prompt is updated
    await expect(page.locator('[data-testid="prompts-list"]')).toContainText('Updated Test Prompt');
  });

  test('should use a prompt in chat', async ({ page }) => {
    await page.goto('/prompts');
    
    // Click on the first prompt
    await page.click('[data-testid="prompt-item"]:first-child');
    
    // Click on "Use in Chat" button
    await page.click('button:has-text("Use in Chat")');
    
    // Check if we're redirected to chat page
    await expect(page).toHaveURL(/.*chat/);
    
    // Check if the prompt content is in the message input
    await expect(page.locator('[data-testid="message-input"]')).not.toBeEmpty();
  });

  test('should search prompts', async ({ page }) => {
    await page.goto('/prompts');
    
    // Type in the search box
    await page.fill('[data-testid="search-input"]', 'test');
    
    // Check if the search results are filtered
    await expect(page.locator('[data-testid="prompts-list"]')).toContainText('test');
  });
});
