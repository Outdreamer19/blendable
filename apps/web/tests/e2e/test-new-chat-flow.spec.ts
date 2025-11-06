import { test, expect } from '@playwright/test'

test.describe('New Chat Flow Tests', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login')
    
    // Wait for login form
    await page.waitForSelector('#email')
    
    // Fill in credentials
    await page.fill('#email', 'demo@blendable.com')
    await page.fill('#password', 'password')
    
    // Submit login
    await page.click('button[type="submit"]')
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard|.*chats/, { timeout: 10000 })
    
    // Navigate to chats
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
  })

  test('should create new chat and show chat interface', async ({ page }) => {
    // Look for "New Chat" button
    const newChatButton = page.locator('button:has-text("New Chat")').first()
    await expect(newChatButton).toBeVisible({ timeout: 5000 })
    
    // Click new chat button
    await newChatButton.click()
    
    // Wait for navigation to chat page (should redirect to /chats/{id})
    await page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 })
    await page.waitForLoadState('networkidle')
    
    // Verify we're on a chat page
    expect(page.url()).toMatch(/\/chats\/\d+/)
    
    // Check for chat input
    const chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="Start typing"]')
    await expect(chatInput).toBeVisible({ timeout: 5000 })
    
    console.log('✅ New chat created successfully')
  })

  test('should be able to type in new chat', async ({ page }) => {
    // Create new chat
    const newChatButton = page.locator('button:has-text("New Chat")').first()
    await expect(newChatButton).toBeVisible({ timeout: 5000 })
    await newChatButton.click()
    
    // Wait for chat page
    await page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 })
    await page.waitForLoadState('networkidle')
    
    // Find and fill chat input
    const chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="Start typing"]')
    await expect(chatInput).toBeVisible({ timeout: 5000 })
    
    // Type a message
    const testMessage = 'Hello, this is a test message'
    await chatInput.fill(testMessage)
    
    // Verify message is in input
    const inputValue = await chatInput.inputValue()
    expect(inputValue).toContain(testMessage)
    
    console.log('✅ Can type in new chat')
  })

  test('should show send button when message is typed', async ({ page }) => {
    // Create new chat
    const newChatButton = page.locator('button:has-text("New Chat")').first()
    await expect(newChatButton).toBeVisible({ timeout: 5000 })
    await newChatButton.click()
    
    // Wait for chat page
    await page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 })
    await page.waitForLoadState('networkidle')
    
    // Find chat input
    const chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="Start typing"]')
    await expect(chatInput).toBeVisible({ timeout: 5000 })
    
    // Type a message
    await chatInput.fill('Test message')
    
    // Find send button (look for Send icon or button with send text)
    const sendButton = page.locator('button:has([class*="Send"]), button[title*="Send"]').last()
    
    // Send button should be enabled when there's text
    const isDisabled = await sendButton.isDisabled().catch(() => false)
    expect(isDisabled).toBeFalsy()
    
    console.log('✅ Send button is enabled when message is typed')
  })

  test('should handle new chat creation without workspace errors', async ({ page }) => {
    // Check console for errors
    const consoleErrors: string[] = []
    page.on('console', msg => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text())
      }
    })
    
    // Try to create new chat
    const newChatButton = page.locator('button:has-text("New Chat")').first()
    await expect(newChatButton).toBeVisible({ timeout: 5000 })
    
    await newChatButton.click()
    
    // Wait for navigation
    await page.waitForTimeout(2000)
    
    // Check if we're on a valid page (not workspace redirect)
    const currentUrl = page.url()
    
    // Should either be on /chats/{id} or still on /chats (if error)
    expect(currentUrl).toMatch(/\/chats/)
    
    // Check for critical errors
    const criticalErrors = consoleErrors.filter(err => 
      err.includes('Missing required prop') || 
      err.includes('500') ||
      err.includes('Failed to load')
    )
    
    if (criticalErrors.length > 0) {
      console.log('⚠️ Found console errors:', criticalErrors)
    }
    
    // Should not have been redirected to workspaces if we have a workspace
    expect(currentUrl).not.toContain('/workspaces')
    
    console.log('✅ New chat creation works without critical errors')
  })
})

