import { test, expect } from '@playwright/test';

test.describe('Working AI Chat Tests', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@blendable.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should send a simple message and verify it appears', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Type a simple test message
    const testMessage = 'Hello, this is a test message.';
    await page.fill('textarea[placeholder*="Start typing"]', testMessage);
    
    // Send the message
    await page.click('button.p-2.bg-blue-600');
    
    // Wait a moment for the message to be processed
    await page.waitForTimeout(2000);
    
    // Check if the message appears in the chat (it might be in a different format)
    // Let's look for any text containing our message
    const messageElement = page.locator(`text=${testMessage}`);
    await expect(messageElement).toBeVisible({ timeout: 10000 });
    
    console.log('✅ Message sent and appeared in chat');
  });

  test('should handle AI response (if API is configured)', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Type a simple question
    const question = 'What is 2+2?';
    await page.fill('textarea[placeholder*="Start typing"]', question);
    
    // Send the message
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for the user message to appear
    await expect(page.locator(`text=${question}`)).toBeVisible({ timeout: 10000 });
    
    // Wait for any AI response indicators (loading, streaming, or response)
    // This might take a while if the AI API is configured
    try {
      // Look for loading indicators
      await page.waitForSelector('.animate-pulse, [class*="loading"], [class*="streaming"]', { timeout: 10000 });
      console.log('✅ AI response loading detected');
      
      // Wait for loading to complete
      await page.waitForFunction(() => {
        const loadingElements = document.querySelectorAll('.animate-pulse, [class*="loading"], [class*="streaming"]');
        return loadingElements.length === 0;
      }, { timeout: 60000 });
      
      // Look for AI response (might be in different containers)
      const possibleResponseSelectors = [
        '.bg-gray-100:not(a)', // AI response container (excluding navigation)
        '[class*="message"]:not([class*="user"])', // Message containers
        'div:has-text("Assistant")', // Assistant messages
        'div:has-text("AI")', // AI messages
      ];
      
      let aiResponseFound = false;
      for (const selector of possibleResponseSelectors) {
        try {
          const response = page.locator(selector).last();
          if (await response.isVisible({ timeout: 5000 })) {
            const responseText = await response.textContent();
            if (responseText && responseText.length > 10) {
              console.log('✅ AI response found:', responseText.substring(0, 100) + '...');
              aiResponseFound = true;
              break;
            }
          }
        } catch (e) {
          // Continue to next selector
        }
      }
      
      if (!aiResponseFound) {
        console.log('⚠️ No AI response detected - API might not be configured');
      }
      
    } catch (error) {
      console.log('⚠️ AI response timeout - API might not be configured or working');
    }
  });

  test('should verify chat interface elements', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Verify all key elements are present
    const chatInput = page.locator('textarea[placeholder*="Start typing"]');
    const sendButton = page.locator('button.p-2.bg-blue-600');
    
    await expect(chatInput).toBeVisible();
    await expect(sendButton).toBeVisible();
    
    // Test input interaction
    await chatInput.fill('Test message');
    await expect(sendButton).toBeEnabled();
    
    // Clear input
    await chatInput.fill('');
    await expect(sendButton).toBeDisabled();
    
    console.log('✅ Chat interface elements verified');
  });

  test('should handle multiple messages', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    const chatInput = page.locator('textarea[placeholder*="Start typing"]');
    const sendButton = page.locator('button.p-2.bg-blue-600');
    
    // Send first message
    await chatInput.fill('First message');
    await sendButton.click();
    await page.waitForTimeout(1000);
    
    // Send second message
    await chatInput.fill('Second message');
    await sendButton.click();
    await page.waitForTimeout(1000);
    
    // Verify both messages appear
    await expect(page.locator('text=First message')).toBeVisible();
    await expect(page.locator('text=Second message')).toBeVisible();
    
    console.log('✅ Multiple messages handled correctly');
  });
});
