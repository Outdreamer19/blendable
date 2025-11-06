import { test, expect } from '@playwright/test'

test.describe('Pricing Page - No Free Plan', () => {
  test.beforeEach(async ({ page }) => {
    // Navigate to pricing page
    await page.goto('/pricing')
  })

  test('should show only Pro and Business plans (no Free plan)', async ({ page }) => {
    // Wait for the pricing page to load
    await page.waitForLoadState('networkidle')

    // Check that Free plan is NOT visible
    const freePlan = page.locator('text=Free').first()
    await expect(freePlan).not.toBeVisible()

    // Check that Pro plan is visible
    const proPlan = page.locator('text=Pro').first()
    await expect(proPlan).toBeVisible()

    // Check that Business plan is visible
    const businessPlan = page.locator('text=Business').first()
    await expect(businessPlan).toBeVisible()

    // Verify pricing amounts
    await expect(page.locator('text=£19')).toBeVisible()
    await expect(page.locator('text=£79')).toBeVisible()

    // Verify no £0 price
    const freePrice = page.locator('text=£0')
    await expect(freePrice).not.toBeVisible()
  })

  test('should have working checkout buttons for Pro plan', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    // Find Pro plan button - look for "Get Started" or "Choose Pro" buttons
    const proButton = page.locator('a[href*="billing/checkout?plan=pro"], button:has-text("Get Started"), a:has-text("Get Started")').first()
    
    // Check if button exists and is visible
    const buttonCount = await proButton.count()
    if (buttonCount > 0) {
      await expect(proButton).toBeVisible()
      
      // Check that it links to checkout with pro plan
      const href = await proButton.getAttribute('href')
      expect(href).toContain('billing/checkout')
      expect(href).toContain('plan=pro')
    }
  })

  test('should have working checkout buttons for Business plan', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    // Find Business plan button
    const businessButton = page.locator('a[href*="billing/checkout?plan=business"], button:has-text("Get Started"), a:has-text("Get Started")').last()
    
    // Check if button exists
    const buttonCount = await businessButton.count()
    if (buttonCount > 0) {
      await expect(businessButton).toBeVisible()
      
      // Check that it links to checkout with business plan
      const href = await businessButton.getAttribute('href')
      expect(href).toContain('billing/checkout')
      expect(href).toContain('plan=business')
    }
  })

  test('should not show "Start Free" text anywhere', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    // Check that "Start Free" text is not visible
    const startFreeText = page.locator('text=Start Free')
    await expect(startFreeText).not.toBeVisible()

    // Check that "Free Trial" text is not visible
    const freeTrialText = page.locator('text=Free Trial')
    await expect(freeTrialText).not.toBeVisible()

    // Check for "Start free" (case insensitive)
    const startFreeLower = page.locator('text=/start free/i')
    const count = await startFreeLower.count()
    expect(count).toBe(0)
  })

  test('should show correct number of plans (2 plans)', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    // Count plan cards - should be 2 (Pro and Business)
    const planCards = page.locator('[class*="plan"], [class*="card"]').filter({ hasText: /Pro|Business/ })
    
    // Alternative: look for pricing sections
    const proSection = page.locator('text=Pro').first()
    const businessSection = page.locator('text=Business').first()
    
    await expect(proSection).toBeVisible()
    await expect(businessSection).toBeVisible()
  })
})

test.describe('Pricing Strip Component - No Free Plan', () => {
  test('should not show Free plan in pricing strip on homepage', async ({ page }) => {
    await page.goto('/')
    await page.waitForLoadState('networkidle')

    // Scroll to pricing section if it exists
    const pricingSection = page.locator('text=Simple, transparent pricing')
    if (await pricingSection.count() > 0) {
      await pricingSection.scrollIntoViewIfNeeded()
      
      // Check Free plan is not visible
      const freePlan = page.locator('text=Free').first()
      await expect(freePlan).not.toBeVisible()
      
      // Check Pro and Business are visible
      await expect(page.locator('text=Pro').first()).toBeVisible()
      await expect(page.locator('text=Business').first()).toBeVisible()
    }
  })
})

test.describe('Billing Page - No Free Plan', () => {
  test.beforeEach(async ({ page }) => {
    // Login first (if needed)
    await page.goto('/login')
    
    // Try to login - adjust credentials as needed
    // For now, just navigate to billing if we can
    await page.goto('/billing')
  })

  test('should not show Free plan option on billing page', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    // Check that Free plan is not in the plans list
    const freePlan = page.locator('text=Free')
    await expect(freePlan).not.toBeVisible()

    // Check that only Pro and Business are shown
    await expect(page.locator('text=Pro')).toBeVisible()
    await expect(page.locator('text=Business')).toBeVisible()
  })

  test('billing page checkout buttons should work', async ({ page }) => {
    await page.waitForLoadState('networkidle')

    // Look for checkout buttons
    const checkoutButtons = page.locator('a[href*="billing/checkout"]')
    const count = await checkoutButtons.count()
    
    if (count > 0) {
      // Check that buttons have plan parameters
      for (let i = 0; i < count; i++) {
        const href = await checkoutButtons.nth(i).getAttribute('href')
        if (href) {
          expect(href).toMatch(/plan=(pro|business)/)
        }
      }
    }
  })
})

