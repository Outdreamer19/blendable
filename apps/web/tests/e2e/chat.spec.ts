import { test, expect } from '@playwright/test';

test.describe('Chat Functionality', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@blendable.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should create a new chat and navigate to chat interface', async ({ page }) => {
    await page.goto('/dashboard');
    
    // Click on "New Chat" button or navigate to chats
    await page.click('a[href*="/chats"]');
    
    // Check if we're on the chat page
    await expect(page).toHaveURL(/.*chats/);
    
    // Check if the chat interface is visible
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
  });

  test('should send a message and receive AI response', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Type a simple test message
    const testMessage = 'Hello! Can you tell me what 2+2 equals?';
    await page.fill('textarea[placeholder*="Start typing"]', testMessage);
    
    // Send the message by clicking the send button
    await page.click('button.p-2.bg-blue-600'); // Send button with blue background
    
    // Wait for the user message to appear
    await expect(page.locator('text=' + testMessage)).toBeVisible();
    
    // Wait for AI response to start streaming (look for loading indicator or streaming message)
    await page.waitForSelector('.animate-pulse, [class*="streaming"], .bg-gray-100', { timeout: 10000 });
    
    // Wait for AI response to complete (no more loading indicators)
    await page.waitForFunction(() => {
      const loadingElements = document.querySelectorAll('.animate-pulse, [class*="streaming"]');
      return loadingElements.length === 0;
    }, { timeout: 30000 });
    
    // Verify that an AI response was received
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
    
    // Check that the response contains some content (not empty)
    const responseText = await aiResponse.textContent();
    expect(responseText).toBeTruthy();
    expect(responseText!.length).toBeGreaterThan(10);
    
    // Verify the response mentions the answer to 2+2 (should be 4)
    await expect(aiResponse).toContainText(/4|four/i);
  });

  test('should handle streaming responses properly', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Send a message that should generate a longer response
    const testMessage = 'Write a short poem about coding.';
    await page.fill('textarea[placeholder*="Start typing"]', testMessage);
    await page.click('button:has(svg)');
    
    // Wait for user message
    await expect(page.locator('text=' + testMessage)).toBeVisible();
    
    // Wait for streaming to start
    await page.waitForSelector('.animate-pulse, [class*="streaming"]', { timeout: 10000 });
    
    // Monitor streaming progress
    let streamingContent = '';
    const startTime = Date.now();
    
    // Wait for streaming to complete
    await page.waitForFunction(() => {
      const loadingElements = document.querySelectorAll('.animate-pulse, [class*="streaming"]');
      return loadingElements.length === 0;
    }, { timeout: 30000 });
    
    // Verify final response
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
    
    const finalResponse = await aiResponse.textContent();
    expect(finalResponse).toBeTruthy();
    expect(finalResponse!.length).toBeGreaterThan(20);
    
    // Verify it's related to the request (contains words like poem, coding, etc.)
    await expect(aiResponse).toContainText(/poem|coding|code|programming/i);
  });

  test('should handle model switching', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Look for model switcher (might be in a dropdown or button)
    const modelSwitcher = page.locator('button:has-text("GPT"), select, [data-testid*="model"]').first();
    
    if (await modelSwitcher.isVisible()) {
      await modelSwitcher.click();
      
      // Try to select a different model if available
      const modelOption = page.locator('option, [role="option"]').nth(1);
      if (await modelOption.isVisible()) {
        await modelOption.click();
      }
    }
    
    // Send a test message
    await page.fill('textarea[placeholder*="Start typing"]', 'What is the capital of France?');
    await page.click('button:has(svg)');
    
    // Wait for response
    await page.waitForSelector('.bg-gray-100', { timeout: 30000 });
    
    // Verify response mentions Paris
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toContainText(/Paris/i);
  });

  test('should handle error responses gracefully', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Send an empty message (should be prevented by frontend)
    await page.click('button:has(svg)');
    
    // Verify the send button is disabled or message wasn't sent
    const sendButton = page.locator('button:has(svg)');
    await expect(sendButton).toBeDisabled();
    
    // Now send a valid message
    await page.fill('textarea[placeholder*="Start typing"]', 'Test message');
    await page.click('button:has(svg)');
    
    // Wait for response
    await page.waitForSelector('.bg-gray-100', { timeout: 30000 });
    
    // Verify we got some response
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
  });

  test('should maintain chat history', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Send first message
    await page.fill('textarea[placeholder*="Start typing"]', 'My name is Test User.');
    await page.click('button:has(svg)');
    
    // Wait for first response
    await page.waitForSelector('.bg-gray-100', { timeout: 30000 });
    
    // Send second message
    await page.fill('textarea[placeholder*="Start typing"]', 'What is my name?');
    await page.click('button:has(svg)');
    
    // Wait for second response
    await page.waitForSelector('.bg-gray-100', { timeout: 30000 });
    
    // Verify both messages are visible
    await expect(page.locator('text=My name is Test User.')).toBeVisible();
    await expect(page.locator('text=What is my name?')).toBeVisible();
    
    // Verify we have at least 2 AI responses
    const aiResponses = page.locator('.bg-gray-100');
    await expect(aiResponses).toHaveCount(2);
  });
});
