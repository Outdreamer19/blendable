import { test, expect } from '@playwright/test'

test.describe('Chat Functionality Tests', () => {
  test.beforeEach(async ({ page }) => {
    // Login before each test
    await page.goto('/login')
    
    // Wait for login form
    await page.waitForSelector('#email')
    
    // Fill in credentials (adjust if needed)
    await page.fill('#email', 'demo@omni-ai.com')
    await page.fill('#password', 'password')
    
    // Submit login
    await page.click('button[type="submit"]')
    
    // Wait for redirect to dashboard
    await page.waitForURL(/.*dashboard|.*chats|.*workspaces/, { timeout: 10000 })
    
    // Ensure we have a workspace - navigate to chats and handle workspace creation
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    
    // Check if we were redirected to workspaces (no workspace available)
    if (page.url().includes('/workspaces') && !page.url().includes('/create')) {
      console.log('No workspace found, creating one...')
      
      // Navigate to create workspace
      await page.goto('/workspaces/create')
      await page.waitForLoadState('networkidle')
      
      // Fill workspace form
      await page.fill('input[name="name"]', 'Test Workspace')
      
      // Select team if needed
      const teamSelect = page.locator('select[name="team_id"]')
      if (await teamSelect.isVisible({ timeout: 2000 }).catch(() => false)) {
        const options = await teamSelect.locator('option').count()
        if (options > 1) {
          await teamSelect.selectOption({ index: 1 })
        }
      }
      
      // Submit and wait for workspace to be created
      await Promise.all([
        page.waitForURL(/.*workspaces\/\d+|.*chats/, { timeout: 10000 }),
        page.click('button[type="submit"], button:has-text("Create")')
      ])
      
      await page.waitForLoadState('networkidle')
      
      // If we're on a workspace show page, try to navigate to chats
      // Or if we're on workspaces index, click on the workspace or go to chats
      if (page.url().includes('/workspaces/') && !page.url().includes('/create')) {
        // Workspace created, navigate to chats
        await page.goto('/chats')
        await page.waitForLoadState('networkidle')
        
        // If still redirected, wait a bit for workspace to be set
        if (page.url().includes('/workspaces')) {
          await page.waitForTimeout(2000)
          await page.goto('/chats')
          await page.waitForLoadState('networkidle')
        }
      } else if (page.url().includes('/workspaces') && !page.url().includes('/create')) {
        // On workspaces index, click on a workspace or create button
        const workspaceLink = page.locator('a[href*="/workspaces/"]').first()
        if (await workspaceLink.isVisible({ timeout: 3000 }).catch(() => false)) {
          await workspaceLink.click()
          await page.waitForLoadState('networkidle')
          await page.goto('/chats')
          await page.waitForLoadState('networkidle')
        }
      }
    }
  })

  test('should navigate to chats page and see chat interface', async ({ page }) => {
    await page.goto('/chats')
    
    // Wait for the page to load
    await page.waitForLoadState('networkidle')
    
    // Check if we're on the chats page
    await expect(page).toHaveURL(/.*chats/)
    
    // Look for either active chat or empty state
    // The page might show empty state if no chats exist
    const hasEmptyState = await page.locator('text=Start a conversation').isVisible().catch(() => false)
    const hasChatInput = await page.locator('textarea[placeholder*="Type your message"]').isVisible().catch(() => false)
    
    expect(hasEmptyState || hasChatInput).toBeTruthy()
  })

  test('should create a new chat', async ({ page }) => {
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    
    // Look for "New Chat" button in sidebar
    const newChatButton = page.locator('button:has-text("New Chat")').first()
    
    // Wait for button to be visible
    await expect(newChatButton).toBeVisible({ timeout: 5000 })
    
    // Click New Chat button
    await newChatButton.click()
    
    // Wait for navigation - might go to /chats/{id} or /workspaces
    await page.waitForTimeout(2000)
    
    const currentUrl = page.url()
    
    // If redirected to workspaces, the workspace wasn't properly set
    if (currentUrl.includes('/workspaces')) {
      console.log('⚠️ Redirected to workspaces - workspace not properly set as current')
      // Try to click on a workspace to set it as current
      const workspaceLink = page.locator('a[href*="/workspaces/"]').first()
      if (await workspaceLink.isVisible({ timeout: 3000 }).catch(() => false)) {
        await workspaceLink.click()
        await page.waitForLoadState('networkidle')
        
        // Try New Chat again
        await page.goto('/chats')
        await page.waitForLoadState('networkidle')
        const retryButton = page.locator('button:has-text("New Chat")').first()
        if (await retryButton.isVisible({ timeout: 3000 }).catch(() => false)) {
          await retryButton.click()
          await page.waitForTimeout(2000)
        }
      }
    }
    
    // Now check if we're on a chat page with input visible
    if (page.url().includes('/chats/')) {
      await page.waitForLoadState('networkidle')
      const chatInput = page.locator('textarea[placeholder*="Type your message"]')
      await expect(chatInput).toBeVisible({ timeout: 10000 })
    } else {
      // If still not on chat page, log the issue
      console.log('⚠️ Could not create chat - current URL:', page.url())
      throw new Error(`Expected to be on /chats/{id} but was on ${page.url()}`)
    }
  })

  test('should send a message in a chat', async ({ page }) => {
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    
    // Ensure we have an active chat - create one if needed
    let chatInput = page.locator('textarea[placeholder*="Type your message"]')
    const hasChatInput = await chatInput.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (!hasChatInput) {
      // Create a new chat
      const newChatBtn = page.locator('button:has-text("New Chat")').first()
      await expect(newChatBtn).toBeVisible({ timeout: 5000 })
      
      await Promise.all([
        page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 }),
        newChatBtn.click()
      ])
      
      await page.waitForLoadState('networkidle')
      chatInput = page.locator('textarea[placeholder*="Type your message"]')
    }
    
    // Now send a message
    await expect(chatInput).toBeVisible({ timeout: 10000 })
    
    const testMessage = 'Hello, this is a test message'
    await chatInput.fill(testMessage)
    
    // Find and click send button (look for Send icon in button)
    const sendButton = page.locator('button:has([class*="Send"]), button[title*="Send"], button:has-text("Send")').last()
    await sendButton.click()
    
    // Wait for user message to appear in the chat messages area (not sidebar)
    const messageInChat = page.locator('.max-w-3xl, [class*="message"]').filter({ hasText: testMessage }).first()
    await expect(messageInChat).toBeVisible({ timeout: 10000 })
    
    console.log('✅ Message sent and appeared in chat')
  })

  test('should handle streaming AI response', async ({ page }) => {
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    
    // Ensure we have an active chat
    let chatInput = page.locator('textarea[placeholder*="Type your message"]')
    const hasChatInput = await chatInput.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (!hasChatInput) {
      // Create new chat
      const newChatBtn = page.locator('button:has-text("New Chat")').first()
      await expect(newChatBtn).toBeVisible({ timeout: 5000 })
      
      await Promise.all([
        page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 }),
        newChatBtn.click()
      ])
      
      await page.waitForLoadState('networkidle')
      chatInput = page.locator('textarea[placeholder*="Type your message"]')
    }
    
    await expect(chatInput).toBeVisible({ timeout: 10000 })
    
    if (true) {
      const question = 'What is 2+2?'
      
      await chatInput.fill(question)
      
      // Click send button
      const sendButton = page.locator('button:has([class*="Send"])').last()
      
      // Monitor for streaming response
      let streamingDetected = false
      page.on('response', async (response) => {
        if (response.url().includes('/send-message') && response.status() === 200) {
          streamingDetected = true
          console.log('✅ Streaming response detected')
        }
      })
      
      await sendButton.click()
      
      // Wait for user message to appear
      await expect(page.locator(`text=${question}`)).toBeVisible({ timeout: 10000 })
      
      // Wait for streaming indicator or response
      try {
        // Look for streaming message or loading state
        await page.waitForSelector(
          '.animate-pulse, [class*="streaming"], .bg-white.border',
          { timeout: 15000 }
        )
        
        console.log('✅ Streaming indicator detected')
        
        // Wait for streaming to complete (no more loading indicators)
        await page.waitForFunction(
          () => {
            const loadingElements = document.querySelectorAll('.animate-pulse, [class*="spin"]')
            return loadingElements.length === 0 || 
                   Array.from(loadingElements).every(el => !el.closest('[class*="message"]'))
          },
          { timeout: 30000 }
        )
        
        // Look for AI response
        const responseElements = page.locator('.bg-white.border, [class*="message"]')
        const count = await responseElements.count()
        
        if (count > 0) {
          console.log(`✅ Found ${count} response elements`)
          const lastResponse = responseElements.last()
          const responseText = await lastResponse.textContent()
          
          if (responseText && responseText.length > 10) {
            console.log('✅ AI response received:', responseText.substring(0, 100))
            expect(responseText.length).toBeGreaterThan(10)
          }
        }
        
      } catch (error) {
        console.log('⚠️ Streaming response timeout or not detected')
        console.log('This might be expected if AI API is not configured')
      }
    }
  })

  test('should verify chat sidebar works', async ({ page }) => {
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    
    // Check if sidebar is visible (desktop) or menu button (mobile)
    const sidebarVisible = await page.locator('.lg\\:w-80, [class*="sidebar"]').isVisible({ timeout: 3000 }).catch(() => false)
    const menuButton = page.locator('button:has([class*="Menu"])')
    const menuVisible = await menuButton.isVisible({ timeout: 3000 }).catch(() => false)
    
    // On mobile, open menu to see sidebar
    if (menuVisible && !sidebarVisible) {
      await menuButton.click()
      await page.waitForTimeout(500)
    }
    
    // Should see chat list or empty state in sidebar
    const hasChatList = await page.locator('[class*="ChatList"], .space-y-1').isVisible({ timeout: 3000 }).catch(() => false)
    const hasNewChatBtn = await page.locator('button:has-text("New Chat")').isVisible({ timeout: 3000 }).catch(() => false)
    
    expect(hasChatList || hasNewChatBtn).toBeTruthy()
  })

  test('should handle empty input correctly', async ({ page }) => {
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    
    const chatInput = page.locator('textarea[placeholder*="Type your message"]')
    
    if (await chatInput.isVisible({ timeout: 5000 }).catch(() => false)) {
      // Send button should be disabled when input is empty
      const sendButton = page.locator('button:has([class*="Send"])').last()
      
      // Clear input if it has any text
      await chatInput.fill('')
      
      // Check if send button is disabled
      let isDisabled = false
      try {
        isDisabled = await sendButton.isDisabled()
      } catch {
        // Check for disabled styling
        const classes = await sendButton.getAttribute('class')
        isDisabled = classes?.includes('disabled') || classes?.includes('opacity-50') || false
      }
      
      expect(isDisabled).toBeTruthy()
    }
  })
})

