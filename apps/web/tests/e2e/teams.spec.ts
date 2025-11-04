import { test, expect } from '@playwright/test';

test.describe('Teams and Workspaces', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should display teams page', async ({ page }) => {
    await page.goto('/teams');
    
    // Check if the teams page is visible
    await expect(page.locator('h1')).toContainText('Teams');
    
    // Check if the current team is displayed
    await expect(page.locator('[data-testid="current-team"]')).toBeVisible();
  });

  test('should create a new team', async ({ page }) => {
    await page.goto('/teams');
    
    // Click on "Create Team" button
    await page.click('button:has-text("Create Team")');
    
    // Fill in team details
    await page.fill('input[name="name"]', 'Test Team');
    await page.fill('textarea[name="description"]', 'A test team for e2e testing');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the new team is created
    await expect(page.locator('[data-testid="team-list"]')).toContainText('Test Team');
  });

  test('should display workspaces page', async ({ page }) => {
    await page.goto('/workspaces');
    
    // Check if the workspaces page is visible
    await expect(page.locator('h1')).toContainText('Workspaces');
    
    // Check if the current workspace is displayed
    await expect(page.locator('[data-testid="current-workspace"]')).toBeVisible();
  });

  test('should create a new workspace', async ({ page }) => {
    await page.goto('/workspaces');
    
    // Click on "Create Workspace" button
    await page.click('button:has-text("Create Workspace")');
    
    // Fill in workspace details
    await page.fill('input[name="name"]', 'Test Workspace');
    await page.fill('textarea[name="description"]', 'A test workspace for e2e testing');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Check if the new workspace is created
    await expect(page.locator('[data-testid="workspace-list"]')).toContainText('Test Workspace');
  });

  test('should switch between workspaces', async ({ page }) => {
    await page.goto('/workspaces');
    
    // Get the current workspace
    const currentWorkspace = await page.locator('[data-testid="current-workspace"]').textContent();
    
    // Click on a different workspace
    await page.click('[data-testid="workspace-item"]:not(:has-text("' + currentWorkspace + '"))');
    
    // Check if the workspace has changed
    await expect(page.locator('[data-testid="current-workspace"]')).not.toContainText(currentWorkspace);
  });
});
