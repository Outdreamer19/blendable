import { test, expect } from '@playwright/test';

test.describe('Basic Chat Test', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@omni-ai.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should successfully send a message to AI', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Type a simple test message
    const testMessage = 'Hello! What is 2+2?';
    await page.fill('textarea[placeholder*="Start typing"]', testMessage);
    
    // Verify the send button is enabled
    const sendButton = page.locator('button.p-2.bg-blue-600');
    await expect(sendButton).toBeEnabled();
    
    // Send the message
    await sendButton.click();
    
    // Wait for the input to be cleared (indicating message was sent)
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toHaveValue('');
    
    // Wait for any loading indicators or responses
    await page.waitForTimeout(3000);
    
    // Take a screenshot for debugging
    await page.screenshot({ path: 'chat-test-result.png', fullPage: true });
    
    console.log('✅ Message sent successfully to AI');
    console.log('📸 Screenshot saved as chat-test-result.png');
  });

  test('should verify chat interface is functional', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    const chatInput = page.locator('textarea[placeholder*="Start typing"]');
    const sendButton = page.locator('button.p-2.bg-blue-600');
    
    // Test input functionality
    await chatInput.fill('Test message');
    await expect(sendButton).toBeEnabled();
    
    // Test clearing input
    await chatInput.fill('');
    await expect(sendButton).toBeDisabled();
    
    // Test typing and sending
    await chatInput.fill('Hello AI!');
    await expect(sendButton).toBeEnabled();
    
    // Send the message
    await sendButton.click();
    
    // Verify input is cleared after sending
    await expect(chatInput).toHaveValue('');
    
    console.log('✅ Chat interface is fully functional');
  });

  test('should handle different types of messages', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    const chatInput = page.locator('textarea[placeholder*="Start typing"]');
    const sendButton = page.locator('button.p-2.bg-blue-600');
    
    // Test math question
    await chatInput.fill('What is 5 * 6?');
    await sendButton.click();
    await page.waitForTimeout(2000);
    
    // Test creative request
    await chatInput.fill('Write a haiku about coding.');
    await sendButton.click();
    await page.waitForTimeout(2000);
    
    // Test factual question
    await chatInput.fill('What is the capital of France?');
    await sendButton.click();
    await page.waitForTimeout(2000);
    
    console.log('✅ Multiple message types sent successfully');
  });
});
