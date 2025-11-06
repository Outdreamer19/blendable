import { test, expect } from '@playwright/test';

test.describe('Improved Workspace Flow', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@blendable.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should have Teams and Workspaces in navigation', async ({ page }) => {
    // Check if Teams and Workspaces links are in the sidebar
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');
    
    // Check for Teams link
    const teamsLink = page.locator('a[href*="/teams"]');
    await expect(teamsLink).toBeVisible();
    await expect(teamsLink).toContainText('Teams');
    
    // Check for Workspaces link
    const workspacesLink = page.locator('a[href*="/workspaces"]');
    await expect(workspacesLink).toBeVisible();
    await expect(workspacesLink).toContainText('Workspaces');
  });

  test('should auto-create default team when creating workspace', async ({ page }) => {
    // Navigate to workspace creation
    await page.goto('/workspaces/create');
    await page.waitForLoadState('networkidle');
    
    // Check if a default team was created and is selected
    const teamSelect = page.locator('select[name="team_id"]');
    await expect(teamSelect).toBeVisible();
    
    // Check if there's a team option selected
    const selectedOption = await teamSelect.inputValue();
    expect(selectedOption).toBeTruthy();
    
    // Fill in workspace details
    await page.fill('input[name="name"]', 'Test Workspace');
    await page.fill('textarea[name="description"]', 'Test workspace description');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Should redirect to workspaces page
    await page.waitForURL(/.*workspaces/);
    
    // Verify workspace was created
    await expect(page.locator('text=Test Workspace')).toBeVisible();
  });

  test('should have Create Team link in workspace creation form', async ({ page }) => {
    // Navigate to workspace creation
    await page.goto('/workspaces/create');
    await page.waitForLoadState('networkidle');
    
    // Check for "Create a new team" link
    const createTeamLink = page.locator('a[href*="/teams/create"]');
    await expect(createTeamLink).toBeVisible();
    await expect(createTeamLink).toContainText('Create a new team');
  });

  test('should allow creating a new team from workspace creation', async ({ page }) => {
    // Navigate to workspace creation
    await page.goto('/workspaces/create');
    await page.waitForLoadState('networkidle');
    
    // Click the "Create a new team" link
    await page.click('a[href*="/teams/create"]');
    
    // Should navigate to team creation page
    await page.waitForURL(/.*teams\/create/);
    
    // Fill in team details
    await page.fill('input[name="name"]', 'My Custom Team');
    await page.fill('textarea[name="description"]', 'A custom team for testing');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Should redirect to team page
    await page.waitForURL(/.*teams\/\d+/);
    
    // Verify team was created
    await expect(page.locator('text=My Custom Team')).toBeVisible();
  });

  test('should allow creating chat after workspace setup', async ({ page }) => {
    // First create a workspace
    await page.goto('/workspaces/create');
    await page.waitForLoadState('networkidle');
    
    // Fill in workspace details
    await page.fill('input[name="name"]', 'Chat Test Workspace');
    await page.fill('textarea[name="description"]', 'Workspace for testing chat creation');
    
    // Submit the form
    await page.click('button[type="submit"]');
    
    // Should redirect to workspaces page
    await page.waitForURL(/.*workspaces/);
    
    // Now navigate to chats
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Should be able to create a new chat
    const newChatButton = page.locator('button:has-text("New Chat"), button:has-text("Start Chat")');
    await expect(newChatButton).toBeVisible();
    
    // Click new chat button
    await newChatButton.click();
    
    // Should not redirect to workspaces (since we have one now)
    await page.waitForTimeout(2000);
    const currentUrl = page.url();
    expect(currentUrl).not.toContain('/workspaces');
  });
});
