import { test, expect } from '@playwright/test';

test.describe('Real-Person Personas', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'test@example.com');
    await page.fill('#password', 'password');
    await page.click('button[type="submit"]');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should display public personas page', async ({ page }) => {
    await page.goto('/experts');
    
    // Check if the public personas page is visible
    await expect(page.locator('h1')).toContainText('Expert Personas');
    
    // Check if the description is visible
    await expect(page.locator('text=Chat with AI assistants trained on real experts')).toBeVisible();
    
    // Check if the features section is visible
    await expect(page.locator('text=Why Use Expert Personas?')).toBeVisible();
  });

  test('should display Alex Hormozi persona', async ({ page }) => {
    await page.goto('/experts');
    
    // Check if Alex Hormozi persona is visible (use first() to avoid strict mode violation)
    await expect(page.locator('text=Alex Hormozi').first()).toBeVisible();
    
    // Check if the description contains business scaling info
    await expect(page.locator('text=Serial entrepreneur, author, and business scaling expert')).toBeVisible();
    
    // Check if the persona has knowledge items
    await expect(page.locator('text=knowledge items')).toBeVisible();
  });

  test('should navigate to persona details', async ({ page }) => {
    await page.goto('/experts');
    
    // Click on Alex Hormozi persona "Learn More" button
    await page.click('text=Learn More');
    
    // Wait for navigation to complete
    await page.waitForLoadState('networkidle');
    
    // Check if we're on the persona details page
    await expect(page.locator('h1')).toContainText('Alex Hormozi');
    
    // Check if the persona description is visible (use first() to avoid strict mode violation)
    await expect(page.locator('text=Serial entrepreneur, author, and business scaling expert').first()).toBeVisible();
  });

  test('should create chat with Alex Hormozi persona', async ({ page }) => {
    // Go to chats page
    await page.goto('/chats');
    
    // Click on "New Chat" button
    await page.click('button:has-text("New Chat")');
    
    // Wait for chat interface to load
    await page.waitForSelector('[data-testid="chat-interface"]');
    
    // Check if persona picker is visible
    await expect(page.locator('text=Persona')).toBeVisible();
    
    // Select Alex Hormozi persona
    await page.selectOption('select[name="persona"]', '1'); // Assuming Alex Hormozi has ID 1
    
    // Type a business question
    await page.fill('textarea[name="message"]', 'I have a fitness business that is struggling to scale. We have good trainers but can\'t seem to get enough clients. What should I focus on first?');
    
    // Send the message
    await page.click('button[type="submit"]');
    
    // Wait for response (this might take a while for AI response)
    await page.waitForSelector('[data-testid="ai-response"]', { timeout: 30000 });
    
    // Check if the response contains Alex Hormozi's style advice
    const response = await page.locator('[data-testid="ai-response"]').textContent();
    expect(response).toContain('offer'); // Alex Hormozi always talks about offers
  });

  test('should display persona in chat interface', async ({ page }) => {
    // Go to an existing chat
    await page.goto('/chats');
    
    // Click on the first chat
    await page.click('[data-testid="chat-item"]:first-child');
    
    // Check if persona selector is visible
    await expect(page.locator('text=Persona')).toBeVisible();
    
    // Check if Alex Hormozi is available in the dropdown
    await page.click('select[name="persona"]');
    await expect(page.locator('option:has-text("Alex Hormozi")')).toBeVisible();
  });

  test('should handle persona switching in chat', async ({ page }) => {
    // Go to an existing chat
    await page.goto('/chats');
    
    // Click on the first chat
    await page.click('[data-testid="chat-item"]:first-child');
    
    // Select Alex Hormozi persona
    await page.selectOption('select[name="persona"]', '1');
    
    // Wait for persona to be applied
    await page.waitForSelector('[data-testid="persona-active"]');
    
    // Check if the persona is active
    await expect(page.locator('[data-testid="persona-active"]')).toContainText('Alex Hormozi');
  });

  test('should display persona knowledge and actions', async ({ page }) => {
    // Go to experts page
    await page.goto('/experts');
    
    // Click on Alex Hormozi persona
    await page.click('text=Learn More');
    
    // Check if knowledge section is visible
    await expect(page.locator('text=Knowledge Base')).toBeVisible();
    
    // Check if actions section is visible
    await expect(page.locator('text=Available Actions')).toBeVisible();
    
    // Check if specific knowledge items are visible
    await expect(page.locator('text=100M Offers')).toBeVisible();
    await expect(page.locator('text=100M Leads')).toBeVisible();
  });

  test('should handle persona system prompt correctly', async ({ page }) => {
    // Go to an existing chat with Alex Hormozi persona
    await page.goto('/chats');
    
    // Create a new chat
    await page.click('button:has-text("New Chat")');
    
    // Select Alex Hormozi persona
    await page.selectOption('select[name="persona"]', '1');
    
    // Type a question that would trigger Alex Hormozi's specific response style
    await page.fill('textarea[name="message"]', 'What is the most important thing for a business to focus on?');
    
    // Send the message
    await page.click('button[type="submit"]');
    
    // Wait for response
    await page.waitForSelector('[data-testid="ai-response"]', { timeout: 30000 });
    
    // Check if the response contains Alex Hormozi's characteristic phrases
    const response = await page.locator('[data-testid="ai-response"]').textContent();
    
    // Alex Hormozi typically mentions offers, lead generation, or business fundamentals
    const hasAlexStyle = response.includes('offer') || 
                        response.includes('lead') || 
                        response.includes('business') ||
                        response.includes('scale');
    
    expect(hasAlexStyle).toBeTruthy();
  });

  test('should display persona marketplace features', async ({ page }) => {
    await page.goto('/experts');
    
    // Check if the features section is visible
    await expect(page.locator('text=Specialized Expertise')).toBeVisible();
    await expect(page.locator('text=Trained on Real Content')).toBeVisible();
    await expect(page.locator('text=Instant Access')).toBeVisible();
    
    // Check if the feature descriptions are appropriate
    await expect(page.locator('text=Get advice from domain experts')).toBeVisible();
    await expect(page.locator('text=Each persona is trained on books, courses')).toBeVisible();
    await expect(page.locator('text=Get expert-level advice instantly')).toBeVisible();
  });

  test('should handle persona errors gracefully', async ({ page }) => {
    // Try to access a non-existent persona
    await page.goto('/experts/999');
    
    // Should show 404 or redirect
    await expect(page.locator('text=404')).toBeVisible();
  });

  test('should maintain persona context across messages', async ({ page }) => {
    // Go to chat with Alex Hormozi persona
    await page.goto('/chats');
    await page.click('button:has-text("New Chat")');
    await page.selectOption('select[name="persona"]', '1');
    
    // Send first message
    await page.fill('textarea[name="message"]', 'I need help with my business offer');
    await page.click('button[type="submit"]');
    await page.waitForSelector('[data-testid="ai-response"]', { timeout: 30000 });
    
    // Send follow-up message
    await page.fill('textarea[name="message"]', 'Can you give me more specific advice?');
    await page.click('button[type="submit"]');
    await page.waitForSelector('[data-testid="ai-response"]', { timeout: 30000 });
    
    // Check if the persona context is maintained
    const responses = await page.locator('[data-testid="ai-response"]').allTextContents();
    const lastResponse = responses[responses.length - 1];
    
    // Should still be in Alex Hormozi's style
    expect(lastResponse).toContain('offer');
  });
});
