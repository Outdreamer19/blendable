import { test, expect } from '@playwright/test';

test.describe('AI Chat Integration Tests', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@blendable.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should send message and receive AI response with math question', async ({ page }) => {
    // Navigate to chat page
    await page.goto('/chats');
    
    // Wait for the chat interface to load
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Type a simple math question
    const mathQuestion = 'What is 15 + 27?';
    await page.fill('textarea[placeholder*="Start typing"]', mathQuestion);
    
    // Send the message
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for the user message to appear
    await expect(page.locator('text=' + mathQuestion)).toBeVisible();
    
    // Wait for AI response to start streaming
    await page.waitForSelector('.animate-pulse, .bg-gray-100', { timeout: 15000 });
    
    // Wait for streaming to complete
    await page.waitForFunction(() => {
      const loadingElements = document.querySelectorAll('.animate-pulse');
      return loadingElements.length === 0;
    }, { timeout: 45000 });
    
    // Verify AI response
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
    
    const responseText = await aiResponse.textContent();
    expect(responseText).toBeTruthy();
    expect(responseText!.length).toBeGreaterThan(5);
    
    // Verify the response contains the correct answer (42)
    await expect(aiResponse).toContainText(/42/i);
  });

  test('should handle creative writing request', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Send a creative writing request
    const creativeRequest = 'Write a haiku about programming.';
    await page.fill('textarea[placeholder*="Start typing"]', creativeRequest);
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for user message
    await expect(page.locator('text=' + creativeRequest)).toBeVisible();
    
    // Wait for AI response
    await page.waitForSelector('.bg-gray-100', { timeout: 45000 });
    
    // Verify response
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
    
    const responseText = await aiResponse.textContent();
    expect(responseText).toBeTruthy();
    expect(responseText!.length).toBeGreaterThan(20);
    
    // Verify it's related to programming
    await expect(aiResponse).toContainText(/programming|code|software|computer/i);
  });

  test('should handle factual question about geography', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Send a geography question
    const geoQuestion = 'What is the largest ocean on Earth?';
    await page.fill('textarea[placeholder*="Start typing"]', geoQuestion);
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for user message
    await expect(page.locator('text=' + geoQuestion)).toBeVisible();
    
    // Wait for AI response
    await page.waitForSelector('.bg-gray-100', { timeout: 45000 });
    
    // Verify response
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
    
    // Verify it mentions the Pacific Ocean
    await expect(aiResponse).toContainText(/Pacific/i);
  });

  test('should handle code generation request', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Send a code generation request
    const codeRequest = 'Write a simple Python function to calculate the factorial of a number.';
    await page.fill('textarea[placeholder*="Start typing"]', codeRequest);
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for user message
    await expect(page.locator('text=' + codeRequest)).toBeVisible();
    
    // Wait for AI response
    await page.waitForSelector('.bg-gray-100', { timeout: 45000 });
    
    // Verify response
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
    
    const responseText = await aiResponse.textContent();
    expect(responseText).toBeTruthy();
    
    // Verify it contains Python code elements
    await expect(aiResponse).toContainText(/def |function|factorial|python/i);
  });

  test('should handle multiple questions in sequence', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // First question
    await page.fill('textarea[placeholder*="Start typing"]', 'What is 5 * 6?');
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for first response
    await page.waitForSelector('.bg-gray-100', { timeout: 45000 });
    await expect(page.locator('.bg-gray-100').last()).toContainText(/30/i);
    
    // Second question
    await page.fill('textarea[placeholder*="Start typing"]', 'What is the capital of Japan?');
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for second response
    await page.waitForSelector('.bg-gray-100', { timeout: 45000 });
    await expect(page.locator('.bg-gray-100').last()).toContainText(/Tokyo/i);
    
    // Verify both questions and answers are visible
    await expect(page.locator('text=What is 5 * 6?')).toBeVisible();
    await expect(page.locator('text=What is the capital of Japan?')).toBeVisible();
    
    // Verify we have 2 AI responses
    const aiResponses = page.locator('.bg-gray-100');
    await expect(aiResponses).toHaveCount(2);
  });

  test('should handle streaming response properly', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Send a request that should generate a longer response
    const longRequest = 'Explain the concept of machine learning in simple terms.';
    await page.fill('textarea[placeholder*="Start typing"]', longRequest);
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for user message
    await expect(page.locator('text=' + longRequest)).toBeVisible();
    
    // Wait for streaming to start
    await page.waitForSelector('.animate-pulse', { timeout: 15000 });
    
    // Monitor the streaming process
    let streamingStarted = false;
    let streamingCompleted = false;
    
    // Check if streaming started
    const streamingElement = page.locator('.animate-pulse');
    if (await streamingElement.isVisible()) {
      streamingStarted = true;
    }
    
    // Wait for streaming to complete
    await page.waitForFunction(() => {
      const loadingElements = document.querySelectorAll('.animate-pulse');
      return loadingElements.length === 0;
    }, { timeout: 60000 });
    
    streamingCompleted = true;
    
    // Verify final response
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
    
    const responseText = await aiResponse.textContent();
    expect(responseText).toBeTruthy();
    expect(responseText!.length).toBeGreaterThan(50);
    
    // Verify it mentions machine learning concepts
    await expect(aiResponse).toContainText(/machine learning|algorithm|data|training/i);
    
    // Log the streaming behavior
    console.log('Streaming started:', streamingStarted);
    console.log('Streaming completed:', streamingCompleted);
    console.log('Response length:', responseText!.length);
  });

  test('should handle error cases gracefully', async ({ page }) => {
    await page.goto('/chats');
    
    // Wait for chat interface
    await expect(page.locator('textarea[placeholder*="Start typing"]')).toBeVisible();
    
    // Test empty message (should be prevented)
    const sendButton = page.locator('button:has(svg)');
    await expect(sendButton).toBeDisabled();
    
    // Test very long message
    const longMessage = 'A'.repeat(1000);
    await page.fill('textarea[placeholder*="Start typing"]', longMessage);
    
    // The button should be enabled for long messages
    await expect(sendButton).toBeEnabled();
    
    // Clear and send a normal message
    await page.fill('textarea[placeholder*="Start typing"]', 'Hello, this is a test message.');
    await page.click('button.p-2.bg-blue-600');
    
    // Wait for response
    await page.waitForSelector('.bg-gray-100', { timeout: 45000 });
    
    // Verify we got a response
    const aiResponse = page.locator('.bg-gray-100').last();
    await expect(aiResponse).toBeVisible();
  });
});
