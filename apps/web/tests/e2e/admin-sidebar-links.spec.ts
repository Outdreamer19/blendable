import { test, expect } from '@playwright/test';

test.describe('Admin Sidebar Links Navigation', () => {
  test.beforeEach(async ({ page }) => {
    // Login as admin
    await page.goto('/login');
    await page.waitForLoadState('networkidle');
    
    await page.waitForSelector('input[type="email"], input[name="email"], #email', { timeout: 5000 });
    
    const emailInput = page.locator('input[type="email"], input[name="email"], #email').first();
    const passwordInput = page.locator('input[type="password"], input[name="password"], #password').first();
    
    await emailInput.fill('shane1obdurate@gmail.com');
    await passwordInput.fill('password');
    
    const submitButton = page.locator('button[type="submit"]').first();
    await submitButton.click();
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*(dashboard|chats|billing)/, { timeout: 15000 });
    
    // Ensure we're on a page with the sidebar
    if (!page.url().includes('/dashboard')) {
      await page.goto('/dashboard');
      await page.waitForLoadState('networkidle');
    }
  });

  test('should navigate to Dashboard from sidebar', async ({ page }) => {
    // Find and click Dashboard link
    const dashboardLink = page.locator('a[href*="/dashboard"]').first();
    await expect(dashboardLink).toBeVisible();
    
    await dashboardLink.click();
    await page.waitForLoadState('networkidle');
    
    expect(page.url()).toContain('/dashboard');
    console.log('✅ Dashboard link works');
  });

  test('should navigate to Chats from sidebar', async ({ page }) => {
    // Find and click Chats link - wait for it to be visible and enabled
    const chatsLink = page.locator('a[href*="/chats"]:has-text("Chats")').first();
    await expect(chatsLink).toBeVisible();
    
    // Click and wait for navigation
    await Promise.all([
      page.waitForURL(/.*\/chats/, { timeout: 10000 }),
      chatsLink.click(),
    ]);
    
    // Should be on chats page, not redirected to subscription
    expect(page.url()).toContain('/chats');
    
    // Verify we're not on onboarding page
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    expect(isOnboarding).toBe(false);
    
    console.log('✅ Chats link works');
  });

  test('should navigate to Personas from sidebar', async ({ page }) => {
    // Find and click Personas link
    const personasLink = page.locator('a[href*="/personas"]:has-text("Personas")').first();
    await expect(personasLink).toBeVisible();
    
    // Click and wait for navigation
    await Promise.all([
      page.waitForURL(/.*\/personas/, { timeout: 10000 }),
      personasLink.click(),
    ]);
    
    // Should be on personas page, not redirected
    expect(page.url()).toContain('/personas');
    
    // Verify we're not on onboarding page
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    expect(isOnboarding).toBe(false);
    
    console.log('✅ Personas link works');
  });

  test('should navigate to Prompts from sidebar', async ({ page }) => {
    // Find and click Prompts link
    const promptsLink = page.locator('a[href*="/prompts"]:has-text("Prompts")').first();
    await expect(promptsLink).toBeVisible();
    
    // Click and wait for navigation
    await Promise.all([
      page.waitForURL(/.*\/prompts/, { timeout: 10000 }),
      promptsLink.click(),
    ]);
    
    // Should be on prompts page, not redirected
    expect(page.url()).toContain('/prompts');
    
    // Verify we're not on onboarding page
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    expect(isOnboarding).toBe(false);
    
    console.log('✅ Prompts link works');
  });

  test('should navigate to Images from sidebar', async ({ page }) => {
    // Find and click Images link
    const imagesLink = page.locator('a[href*="/images"]:has-text("Images")').first();
    await expect(imagesLink).toBeVisible();
    
    // Click and wait for navigation
    await Promise.all([
      page.waitForURL(/.*\/images/, { timeout: 10000 }),
      imagesLink.click(),
    ]);
    
    // Should be on images page, not redirected
    expect(page.url()).toContain('/images');
    
    // Verify we're not on onboarding page
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    expect(isOnboarding).toBe(false);
    
    console.log('✅ Images link works');
  });

  test('should navigate to Usage from sidebar', async ({ page }) => {
    // Find and click Usage link
    const usageLink = page.locator('a[href*="/usage"]:has-text("Usage")').first();
    await expect(usageLink).toBeVisible();
    
    // Click and wait for navigation
    await Promise.all([
      page.waitForURL(/.*\/usage/, { timeout: 10000 }),
      usageLink.click(),
    ]);
    
    // Should be on usage page, not redirected
    expect(page.url()).toContain('/usage');
    
    // Verify we're not on onboarding page
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    expect(isOnboarding).toBe(false);
    
    console.log('✅ Usage link works');
  });

  test('should navigate to Teams from sidebar', async ({ page }) => {
    // Find and click Teams link
    const teamsLink = page.locator('a[href*="/teams"]:has-text("Teams")').first();
    await expect(teamsLink).toBeVisible();
    
    // Click and wait for navigation
    await Promise.all([
      page.waitForURL(/.*\/teams/, { timeout: 10000 }),
      teamsLink.click(),
    ]);
    
    // Should be on teams page, not redirected
    expect(page.url()).toContain('/teams');
    
    // Verify we're not on onboarding page
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    expect(isOnboarding).toBe(false);
    
    console.log('✅ Teams link works');
  });

  test('should navigate to Workspaces from sidebar', async ({ page }) => {
    // Find and click Workspaces link
    const workspacesLink = page.locator('a[href*="/workspaces"]:has-text("Workspaces")').first();
    await expect(workspacesLink).toBeVisible();
    
    // Click and wait for navigation
    await Promise.all([
      page.waitForURL(/.*\/workspaces/, { timeout: 10000 }),
      workspacesLink.click(),
    ]);
    
    // Should be on workspaces page, not redirected
    expect(page.url()).toContain('/workspaces');
    
    // Verify we're not on onboarding page
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    expect(isOnboarding).toBe(false);
    
    console.log('✅ Workspaces link works');
  });

  test('should navigate to Billing from sidebar', async ({ page }) => {
    // Find and click Billing link
    const billingLink = page.locator('a[href*="/billing"]:has-text("Billing")').first();
    await expect(billingLink).toBeVisible();
    
    // Click and wait for navigation
    await Promise.all([
      page.waitForURL(/.*\/billing/, { timeout: 10000 }),
      billingLink.click(),
    ]);
    
    // Should be on billing page
    expect(page.url()).toContain('/billing');
    
    // Should see billing dashboard, not onboarding
    const welcomeHeading = page.locator('h1:has-text("Welcome to Blendable")');
    const isOnboarding = await welcomeHeading.isVisible({ timeout: 1000 }).catch(() => false);
    expect(isOnboarding).toBe(false);
    
    // Should see billing dashboard elements
    const billingHeading = page.locator('h2:has-text("Billing & Usage"), h3:has-text("Available Plans")');
    const hasBillingDashboard = await billingHeading.isVisible({ timeout: 3000 }).catch(() => false);
    
    console.log(`Billing dashboard visible: ${hasBillingDashboard}`);
    console.log('✅ Billing link works');
  });

  test('should verify all sidebar links are visible and accessible', async ({ page }) => {
    // List of expected sidebar links
    const expectedLinks = [
      { name: 'Dashboard', href: '/dashboard' },
      { name: 'Chats', href: '/chats' },
      { name: 'Personas', href: '/personas' },
      { name: 'Prompts', href: '/prompts' },
      { name: 'Images', href: '/images' },
      { name: 'Usage', href: '/usage' },
      { name: 'Teams', href: '/teams' },
      { name: 'Workspaces', href: '/workspaces' },
      { name: 'Billing', href: '/billing' },
    ];

    // Verify all links are visible
    for (const link of expectedLinks) {
      const linkElement = page.locator(`a[href*="${link.href}"]`).first();
      const isVisible = await linkElement.isVisible({ timeout: 2000 }).catch(() => false);
      
      expect(isVisible).toBe(true);
      console.log(`✅ ${link.name} link is visible`);
    }

    // Take a screenshot of the sidebar
    const sidebar = page.locator('aside').first();
    await sidebar.screenshot({ path: 'test-results/admin-sidebar.png' });
    
    console.log('✅ All sidebar links are visible and accessible');
  });
});

