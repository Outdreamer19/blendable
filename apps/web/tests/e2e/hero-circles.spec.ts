import { test, expect } from '@playwright/test';

test.describe('Hero Section Circles', () => {
  test('should display hero circles correctly on mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 }); // Mobile viewport
    await page.goto('/');
    
    // Wait for the page to load
    await page.waitForLoadState('networkidle');
    
    // Check if the main container exists
    const mainContainer = page.locator('.main');
    await expect(mainContainer).toBeVisible();
    
    // Check container dimensions (mobile: 600px x 300px)
    const mainBox = await mainContainer.boundingBox();
    expect(mainBox).not.toBeNull();
    if (mainBox) {
      expect(mainBox.width).toBeCloseTo(600, 0);
      expect(mainBox.height).toBeCloseTo(300, 0);
    }
    
    // Check if big circle exists
    const bigCircle = page.locator('.big-circle');
    await expect(bigCircle).toBeVisible();
    
    // Check big circle dimensions (mobile: 600px x 600px)
    const bigCircleWidth = await bigCircle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).width);
    });
    const bigCircleHeight = await bigCircle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).height);
    });
    expect(bigCircleWidth).toBeCloseTo(600, 0);
    expect(bigCircleHeight).toBeCloseTo(600, 0);
    
    // Check if inner circle exists
    const innerCircle = page.locator('.circle');
    await expect(innerCircle).toBeVisible();
    
    // Check inner circle dimensions (mobile: 450px x 450px)
    const innerCircleWidth = await innerCircle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).width);
    });
    const innerCircleHeight = await innerCircle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).height);
    });
    expect(innerCircleWidth).toBeCloseTo(450, 0);
    expect(innerCircleHeight).toBeCloseTo(450, 0);
    
    // Check if icons are visible (should have 4 on big circle, 4 on inner circle)
    const bigCircleIcons = page.locator('.big-circle .icon-block');
    await expect(bigCircleIcons).toHaveCount(4);
    
    const innerCircleIcons = page.locator('.circle .icon-block');
    await expect(innerCircleIcons).toHaveCount(4);
    
    // Check if center logo exists
    const centerLogo = page.locator('.center-logo');
    await expect(centerLogo).toBeVisible();
    
    // Verify circles are positioned at top (only top half visible)
    const bigCircleBox = await bigCircle.boundingBox();
    if (bigCircleBox && mainBox) {
      // Circle should start at top of container
      expect(bigCircleBox.y).toBeCloseTo(mainBox.y, 0);
      // Circle center should be at bottom of container (300px from top)
      expect(bigCircleBox.y + 300).toBeCloseTo(mainBox.y + mainBox.height, 0);
    }
  });

  test('should display hero circles correctly on desktop', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 1080 }); // Desktop viewport
    await page.goto('/');
    
    // Wait for the page to load
    await page.waitForLoadState('networkidle');
    
    // Check if the main container exists
    const mainContainer = page.locator('.main');
    await expect(mainContainer).toBeVisible();
    
    // Check container dimensions (desktop: 700px x 350px)
    const mainBox = await mainContainer.boundingBox();
    expect(mainBox).not.toBeNull();
    if (mainBox) {
      expect(mainBox.width).toBeCloseTo(700, 0);
      expect(mainBox.height).toBeCloseTo(350, 0);
    }
    
    // Check if big circle exists
    const bigCircle = page.locator('.big-circle');
    await expect(bigCircle).toBeVisible();
    
    // Check big circle dimensions (desktop: 700px x 700px)
    const bigCircleWidth = await bigCircle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).width);
    });
    const bigCircleHeight = await bigCircle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).height);
    });
    expect(bigCircleWidth).toBeCloseTo(700, 0);
    expect(bigCircleHeight).toBeCloseTo(700, 0);
    
    // Check if inner circle exists
    const innerCircle = page.locator('.circle');
    await expect(innerCircle).toBeVisible();
    
    // Check inner circle dimensions (desktop: 525px x 525px)
    const innerCircleWidth = await innerCircle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).width);
    });
    const innerCircleHeight = await innerCircle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).height);
    });
    expect(innerCircleWidth).toBeCloseTo(525, 0);
    expect(innerCircleHeight).toBeCloseTo(525, 0);
    
    // Check if icons are visible
    const bigCircleIcons = page.locator('.big-circle .icon-block');
    await expect(bigCircleIcons).toHaveCount(4);
    
    const innerCircleIcons = page.locator('.circle .icon-block');
    await expect(innerCircleIcons).toHaveCount(4);
    
    // Check if center logo exists
    const centerLogo = page.locator('.center-logo');
    await expect(centerLogo).toBeVisible();
    
    // Verify circles are positioned at top (only top half visible)
    const bigCircleBox = await bigCircle.boundingBox();
    if (bigCircleBox && mainBox) {
      // Circle should start at top of container
      expect(bigCircleBox.y).toBeCloseTo(mainBox.y, 0);
      // Circle center should be at bottom of container (350px from top)
      expect(bigCircleBox.y + 350).toBeCloseTo(mainBox.y + mainBox.height, 0);
    }
  });

  test('should have circles rotating', async ({ page }) => {
    await page.goto('/');
    await page.waitForLoadState('networkidle');
    
    const bigCircle = page.locator('.big-circle');
    const innerCircle = page.locator('.circle');
    
    // Check if rotation animation is applied
    const bigCircleAnimation = await bigCircle.evaluate((el) => {
      const style = window.getComputedStyle(el);
      return style.animationName;
    });
    expect(bigCircleAnimation).toBe('rotate');
    
    const innerCircleAnimation = await innerCircle.evaluate((el) => {
      const style = window.getComputedStyle(el);
      return style.animationName;
    });
    expect(innerCircleAnimation).toBe('circle-rotate');
  });
});

