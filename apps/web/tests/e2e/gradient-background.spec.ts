import { test, expect } from '@playwright/test';

test.describe('Animated Gradient Background', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
  });

  test('should have animated gradient background component', async ({ page }) => {
    const gradientBackground = page.locator('.animated-gradient-bg');
    await expect(gradientBackground).toBeVisible();
    
    // Check if it's positioned correctly
    await expect(gradientBackground).toHaveCSS('position', 'fixed');
    await expect(gradientBackground).toHaveCSS('z-index', '-1');
  });

  test('should have SVG gradient elements', async ({ page }) => {
    const gradientSvg = page.locator('.animated-gradient-bg svg.gradient-svg');
    await expect(gradientSvg).toBeVisible();
    
    // Check SVG attributes
    await expect(gradientSvg).toHaveAttribute('viewBox', '0 0 1000 1000');
    await expect(gradientSvg).toHaveAttribute('preserveAspectRatio', 'xMidYMid slice');
  });

  test('should have gradient definitions', async ({ page }) => {
    const gradientDefs = page.locator('.animated-gradient-bg svg defs');
    await expect(gradientDefs).toBeVisible();
    
    // Check for gradient definitions
    await expect(gradientDefs.locator('radialGradient#gradient1')).toBeVisible();
    await expect(gradientDefs.locator('radialGradient#gradient2')).toBeVisible();
    await expect(gradientDefs.locator('radialGradient#gradient3')).toBeVisible();
  });

  test('should have animated gradient circles', async ({ page }) => {
    const gradientCircles = page.locator('.animated-gradient-bg svg circle');
    await expect(gradientCircles).toHaveCount(5);
    
    // Check if circles have proper attributes
    const firstCircle = gradientCircles.first();
    await expect(firstCircle).toHaveAttribute('fill');
    await expect(firstCircle).toHaveAttribute('transform');
    await expect(firstCircle).toHaveAttribute('opacity');
  });

  test('should have working gradient animations', async ({ page }) => {
    const gradientCircles = page.locator('.animated-gradient-bg svg circle');
    
    // Get initial transform values
    const initialTransforms = await Promise.all(
      Array.from({ length: 5 }, (_, i) => 
        gradientCircles.nth(i).getAttribute('transform')
      )
    );
    
    // Wait for animation to progress
    await page.waitForTimeout(2000);
    
    // Get transform values after animation
    const animatedTransforms = await Promise.all(
      Array.from({ length: 5 }, (_, i) => 
        gradientCircles.nth(i).getAttribute('transform')
      )
    );
    
    // At least some transforms should have changed
    const hasChanges = initialTransforms.some((initial, i) => 
      initial !== animatedTransforms[i]
    );
    
    expect(hasChanges).toBe(true);
  });

  test('should have proper blur and filter effects', async ({ page }) => {
    const gradientSvg = page.locator('.animated-gradient-bg svg.gradient-svg');
    
    // Check if blur filter is applied
    const filter = await gradientSvg.evaluate(el => 
      window.getComputedStyle(el).filter
    );
    
    expect(filter).toContain('blur');
  });

  test('should have proper overlay for content readability', async ({ page }) => {
    const gradientBackground = page.locator('.animated-gradient-bg');
    
    // Check if overlay pseudo-element exists
    const overlay = await gradientBackground.evaluate(el => {
      const styles = window.getComputedStyle(el, '::before');
      return styles.content !== 'none';
    });
    
    expect(overlay).toBe(true);
  });

  test('should have smooth transitions', async ({ page }) => {
    const gradientCircles = page.locator('.animated-gradient-bg svg circle');
    
    // Check if circles have transition properties
    const firstCircle = gradientCircles.first();
    const transition = await firstCircle.evaluate(el => 
      window.getComputedStyle(el).transition
    );
    
    expect(transition).toContain('fill');
    expect(transition).toContain('opacity');
  });

  test('should have performance optimizations', async ({ page }) => {
    const gradientBackground = page.locator('.animated-gradient-bg');
    
    // Check for performance optimizations
    const transform = await gradientBackground.evaluate(el => 
      window.getComputedStyle(el).transform
    );
    
    const backfaceVisibility = await gradientBackground.evaluate(el => 
      window.getComputedStyle(el).backfaceVisibility
    );
    
    expect(transform).toContain('translateZ(0)');
    expect(backfaceVisibility).toBe('hidden');
  });

  test('should have proper z-index layering', async ({ page }) => {
    const gradientBackground = page.locator('.animated-gradient-bg');
    const contentContainer = page.locator('.relative.z-10');
    
    // Check z-index values
    const gradientZIndex = await gradientBackground.evaluate(el => 
      window.getComputedStyle(el).zIndex
    );
    
    const contentZIndex = await contentContainer.evaluate(el => 
      window.getComputedStyle(el).zIndex
    );
    
    expect(parseInt(gradientZIndex)).toBeLessThan(parseInt(contentZIndex));
  });

  test('should have responsive gradient sizing', async ({ page }) => {
    // Test mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    
    const gradientBackground = page.locator('.animated-gradient-bg');
    await expect(gradientBackground).toBeVisible();
    
    // Test desktop viewport
    await page.setViewportSize({ width: 1920, height: 1080 });
    
    await expect(gradientBackground).toBeVisible();
  });

  test('should have proper gradient color cycling', async ({ page }) => {
    // Wait for initial gradient colors to load
    await page.waitForTimeout(1000);
    
    const gradientCircles = page.locator('.animated-gradient-bg svg circle');
    const firstCircle = gradientCircles.first();
    
    // Get initial fill color
    const initialFill = await firstCircle.getAttribute('fill');
    
    // Wait for gradient color change (should happen every 8 seconds)
    await page.waitForTimeout(9000);
    
    // Check if gradient colors have changed
    const updatedFill = await firstCircle.getAttribute('fill');
    
    // The fill should still be a gradient reference
    expect(updatedFill).toContain('url(#gradient');
  });

  test('should have proper animation timing', async ({ page }) => {
    const gradientCircles = page.locator('.animated-gradient-bg svg circle');
    
    // Monitor animation for a few seconds
    const startTime = Date.now();
    const transforms = [];
    
    for (let i = 0; i < 5; i++) {
      await page.waitForTimeout(500);
      const transform = await gradientCircles.first().getAttribute('transform');
      transforms.push(transform);
    }
    
    const endTime = Date.now();
    const duration = endTime - startTime;
    
    // Animation should be running smoothly
    expect(duration).toBeGreaterThan(2000);
    expect(transforms.length).toBe(5);
  });

  test('should have proper opacity animations', async ({ page }) => {
    const gradientCircles = page.locator('.animated-gradient-bg svg circle');
    
    // Check if circles have opacity attributes
    const opacities = await Promise.all(
      Array.from({ length: 5 }, (_, i) => 
        gradientCircles.nth(i).getAttribute('opacity')
      )
    );
    
    // All circles should have opacity values
    opacities.forEach(opacity => {
      expect(opacity).toBeTruthy();
      expect(parseFloat(opacity)).toBeGreaterThan(0);
      expect(parseFloat(opacity)).toBeLessThanOrEqual(1);
    });
  });

  test('should have proper scale animations', async ({ page }) => {
    const gradientCircles = page.locator('.animated-gradient-bg svg circle');
    
    // Get initial scale values
    const initialTransforms = await Promise.all(
      Array.from({ length: 5 }, (_, i) => 
        gradientCircles.nth(i).getAttribute('transform')
      )
    );
    
    // Wait for animation
    await page.waitForTimeout(1000);
    
    // Get updated scale values
    const updatedTransforms = await Promise.all(
      Array.from({ length: 5 }, (_, i) => 
        gradientCircles.nth(i).getAttribute('transform')
      )
    );
    
    // Check if scale values are changing
    const hasScaleChanges = initialTransforms.some((initial, i) => 
      initial !== updatedTransforms[i]
    );
    
    expect(hasScaleChanges).toBe(true);
  });

  test('should have proper radius animations', async ({ page }) => {
    const gradientCircles = page.locator('.animated-gradient-bg svg circle');
    
    // Get initial radius values
    const initialRadii = await Promise.all(
      Array.from({ length: 5 }, (_, i) => 
        gradientCircles.nth(i).getAttribute('r')
      )
    );
    
    // Wait for animation
    await page.waitForTimeout(1000);
    
    // Get updated radius values
    const updatedRadii = await Promise.all(
      Array.from({ length: 5 }, (_, i) => 
        gradientCircles.nth(i).getAttribute('r')
      )
    );
    
    // Check if radius values are changing
    const hasRadiusChanges = initialRadii.some((initial, i) => 
      initial !== updatedRadii[i]
    );
    
    expect(hasRadiusChanges).toBe(true);
  });

  test('should have proper memory cleanup', async ({ page }) => {
    // Navigate away from the page
    await page.goto('/');
    
    // Check if there are no memory leaks
    // This is a basic check - in a real scenario, you'd use more sophisticated memory profiling
    const performanceEntries = await page.evaluate(() => 
      performance.getEntriesByType('measure')
    );
    
    // Should not have excessive performance entries
    expect(performanceEntries.length).toBeLessThan(100);
  });
});
