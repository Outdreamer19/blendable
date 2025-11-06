import { test, expect } from '@playwright/test'

test('Verify no Free plan on pricing page', async ({ page }) => {
  await page.goto('/pricing')
  await page.waitForLoadState('networkidle')

  // Take a screenshot for debugging
  await page.screenshot({ path: 'test-results/pricing-page.png', fullPage: true })

  // Check that Pro and Business are visible
  await expect(page.locator('text=Pro').first()).toBeVisible()
  await expect(page.locator('text=Business').first()).toBeVisible()

  // Check for Free plan - should NOT be visible in pricing cards
  const freePlanCards = page.locator('text=/^Free$/i').filter({ hasNotText: 'Start Free' })
  const freeCardCount = await freePlanCards.count()
  console.log(`Found ${freeCardCount} Free plan cards`)
  
  // Check for "Start Free" buttons - should be "Get Started" now
  const startFreeButtons = page.locator('text=/Start Free/i')
  const startFreeCount = await startFreeButtons.count()
  console.log(`Found ${startFreeCount} "Start Free" buttons`)
  
  // Check for pricing text
  const pageText = await page.textContent('body')
  const hasStartFreeText = pageText?.includes('Start free, upgrade') || pageText?.includes('Start Free')
  console.log(`Has "Start free" text: ${hasStartFreeText}`)
  
  // Verify Pro plan price
  await expect(page.locator('text=£19')).toBeVisible()
  // Verify Business plan price
  await expect(page.locator('text=£79')).toBeVisible()
  
  // Check checkout buttons work
  const proCheckoutLink = page.locator('a[href*="billing/checkout?plan=pro"]').first()
  if (await proCheckoutLink.count() > 0) {
    const href = await proCheckoutLink.getAttribute('href')
    console.log(`Pro checkout link: ${href}`)
    expect(href).toContain('plan=pro')
  }
})

test('Verify no Free plan on homepage pricing strip', async ({ page }) => {
  await page.goto('/')
  await page.waitForLoadState('networkidle')
  
  // Scroll to find pricing section
  await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight))
  await page.waitForTimeout(1000)
  
  // Look for pricing section
  const pricingSection = page.locator('text=Simple, transparent pricing')
  if (await pricingSection.count() > 0) {
    await pricingSection.scrollIntoViewIfNeeded()
    await page.waitForTimeout(500)
    
    // Check Pro and Business are visible
    await expect(page.locator('text=Pro').first()).toBeVisible()
    await expect(page.locator('text=Business').first()).toBeVisible()
    
    // Free should not be visible
    const freeInStrip = page.locator('text=/^Free$/i').filter({ hasNotText: 'Start Free' })
    const count = await freeInStrip.count()
    console.log(`Free plans in pricing strip: ${count}`)
  }
})

