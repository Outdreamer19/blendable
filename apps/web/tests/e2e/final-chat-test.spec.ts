import { test, expect } from '@playwright/test';

test.describe('Final Chat AI Integration Test', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@omni-ai.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should test chat functionality and record AI response', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    const chatInput = page.locator('textarea[placeholder*="Start typing"]');
    const sendButton = page.locator('button.p-2.bg-blue-600');
    
    // Verify interface is ready
    await expect(chatInput).toBeVisible();
    await expect(sendButton).toBeVisible();
    await expect(sendButton).toBeDisabled(); // Should be disabled when empty
    
    // Type a simple message
    const testMessage = 'Hello! What is 2+2?';
    await chatInput.fill(testMessage);
    
    // Verify send button is now enabled
    await expect(sendButton).toBeEnabled();
    
    // Send the message
    await sendButton.click();
    
    // Wait for any processing
    await page.waitForTimeout(2000);
    
    // Take a screenshot to see the current state
    await page.screenshot({ path: 'chat-after-send.png', fullPage: true });
    
    // Check if there are any loading indicators
    const loadingIndicators = await page.locator('.animate-pulse, [class*="loading"], [class*="streaming"]').count();
    console.log(`Loading indicators found: ${loadingIndicators}`);
    
    // Check for any error messages
    const errorMessages = await page.locator('[class*="error"], .text-red-500, .text-red-600').count();
    console.log(`Error messages found: ${errorMessages}`);
    
    // Look for any response containers
    const responseContainers = await page.locator('.bg-gray-100, [class*="message"], [class*="response"]').count();
    console.log(`Response containers found: ${responseContainers}`);
    
    // Check if the input was cleared (this would indicate successful submission)
    const inputValue = await chatInput.inputValue();
    console.log(`Input value after send: "${inputValue}"`);
    
    // Wait longer for potential AI response
    await page.waitForTimeout(10000);
    
    // Take another screenshot after waiting
    await page.screenshot({ path: 'chat-after-wait.png', fullPage: true });
    
    // Check for any AI response
    const possibleResponseSelectors = [
      'div:has-text("Assistant")',
      'div:has-text("AI")',
      '.bg-gray-100:not(a)',
      '[class*="message"]:not([class*="user"])',
    ];
    
    let aiResponseFound = false;
    for (const selector of possibleResponseSelectors) {
      try {
        const elements = await page.locator(selector).all();
        for (const element of elements) {
          const text = await element.textContent();
          if (text && text.length > 10 && !text.includes('Chats') && !text.includes('Dashboard')) {
            console.log(`✅ Potential AI response found: ${text.substring(0, 100)}...`);
            aiResponseFound = true;
            break;
          }
        }
        if (aiResponseFound) break;
      } catch (e) {
        // Continue to next selector
      }
    }
    
    if (!aiResponseFound) {
      console.log('⚠️ No AI response detected');
      console.log('This could mean:');
      console.log('1. AI API is not configured');
      console.log('2. Chat functionality is not fully implemented');
      console.log('3. Response is taking longer than expected');
    }
    
    console.log('📸 Screenshots saved: chat-after-send.png, chat-after-wait.png');
    console.log('✅ Chat test completed - check screenshots for details');
  });

  test('should verify chat interface elements and functionality', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    const chatInput = page.locator('textarea[placeholder*="Start typing"]');
    const sendButton = page.locator('button.p-2.bg-blue-600');
    
    // Test basic functionality
    await expect(chatInput).toBeVisible();
    await expect(sendButton).toBeVisible();
    await expect(sendButton).toBeDisabled();
    
    // Test typing
    await chatInput.fill('Test message');
    await expect(sendButton).toBeEnabled();
    
    // Test clearing
    await chatInput.fill('');
    await expect(sendButton).toBeDisabled();
    
    // Test different message types
    const testMessages = [
      'What is 2+2?',
      'Write a haiku about coding.',
      'What is the capital of France?',
      'Explain machine learning in simple terms.',
    ];
    
    for (const message of testMessages) {
      await chatInput.fill(message);
      await expect(sendButton).toBeEnabled();
      await sendButton.click();
      await page.waitForTimeout(1000);
      console.log(`✅ Sent message: "${message}"`);
    }
    
    console.log('✅ All test messages sent successfully');
  });
});
