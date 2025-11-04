import { test, expect } from '@playwright/test'

test.describe('Authenticated Personas', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login')
    await page.fill('#email', 'test@example.com')
    await page.fill('#password', 'password')
    await page.click('button[type="submit"]')
    await page.waitForURL(/.*dashboard/)
  })

  test('persona show page loads without 500', async ({ page }) => {
    // Go to personas index (authenticated)
    await page.goto('/personas')

    // Ensure page renders
    await expect(page.locator('h2')).toContainText('Personas')

    // Click first persona show link
    const personaLinks = page.locator('a[href^="/personas/"]')
    const count = await personaLinks.count()

    // If none exist, skip test gracefully
    test.skip(count === 0, 'No personas available to test')

    // Intercept responses to ensure no 500
    let got500 = false
    page.on('response', (resp) => {
      const url = resp.url()
      if (url.includes('/personas/') && resp.status() >= 500) {
        got500 = true
      }
    })

    await personaLinks.first().click()
    await page.waitForLoadState('networkidle')

    // Basic assertions: page has header and no 500
    await expect(page.locator('h1')).toBeVisible()
    expect(got500).toBeFalsy()
  })
})


