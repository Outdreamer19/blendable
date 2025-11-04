import { test, expect } from '@playwright/test';

test.describe('Simple Chat Test', () => {
  test('should load chat page and verify basic functionality', async ({ page }) => {
    // Login first
    await page.goto('/login');
    await page.fill('#email', 'demo@omni-ai.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
    
    // Navigate to the chat page
    await page.goto('/chats');
    
    // Wait for the page to load
    await page.waitForLoadState('networkidle');
    
    // Check if the chat interface is visible
    const chatInput = page.locator('textarea[placeholder*="Start typing"]');
    await expect(chatInput).toBeVisible();
    
    // Check if send button is present (the one with p-2 class is the send button)
    const sendButton = page.locator('button.p-2.bg-blue-600');
    await expect(sendButton).toBeVisible();
    
    // Verify the send button is initially disabled (empty input)
    await expect(sendButton).toBeDisabled();
    
    // Type a test message
    await chatInput.fill('Hello, this is a test message.');
    
    // Verify the send button is now enabled
    await expect(sendButton).toBeEnabled();
    
    console.log('✅ Basic chat interface test passed');
  });

  test('should handle login and navigate to chat', async ({ page }) => {
    // Try to access chat page without login
    await page.goto('/chats');
    
    // Should redirect to login page
    await expect(page).toHaveURL(/.*login/);
    
    // Fill login form
    await page.fill('#email', 'demo@omni-ai.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Should redirect to dashboard or chat page
    await page.waitForURL(/.*(dashboard|chats)/);
    
    console.log('✅ Login and navigation test passed');
  });
});
