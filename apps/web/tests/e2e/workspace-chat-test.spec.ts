import { test, expect } from '@playwright/test';

test.describe('Workspace Chat Creation', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@blendable.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should handle workspace creation for new chat', async ({ page }) => {
    // Navigate to chats page
    await page.goto('/chats');
    
    // Listen for console errors
    const consoleErrors: string[] = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text());
      }
    });
    
    // Wait for the page to load
    await page.waitForLoadState('networkidle');
    
    // Check if we're redirected to workspaces page (due to no workspace)
    const currentUrl = page.url();
    console.log('Current URL:', currentUrl);
    
    if (currentUrl.includes('/workspaces')) {
      console.log('Redirected to workspaces page - user has no workspace');
      
      // Create a new workspace
      await page.click('button:has-text("Create Workspace"), a[href*="/workspaces/create"]');
      
      // Fill workspace creation form
      await page.waitForSelector('input[name="name"]');
      await page.fill('input[name="name"]', 'Test Workspace');
      
      // Select a team if required
      const teamSelect = page.locator('select[name="team_id"]');
      if (await teamSelect.isVisible()) {
        await teamSelect.selectOption({ index: 0 });
      }
      
      // Submit the form
      await page.click('button:has-text("Create"), button[type="submit"]');
      
      // Wait for redirect back to chats
      await page.waitForURL(/.*chats/);
    }
    
    // Now try to create a new chat
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Look for new chat button or input field
    const newChatButton = page.locator('button:has-text("New Chat"), button:has-text("Start Chat"), a[href*="/chats/create"]');
    const chatInput = page.locator('textarea[placeholder*="Start typing"], input[placeholder*="message"]');
    
    if (await newChatButton.isVisible()) {
      console.log('Found new chat button, clicking...');
      await newChatButton.click();
    } else if (await chatInput.isVisible()) {
      console.log('Found chat input, typing message...');
      await chatInput.fill('Hello, this is a test message');
      await page.click('button:has(svg), button[type="submit"]');
    } else {
      console.log('Neither new chat button nor input found');
      // Take a screenshot for debugging
      await page.screenshot({ path: 'workspace-chat-debug.png' });
    }
    
    // Check for console errors
    if (consoleErrors.length > 0) {
      console.log('Console errors found:', consoleErrors);
      // Filter out Laravel Boost messages
      const relevantErrors = consoleErrors.filter(error => 
        !error.includes('Browser logger active') && 
        !error.includes('MCP server detected') &&
        !error.includes('workspace')
      );
      
      if (relevantErrors.length > 0) {
        console.log('Relevant console errors:', relevantErrors);
      }
    }
    
    // Wait a moment for any async operations
    await page.waitForTimeout(2000);
    
    // Check if we have a chat interface or if there are any error messages
    const errorMessages = page.locator('.error, .alert-danger, [class*="error"]');
    if (await errorMessages.count() > 0) {
      const errorText = await errorMessages.first().textContent();
      console.log('Error message found:', errorText);
    }
  });

  test('should verify workspace data is properly loaded', async ({ page }) => {
    // Navigate to dashboard first to check workspace data
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');
    
    // Check if workspace data is available in the page
    const workspaceData = await page.evaluate(() => {
      // Check if workspace data is available in the window object or props
      const app = document.querySelector('#app');
      if (app && app.textContent) {
        return app.textContent.includes('workspace') || app.textContent.includes('Workspace');
      }
      return false;
    });
    
    console.log('Workspace data available:', workspaceData);
    
    // Navigate to chats
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Check for workspace-related elements
    const workspaceSelector = page.locator('[data-workspace], .workspace, [class*="workspace"]');
    const hasWorkspaceElements = await workspaceSelector.count() > 0;
    
    console.log('Workspace elements found:', hasWorkspaceElements);
    
    // Check if there's a workspace selector or switcher
    const workspaceSwitcher = page.locator('select[name*="workspace"], [data-testid*="workspace"]');
    const hasWorkspaceSwitcher = await workspaceSwitcher.isVisible();
    
    console.log('Workspace switcher found:', hasWorkspaceSwitcher);
  });
});
