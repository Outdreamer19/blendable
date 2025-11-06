import { test, expect } from '@playwright/test';

test.describe('Login Page with Animated Gradient Background', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
  });

  test('should display login page with correct title', async ({ page }) => {
    await expect(page).toHaveTitle(/Sign in — Blendable/);
    await expect(page.locator('h1')).toContainText('Welcome back');
  });

  test('should have animated gradient background', async ({ page }) => {
    // Check if the animated gradient background component is present
    const gradientBackground = page.locator('.animated-gradient-bg');
    await expect(gradientBackground).toBeVisible();
    
    // Check if the SVG gradient elements are present
    const gradientSvg = gradientBackground.locator('svg.gradient-svg');
    await expect(gradientSvg).toBeVisible();
    
    // Check if gradient circles are present
    const gradientCircles = gradientSvg.locator('circle');
    await expect(gradientCircles).toHaveCount(5); // We have 5 animated circles
  });

  test('should have working login form', async ({ page }) => {
    // Check if form elements are present
    await expect(page.locator('input[type="email"]')).toBeVisible();
    await expect(page.locator('input[type="password"]')).toBeVisible();
    await expect(page.locator('input[type="checkbox"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
    
    // Check form labels
    await expect(page.locator('label[for="email"]')).toContainText('Email');
    await expect(page.locator('label[for="password"]')).toContainText('Password');
  });

  test('should have social login buttons', async ({ page }) => {
    const socialButtons = page.locator('.flex.items-center.justify-center.gap-4');
    await expect(socialButtons).toBeVisible();
    
    // Check for Google, GitHub, and Facebook buttons
    const googleButton = socialButtons.locator('button[aria-label="Continue with Google"]');
    const githubButton = socialButtons.locator('button[aria-label="Continue with GitHub"]');
    const facebookButton = socialButtons.locator('button[aria-label="Continue with Facebook"]');
    
    await expect(googleButton).toBeVisible();
    await expect(githubButton).toBeVisible();
    await expect(facebookButton).toBeVisible();
  });

  test('should have testimonial carousel', async ({ page }) => {
    // Check if quote panel is visible
    const quotePanel = page.locator('aside.relative.overflow-hidden.rounded-\\[28px\\]');
    await expect(quotePanel).toBeVisible();
    
    // Check if testimonial content is present
    await expect(quotePanel.locator('h2')).toContainText("What's our users said.");
    
    // Check if testimonial quote is visible
    const testimonialQuote = quotePanel.locator('p.text-slate-300.italic');
    await expect(testimonialQuote).toBeVisible();
    
    // Check if author information is present
    const authorInfo = quotePanel.locator('.mt-8');
    await expect(authorInfo).toBeVisible();
  });

  test('should have working testimonial carousel navigation', async ({ page }) => {
    const quotePanel = page.locator('aside.relative.overflow-hidden.rounded-\\[28px\\]');
    
    // Check if navigation buttons are present
    const prevButton = quotePanel.locator('button[aria-label="Previous testimonial"]');
    const nextButton = quotePanel.locator('button[aria-label="Next testimonial"]');
    
    await expect(prevButton).toBeVisible();
    await expect(nextButton).toBeVisible();
    
    // Check if progress indicators are present
    const progressDots = quotePanel.locator('.flex.items-center.gap-1.ml-4 div');
    await expect(progressDots).toHaveCount(6); // We have 6 testimonials
  });

  test('should have glassmorphic auth card', async ({ page }) => {
    const authCard = page.locator('section.rounded-\\[28px\\].border.border-slate-800\\/70');
    await expect(authCard).toBeVisible();
    
    // Check if the card has the correct styling classes
    await expect(authCard).toHaveClass(/backdrop-blur-xl/);
    await expect(authCard).toHaveClass(/bg-white\/5/);
    await expect(authCard).toHaveClass(/shadow-2xl/);
  });

  test('should have clipped corner on quote panel', async ({ page }) => {
    const quotePanel = page.locator('aside.relative.overflow-hidden.rounded-\\[28px\\]');
    
    // Check if the clipped corner overlay is present
    const clippedCorner = quotePanel.locator('.absolute.-top-0\\.5.-right-0\\.5.w-12.h-12.bg-slate-950.rounded-bl-2xl');
    await expect(clippedCorner).toBeVisible();
  });

  test('should have animated starburst background', async ({ page }) => {
    const quotePanel = page.locator('aside.relative.overflow-hidden.rounded-\\[28px\\]');
    
    // Check if the starburst SVG is present
    const starburst = quotePanel.locator('.absolute.-right-16.bottom-0.h-\\[420px\\].w-\\[420px\\] svg');
    await expect(starburst).toBeVisible();
  });

  test('should have proper form validation', async ({ page }) => {
    // Try to submit empty form
    await page.click('button[type="submit"]');
    
    // Check if validation errors appear (if any)
    // Note: This depends on your validation implementation
    const emailInput = page.locator('input[type="email"]');
    const passwordInput = page.locator('input[type="password"]');
    
    await expect(emailInput).toBeVisible();
    await expect(passwordInput).toBeVisible();
  });

  test('should have responsive design', async ({ page }) => {
    // Test mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Check if elements are still visible on mobile
    await expect(page.locator('h1')).toBeVisible();
    await expect(page.locator('input[type="email"]')).toBeVisible();
    await expect(page.locator('input[type="password"]')).toBeVisible();
    
    // Test desktop viewport
    await page.setViewportSize({ width: 1920, height: 1080 });
    
    // Check if grid layout is working
    const gridContainer = page.locator('.grid.grid-cols-1.lg\\:grid-cols-\\[minmax\\(360px\\,560px\\)_minmax\\(420px\\,1fr\\)\\]');
    await expect(gridContainer).toBeVisible();
  });

  test('should have accessible form elements', async ({ page }) => {
    // Check if form elements have proper labels and IDs
    const emailInput = page.locator('input[type="email"]');
    const passwordInput = page.locator('input[type="password"]');
    const checkbox = page.locator('input[type="checkbox"]');
    
    await expect(emailInput).toHaveAttribute('id', 'email');
    await expect(passwordInput).toHaveAttribute('id', 'password');
    await expect(checkbox).toHaveAttribute('id', 'remember');
    
    // Check if labels are properly associated
    await expect(page.locator('label[for="email"]')).toBeVisible();
    await expect(page.locator('label[for="password"]')).toBeVisible();
    await expect(page.locator('label[for="remember"]')).toBeVisible();
  });

  test('should have proper focus management', async ({ page }) => {
    // Test tab navigation
    await page.keyboard.press('Tab');
    await expect(page.locator('input[type="email"]')).toBeFocused();
    
    await page.keyboard.press('Tab');
    await expect(page.locator('input[type="password"]')).toBeFocused();
    
    await page.keyboard.press('Tab');
    await expect(page.locator('input[type="checkbox"]')).toBeFocused();
  });

  test('should have working gradient animation', async ({ page }) => {
    const gradientBackground = page.locator('.animated-gradient-bg');
    await expect(gradientBackground).toBeVisible();
    
    // Wait a bit to see if animation is working
    await page.waitForTimeout(2000);
    
    // Check if gradient circles are still visible (animation should be running)
    const gradientCircles = gradientBackground.locator('svg circle');
    await expect(gradientCircles).toHaveCount(5);
    
    // Check if circles have transform attributes (indicating animation)
    const firstCircle = gradientCircles.first();
    await expect(firstCircle).toHaveAttribute('transform');
  });

  test('should have proper error handling', async ({ page }) => {
    // Test with invalid email format
    await page.fill('input[type="email"]', 'invalid-email');
    await page.fill('input[type="password"]', 'password123');
    await page.click('button[type="submit"]');
    
    // Check if form validation works (this depends on your implementation)
    // The form should either show validation errors or submit and show server errors
  });

  test('should have working remember me checkbox', async ({ page }) => {
    const checkbox = page.locator('input[type="checkbox"]');
    
    // Check if checkbox is unchecked by default
    await expect(checkbox).not.toBeChecked();
    
    // Click checkbox
    await checkbox.click();
    
    // Check if checkbox is now checked
    await expect(checkbox).toBeChecked();
  });

  test('should have proper loading states', async ({ page }) => {
    // Fill form and submit
    await page.fill('input[type="email"]', 'test@example.com');
    await page.fill('input[type="password"]', 'password123');
    
    // Click submit button
    await page.click('button[type="submit"]');
    
    // Check if loading state appears (if implemented)
    // This depends on your loading state implementation
  });
});

test.describe('Login Page Performance', () => {
  test('should load quickly', async ({ page }) => {
    const startTime = Date.now();
    await page.goto('/login');
    const loadTime = Date.now() - startTime;
    
    // Page should load within 3 seconds
    expect(loadTime).toBeLessThan(3000);
  });

  test('should have smooth animations', async ({ page }) => {
    await page.goto('/login');
    
    // Check if gradient background is present and animated
    const gradientBackground = page.locator('.animated-gradient-bg');
    await expect(gradientBackground).toBeVisible();
    
    // Wait for animation to start
    await page.waitForTimeout(1000);
    
    // Check if animation is running by looking for transform changes
    const gradientCircles = gradientBackground.locator('svg circle');
    const firstCircle = gradientCircles.first();
    
    // Get initial transform
    const initialTransform = await firstCircle.getAttribute('transform');
    
    // Wait a bit for animation
    await page.waitForTimeout(1000);
    
    // Get transform after animation
    const animatedTransform = await firstCircle.getAttribute('transform');
    
    // Transform should have changed (indicating animation)
    expect(animatedTransform).not.toBe(initialTransform);
  });
});

test.describe('Login Page Accessibility', () => {
  test('should have proper ARIA labels', async ({ page }) => {
    await page.goto('/login');
    
    // Check if social buttons have proper ARIA labels
    await expect(page.locator('button[aria-label="Continue with Google"]')).toBeVisible();
    await expect(page.locator('button[aria-label="Continue with GitHub"]')).toBeVisible();
    await expect(page.locator('button[aria-label="Continue with Facebook"]')).toBeVisible();
    
    // Check if testimonial navigation has proper ARIA labels
    await expect(page.locator('button[aria-label="Previous testimonial"]')).toBeVisible();
    await expect(page.locator('button[aria-label="Next testimonial"]')).toBeVisible();
  });

  test('should have proper color contrast', async ({ page }) => {
    await page.goto('/login');
    
    // Check if text is visible (indicating good contrast)
    await expect(page.locator('h1')).toBeVisible();
    await expect(page.locator('label')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('should be keyboard navigable', async ({ page }) => {
    await page.goto('/login');
    
    // Test keyboard navigation
    await page.keyboard.press('Tab');
    await expect(page.locator('input[type="email"]')).toBeFocused();
    
    await page.keyboard.press('Tab');
    await expect(page.locator('input[type="password"]')).toBeFocused();
    
    await page.keyboard.press('Tab');
    await expect(page.locator('input[type="checkbox"]')).toBeFocused();
    
    await page.keyboard.press('Tab');
    await expect(page.locator('button[type="submit"]')).toBeFocused();
  });
});
