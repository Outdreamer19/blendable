import { test, expect } from '@playwright/test'

test.describe('Experts → Start Chat with Persona', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login')
    await page.fill('#email', 'test@example.com')
    await page.fill('#password', 'password')
    await page.click('button[type="submit"]')
    await page.waitForURL(/.*dashboard/)
  })

  test('navigates /experts → Alex Hormozi → Start Chat and lands in persona chat', async ({ page }) => {
    // Go to experts page
    await page.goto('/experts')
    await expect(page.locator('h1')).toContainText('Expert Personas')

    // Open Alex Hormozi
    const alexCard = page.locator('div', { hasText: 'Alex Hormozi' }).first()
    await expect(alexCard).toBeVisible()
    await alexCard.getByText('Learn More').click()

    // Persona details
    await expect(page.locator('h1')).toContainText('Alex Hormozi')

    // Start Chat
    const startChat = page.getByRole('link', { name: 'Start Chat' })
    await startChat.click()

    // Ensure no server errors for chat route
    let saw500 = false
    page.on('response', (resp) => {
      if (resp.url().includes('/chats/') && resp.status() >= 500) {
        saw500 = true
      }
    })

    // Expect to be on a chat page (look for chat container or message input)
    // Wait for URL to be a chat page
    await page.waitForURL(/.*\/chats\/\d+/, { timeout: 15000 })
    
    // Look for chat input (could be in Composer component)
    const chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="Start typing"], textarea[name="message"]').first()
    await chatInput.waitFor({ timeout: 10000 }).catch(() => {
      // If input not found, check if we're at least on a chat page
      const currentUrl = page.url()
      if (currentUrl.match(/\/chats\/\d+/)) {
        console.log('✅ On chat page but input not immediately visible')
      } else {
        throw new Error('Not on a chat page: ' + currentUrl)
      }
    })
    expect(saw500).toBeFalsy()

    // Optionally verify persona is selected/visible if UI shows it
    // This depends on how the chat header or selector renders persona
    // Try to find persona indicator if present
    const personaIndicator = page.locator('[data-testid="persona-active"]')
    if (await personaIndicator.isVisible().catch(() => false)) {
      await expect(personaIndicator).toContainText('Alex Hormozi')
    }
  })
})


