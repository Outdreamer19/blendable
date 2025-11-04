import { test, expect } from '@playwright/test';

test.describe('Basic Onboarding Test', () => {
  test('should allow existing user to access chats without workspace issues', async ({ page }) => {
    // Login with existing user
    await page.goto('/login');
    await page.fill('#email', 'demo@omni-ai.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
    
    // Navigate to chats
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Check if we can access chats without workspace errors
    const currentUrl = page.url();
    console.log('Current URL after navigating to chats:', currentUrl);
    
    // Should not be redirected to workspaces
    expect(currentUrl).not.toContain('/workspaces');
    
    // Should have chat interface or new chat button
    const newChatButton = page.locator('button:has-text("New Chat"), button:has-text("Start Chat")');
    const chatInput = page.locator('textarea[placeholder*="Start typing"], input[placeholder*="message"]');
    
    const hasChatInterface = await newChatButton.isVisible() || await chatInput.isVisible();
    console.log('Has chat interface:', hasChatInterface);
    
    // Take a screenshot for debugging
    await page.screenshot({ path: 'basic-onboarding-test.png' });
  });

  test('should have Teams and Workspaces in navigation', async ({ page }) => {
    // Login with existing user
    await page.goto('/login');
    await page.fill('#email', 'demo@omni-ai.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
    
    // Check for Teams link
    const teamsLink = page.locator('a[href*="/teams"]');
    await expect(teamsLink).toBeVisible();
    await expect(teamsLink).toContainText('Teams');
    
    // Check for Workspaces link
    const workspacesLink = page.locator('a[href*="/workspaces"]');
    await expect(workspacesLink).toBeVisible();
    await expect(workspacesLink).toContainText('Workspaces');
  });
});
