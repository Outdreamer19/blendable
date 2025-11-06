import { test, expect } from '@playwright/test'

test.describe('Navbar Transition Tests', () => {
  test('should show smooth width transition when scrolling', async ({ page }) => {
    await page.goto('/')
    
    // Wait for page to load
    await page.waitForLoadState('networkidle')
    
    // Get initial navbar width
    const navbar = page.locator('header > div:first-child')
    const initialWidth = await navbar.evaluate(el => el.getBoundingClientRect().width)
    
    console.log('Initial navbar width:', initialWidth)
    
    // Scroll down to trigger transition
    await page.evaluate(() => window.scrollTo(0, 900))
    await page.waitForTimeout(100) // Small delay to let transition start
    
    // Check if width is changing (should be around 700px when scrolled)
    const scrolledWidth = await navbar.evaluate(el => el.getBoundingClientRect().width)
    console.log('Scrolled navbar width:', scrolledWidth)
    
    // The width should be different (smaller when scrolled)
    expect(scrolledWidth).toBeLessThan(initialWidth)
    
    // Scroll back up
    await page.evaluate(() => window.scrollTo(0, 0))
    await page.waitForTimeout(600) // Wait for transition to complete
    
    // Check if width returns to original
    const finalWidth = await navbar.evaluate(el => el.getBoundingClientRect().width)
    console.log('Final navbar width:', finalWidth)
    
    // Should be back to original width
    expect(finalWidth).toBeGreaterThan(scrolledWidth)
  })
  
  test('should show smooth height transition', async ({ page }) => {
    await page.goto('/')
    await page.waitForLoadState('networkidle')
    
    const navbar = page.locator('header > div:first-child > div:last-child')
    
    // Get initial height
    const initialHeight = await navbar.evaluate(el => el.getBoundingClientRect().height)
    console.log('Initial navbar height:', initialHeight)
    
    // Scroll down
    await page.evaluate(() => window.scrollTo(0, 900))
    await page.waitForTimeout(100)
    
    const scrolledHeight = await navbar.evaluate(el => el.getBoundingClientRect().height)
    console.log('Scrolled navbar height:', scrolledHeight)
    
    // Height should be smaller when scrolled
    expect(scrolledHeight).toBeLessThan(initialHeight)
  })
  
  test('should show logo text fade out smoothly', async ({ page }) => {
    await page.goto('/')
    await page.waitForLoadState('networkidle')
    
    const logoText = page.locator('span:has-text("Blendable")')
    
    // Check logo text is visible initially
    await expect(logoText).toBeVisible()
    
    // Scroll down
    await page.evaluate(() => window.scrollTo(0, 900))
    await page.waitForTimeout(100)
    
    // Logo text should fade out
    await expect(logoText).not.toBeVisible()
    
    // Scroll back up
    await page.evaluate(() => window.scrollTo(0, 0))
    await page.waitForTimeout(600)
    
    // Logo text should be visible again
    await expect(logoText).toBeVisible()
  })
})
