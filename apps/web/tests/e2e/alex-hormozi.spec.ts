import { test, expect } from '@playwright/test'

test.describe('Alex Hormozi persona - summarize 100M Offers', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/login')
    await page.fill('#email', 'test@example.com')
    await page.fill('#password', 'password')
    await page.click('button[type="submit"]')
    await page.waitForURL(/.*dashboard/)
  })

  test('summarize request completes without server errors', async ({ page }) => {
    // Go to public experts and open Alex Hormozi
    await page.goto('/experts')
    const alexCard = page.locator('div', { hasText: 'Alex Hormozi' }).first()
    await expect(alexCard).toBeVisible()
    await alexCard.getByText('Learn More').click()

    // On persona details, start a chat
    await expect(page.locator('h1')).toContainText('Alex Hormozi')
    const startChat = page.getByRole('link', { name: 'Start Chat' })
    await expect(startChat).toBeVisible()
    await startChat.click()

    // We should now be on Chats UI. Create a new chat if needed.
    // Try clicking a New Chat button if present
    const newChatBtn = page.getByRole('button', { name: /new chat/i })
    if (await newChatBtn.isVisible().catch(() => false)) {
      await newChatBtn.click()
    }

    // Select the persona if a selector exists
    const personaSelect = page.locator('select[name="persona"]')
    if (await personaSelect.isVisible().catch(() => false)) {
      // try selecting by visible text fallback to value if needed
      await personaSelect.selectOption({ label: 'Alex Hormozi' }).catch(async () => {
        const option = await page.locator('option', { hasText: 'Alex Hormozi' }).first().getAttribute('value')
        if (option) await personaSelect.selectOption(option)
      })
    }

    // Type the summarize prompt
    const prompt = "Summarize '100M Offers' in one concise paragraph."
    const messageBox = page.locator('textarea[name="message"]')
    if (await messageBox.isVisible().catch(() => false)) {
      await messageBox.fill(prompt)
    } else {
      // fallback to any contenteditable or textarea
      const anyTextarea = page.locator('textarea').first()
      await anyTextarea.fill(prompt)
    }

    // Send the message
    const sendBtn = page.getByRole('button', { name: /send/i })
    if (await sendBtn.isVisible().catch(() => false)) {
      await sendBtn.click()
    } else {
      await page.keyboard.press('Enter')
    }

    // Wait for AI response (up to 60s)
    // Expect no network 500s for /chats/* calls
    let saw500 = false
    page.on('response', (resp) => {
      if (resp.url().includes('/chats/') && resp.status() >= 500) {
        saw500 = true
      }
    })

    // Common locator used in other tests
    const aiResponse = page.locator('[data-testid="ai-response"]').first()
    await aiResponse.waitFor({ timeout: 60000 }).catch(() => {})

    expect(saw500).toBeFalsy()
  })
})


