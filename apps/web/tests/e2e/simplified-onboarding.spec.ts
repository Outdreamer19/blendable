import { test, expect } from '@playwright/test';

test.describe('Simplified Onboarding Flow', () => {
  test('should auto-create team and workspace on registration', async ({ page }) => {
    // Go to registration page
    await page.goto('/register');
    await page.waitForLoadState('networkidle');
    
    // Fill in registration form
    const testEmail = `test-${Date.now()}@example.com`;
    const testName = 'Test User';
    
    await page.fill('input[name="name"]', testName);
    await page.fill('input[name="email"]', testEmail);
    await page.fill('input[name="password"]', 'password123');
    await page.fill('input[name="password_confirmation"]', 'password123');
    
    // Submit registration
    await page.click('button[type="submit"]');
    
    // Should redirect to dashboard
    await page.waitForURL(/.*dashboard/);
    
    // Check that user is logged in and has a workspace
    await expect(page.locator('text=Dashboard')).toBeVisible();
    
    // Navigate to chats - should work without workspace issues
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Should be able to create a new chat
    const newChatButton = page.locator('button:has-text("New Chat"), button:has-text("Start Chat")');
    await expect(newChatButton).toBeVisible();
    
    // Click new chat button - should not redirect to workspaces
    await newChatButton.click();
    await page.waitForTimeout(2000);
    
    const currentUrl = page.url();
    expect(currentUrl).not.toContain('/workspaces');
    
    // Check that Teams and Workspaces are available in navigation
    const teamsLink = page.locator('a[href*="/teams"]');
    const workspacesLink = page.locator('a[href*="/workspaces"]');
    
    await expect(teamsLink).toBeVisible();
    await expect(workspacesLink).toBeVisible();
  });

  test('should have personal team and workspace created automatically', async ({ page }) => {
    // Login with existing user
    await page.goto('/login');
    await page.fill('#email', 'demo@blendable.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
    
    // Check teams page
    await page.goto('/teams');
    await page.waitForLoadState('networkidle');
    
    // Should have at least one team
    const teamCards = page.locator('[class*="team"], .bg-white');
    await expect(teamCards.first()).toBeVisible();
    
    // Check workspaces page
    await page.goto('/workspaces');
    await page.waitForLoadState('networkidle');
    
    // Should have at least one workspace
    const workspaceCards = page.locator('[class*="workspace"], .bg-white');
    await expect(workspaceCards.first()).toBeVisible();
  });

  test('should allow creating new chat immediately after registration', async ({ page }) => {
    // Go to registration page
    await page.goto('/register');
    await page.waitForLoadState('networkidle');
    
    // Fill in registration form
    const testEmail = `test-chat-${Date.now()}@example.com`;
    const testName = 'Chat Test User';
    
    await page.fill('input[name="name"]', testName);
    await page.fill('input[name="email"]', testEmail);
    await page.fill('input[name="password"]', 'password123');
    await page.fill('input[name="password_confirmation"]', 'password123');
    
    // Submit registration
    await page.click('button[type="submit"]');
    
    // Should redirect to dashboard
    await page.waitForURL(/.*dashboard/);
    
    // Navigate directly to chats
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Should be able to start chatting immediately
    const chatInput = page.locator('textarea[placeholder*="Start typing"], input[placeholder*="message"], textarea[placeholder*="Type"]');
    const newChatButton = page.locator('button:has-text("New Chat"), button:has-text("Start Chat")');
    
    if (await newChatButton.isVisible()) {
      await newChatButton.click();
      await page.waitForTimeout(1000);
    }
    
    // Should have chat interface available
    const hasChatInterface = await chatInput.isVisible() || await newChatButton.isVisible();
    expect(hasChatInterface).toBeTruthy();
    
    // Should not be redirected to workspaces
    const currentUrl = page.url();
    expect(currentUrl).not.toContain('/workspaces');
  });
});
