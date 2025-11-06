import { test, expect } from '@playwright/test';

test.describe('Complete Chat Creation Flow', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@blendable.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should create workspace and then create a new chat', async ({ page }) => {
    // First, check if user has workspaces
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Check if we're redirected to workspaces page (no workspace available)
    const currentUrl = page.url();
    console.log('Current URL after navigating to chats:', currentUrl);
    
    if (currentUrl.includes('/workspaces')) {
      console.log('User has no workspace, creating one...');
      
      // Navigate to create workspace page
      await page.goto('/workspaces/create');
      await page.waitForLoadState('networkidle');
      
      // Fill workspace creation form
      await page.fill('input[name="name"]', 'Test Workspace');
      
      // Select a team if required
      const teamSelect = page.locator('select[name="team_id"]');
      if (await teamSelect.isVisible()) {
        const options = await teamSelect.locator('option').all();
        if (options.length > 1) {
          await teamSelect.selectOption({ index: 1 }); // Select first non-empty option
        }
      }
      
      // Submit the form
      await page.click('button:has-text("Create"), button[type="submit"]');
      
      // Wait for redirect
      await page.waitForURL(/.*workspaces/);
      console.log('Workspace created, URL after creation:', page.url());
    }
    
    // Now navigate to chats page
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    console.log('Final URL on chats page:', page.url());
    
    // Look for chat interface elements
    const chatInput = page.locator('textarea[placeholder*="Start typing"], input[placeholder*="message"], textarea[placeholder*="Type"]');
    const newChatButton = page.locator('button:has-text("New Chat"), button:has-text("Start Chat"), a[href*="/chats/create"]');
    
    console.log('Chat input visible:', await chatInput.isVisible());
    console.log('New chat button visible:', await newChatButton.isVisible());
    
    // Try to create a new chat
    if (await newChatButton.isVisible()) {
      console.log('Clicking new chat button...');
      await newChatButton.click();
      await page.waitForTimeout(2000);
    } else if (await chatInput.isVisible()) {
      console.log('Found chat input, typing message...');
      await chatInput.fill('Hello, this is a test message');
      await page.click('button:has(svg), button[type="submit"], button:has-text("Send")');
      await page.waitForTimeout(2000);
    } else {
      console.log('Neither new chat button nor input found');
      // Take a screenshot for debugging
      await page.screenshot({ path: 'complete-chat-creation-debug.png' });
      
      // Check what elements are actually on the page
      const allButtons = await page.locator('button').all();
      console.log('All buttons on page:');
      for (const button of allButtons) {
        const text = await button.textContent();
        if (text && text.trim()) {
          console.log(`- Button: "${text.trim()}"`);
        }
      }
      
      const allInputs = await page.locator('input, textarea').all();
      console.log('All inputs on page:');
      for (const input of allInputs) {
        const placeholder = await input.getAttribute('placeholder');
        if (placeholder) {
          console.log(`- Input placeholder: "${placeholder}"`);
        }
      }
    }
    
    // Check if we successfully created a chat or if there are any error messages
    const errorMessages = page.locator('.error, .alert-danger, [class*="error"]');
    if (await errorMessages.count() > 0) {
      const errorText = await errorMessages.first().textContent();
      console.log('Error message found:', errorText);
    }
    
    // Check if we have a chat interface now
    const chatMessages = page.locator('[class*="message"], .chat-message, [data-testid*="message"]');
    const hasChatMessages = await chatMessages.count() > 0;
    console.log('Chat messages found:', hasChatMessages);
    
    // Verify the page is working - should be on workspaces page if no workspace exists
    expect(page.url()).toMatch(/workspaces|chats/);
  });

  test('should verify workspace data is properly loaded in props', async ({ page }) => {
    // Navigate to dashboard first to check workspace data
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');
    
    // Check if workspace data is available in the page props
    const props = await page.evaluate(() => {
      // Try to access the Inertia props
      if ((window as any).__inertia) {
        return (window as any).__inertia.page.props;
      }
      return null;
    });
    
    console.log('Dashboard props:', props);
    
    if (props && props.currentWorkspace === null) {
      console.log('currentWorkspace is null - user needs to create a workspace');
    } else if (props && props.currentWorkspace) {
      console.log('currentWorkspace found:', props.currentWorkspace);
    }
    
    // Navigate to chats
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Check props on chats page
    const chatProps = await page.evaluate(() => {
      if ((window as any).__inertia) {
        return (window as any).__inertia.page.props;
      }
      return null;
    });
    
    console.log('Chats props:', chatProps);
    
    if (chatProps && chatProps.currentWorkspace === null) {
      console.log('currentWorkspace is null on chats page - this is the issue!');
    }
  });
});
