import { test, expect } from '@playwright/test';

test.describe('Debug Workspace Issue', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login');
    await page.fill('#email', 'demo@blendable.com');
    await page.fill('#password', 'password');
    await page.click('button:has-text("Log in")');
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard/);
  });

  test('should debug workspace issue step by step', async ({ page }) => {
    // Listen for all console messages
    const consoleMessages: Array<{type: string, text: string}> = [];
    page.on('console', msg => {
      consoleMessages.push({type: msg.type(), text: msg.text()});
    });
    
    // Navigate to chats page
    await page.goto('/chats');
    await page.waitForLoadState('networkidle');
    
    // Log all console messages
    console.log('All console messages:');
    consoleMessages.forEach(msg => {
      console.log(`${msg.type}: ${msg.text}`);
    });
    
    // Check if the page has workspace data
    const pageContent = await page.content();
    console.log('Page contains workspace data:', pageContent.includes('workspace'));
    
    // Check the props being passed to the Vue component
    const props = await page.evaluate(() => {
      // Try to access the Inertia props
      const app = document.querySelector('#app');
      if (app && (window as any).__inertia) {
        return (window as any).__inertia.page.props;
      }
      return null;
    });
    
    console.log('Inertia props:', props);
    
    // Check if currentWorkspace is null
    if (props && props.currentWorkspace === null) {
      console.log('currentWorkspace is null - this is the issue!');
    }
    
    // Try to click the new chat button and see what happens
    const newChatButton = page.locator('button:has-text("New Chat"), button:has-text("Start Chat"), a[href*="/chats/create"]');
    if (await newChatButton.isVisible()) {
      console.log('Found new chat button, clicking...');
      await newChatButton.click();
      
      // Wait a moment and check console again
      await page.waitForTimeout(1000);
      
      console.log('Console messages after clicking new chat:');
      consoleMessages.forEach(msg => {
        if (msg.text.includes('workspace') || msg.text.includes('No current')) {
          console.log(`${msg.type}: ${msg.text}`);
        }
      });
    }
    
    // Take a screenshot for debugging
    await page.screenshot({ path: 'debug-workspace-issue.png' });
  });
});
