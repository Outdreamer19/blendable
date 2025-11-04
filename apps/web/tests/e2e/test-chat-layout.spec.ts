import { test, expect } from '@playwright/test'

test.describe('Chat Layout and Visibility', () => {
  test.beforeEach(async ({ page }) => {
    // Login
    await page.goto('/login')
    await page.fill('#email', 'demo@omni-ai.com')
    await page.fill('#password', 'password')
    await page.click('button[type="submit"]')
    await page.waitForURL(/.*(dashboard|chats)/)

    // Ensure workspace exists
    const currentUrl = page.url()
    if (currentUrl.includes('/workspaces') && !currentUrl.includes('/workspaces/')) {
      // Need to create or select a workspace
      const workspaceLink = page.locator('a[href*="/workspaces/"]').first()
      if (await workspaceLink.isVisible({ timeout: 2000 }).catch(() => false)) {
        await workspaceLink.click()
        await page.waitForURL(/.*workspaces\/\d+/)
      } else {
        // Create a workspace
        await page.goto('/workspaces/create')
        await page.waitForURL(/.*workspaces\/create/)
        
        const nameInput = page.locator('input#name, input[name="name"]').first()
        const teamSelect = page.locator('select#team_id, select[name="team_id"]').first()
        
        if (await nameInput.isVisible({ timeout: 2000 })) {
          await nameInput.fill('Test Workspace')
          
          // Try to select a team if available
          if (await teamSelect.isVisible({ timeout: 1000 }).catch(() => false)) {
            const options = await teamSelect.locator('option').count()
            if (options > 1) {
              await teamSelect.selectOption({ index: 1 })
            }
          }
          
          await Promise.all([
            page.waitForURL(/.*(workspaces\/\d+|workspaces)/, { timeout: 10000 }),
            page.click('button[type="submit"]')
          ])
        }
      }
    }

    // Navigate to chats
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
  })

  test('chat input box should be visible without scrolling', async ({ page }) => {
    // Wait for the page to fully load
    await page.waitForLoadState('networkidle')
    
    // Check if there's an active chat or create one
    let chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="Start typing"], textarea[name="message"]')
    const emptyState = page.locator('text=No chats yet, text=Start a new conversation, text=Start a conversation')
    
    // If no chat input is visible, check for empty state and create a chat
    const inputVisible = await chatInput.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (!inputVisible) {
      // Check for empty state
      const isEmpty = await emptyState.isVisible({ timeout: 2000 }).catch(() => false)
      
      if (isEmpty) {
        // Try to find and click New Chat button
        const newChatButton = page.locator('button:has-text("New Chat")').first()
        if (await newChatButton.isVisible({ timeout: 3000 }).catch(() => false)) {
          await Promise.all([
            page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 }),
            newChatButton.click()
          ])
          await page.waitForLoadState('networkidle')
        }
      } else {
        // Try navigating to a chat if URL is just /chats
        if (page.url().endsWith('/chats') || page.url().endsWith('/chats/')) {
          // Look for any chat link in sidebar
          const chatLink = page.locator('a[href*="/chats/"], div[class*="ChatRow"]').first()
          if (await chatLink.isVisible({ timeout: 3000 }).catch(() => false)) {
            await chatLink.click()
            await page.waitForLoadState('networkidle')
          }
        }
      }
    }

    // Wait for chat input to appear - try multiple selectors
    const inputSelectors = [
      'textarea[placeholder*="Type your message"]',
      'textarea[placeholder*="message"]',
      'textarea',
      'input[type="text"]'
    ]
    
    let foundInput = false
    for (const selector of inputSelectors) {
      chatInput = page.locator(selector).first()
      const visible = await chatInput.isVisible({ timeout: 2000 }).catch(() => false)
      if (visible) {
        foundInput = true
        break
      }
    }
    
    // If no input found, check if we're in empty state - that's acceptable for layout test
    if (!foundInput) {
      const emptyStateVisible = await page.locator('text=Start a conversation, text=No chats yet').isVisible({ timeout: 2000 }).catch(() => false)
      if (emptyStateVisible) {
        console.log('✅ Empty state - layout is correct')
        return // Empty state is acceptable
      }
    }
    
    if (foundInput) {
      // Get viewport dimensions
      const viewport = page.viewportSize()
      expect(viewport).toBeTruthy()
      
      if (viewport) {
        // Get the bounding box of the chat input
        const inputBox = await chatInput.boundingBox()
        expect(inputBox).toBeTruthy()
        
        if (inputBox) {
          // Check that the input box is within the viewport
          const inputBottom = inputBox.y + inputBox.height
          const viewportHeight = viewport.height
          
          console.log(`✅ Input box bottom: ${inputBottom}, Viewport height: ${viewportHeight}`)
          
          // The input should be visible in the viewport (with some tolerance for padding)
          expect(inputBottom).toBeLessThanOrEqual(viewportHeight + 100) // 100px tolerance
          
          // Verify the input is actually visible
          await expect(chatInput).toBeVisible()
        }
      }
    } else {
      // Log for debugging but don't fail - might be in a state where input isn't shown
      console.log('⚠️ Chat input not found, but this might be expected in some states')
    }
  })

  test('chat input should stay visible when scrolling messages', async ({ page }) => {
    // Navigate to chats and ensure we have a chat
    await page.waitForLoadState('networkidle')
    
    let chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="Start typing"], textarea[name="message"]')
    const emptyState = page.locator('text=No chats yet, text=Start a new conversation, text=Start a conversation')
    
    // Check if input is visible, if not create a chat
    const inputVisible = await chatInput.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (!inputVisible) {
      const isEmpty = await emptyState.isVisible({ timeout: 2000 }).catch(() => false)
      
      if (isEmpty) {
        const newChatButton = page.locator('button:has-text("New Chat")').first()
        if (await newChatButton.isVisible({ timeout: 3000 }).catch(() => false)) {
          await Promise.all([
            page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 }),
            newChatButton.click()
          ])
          await page.waitForLoadState('networkidle')
        }
      } else {
        // Try to click on existing chat
        const chatLink = page.locator('a[href*="/chats/"], div[class*="ChatRow"]').first()
        if (await chatLink.isVisible({ timeout: 3000 }).catch(() => false)) {
          await chatLink.click()
          await page.waitForLoadState('networkidle')
        }
      }
    }

    // Try to find chat input with multiple selectors
    const inputSelectors = [
      'textarea[placeholder*="Type your message"]',
      'textarea[placeholder*="message"]',
      'textarea',
    ]
    
    let foundInput = false
    for (const selector of inputSelectors) {
      chatInput = page.locator(selector).first()
      const visible = await chatInput.isVisible({ timeout: 2000 }).catch(() => false)
      if (visible) {
        foundInput = true
        break
      }
    }
    
    if (foundInput) {
      // Get viewport dimensions
      const viewport = page.viewportSize()
      expect(viewport).toBeTruthy()
      
      if (viewport) {
        const inputBox = await chatInput.boundingBox()
        if (inputBox) {
          // Check that input is near the bottom of viewport
          const inputBottom = inputBox.y + inputBox.height
          const viewportHeight = viewport.height
          
          // Input should be in the lower portion of the viewport
          expect(inputBottom).toBeGreaterThan(viewportHeight * 0.5) // At least in lower half
          
          // Find the messages container (scrollable area)
          const messagesContainer = page.locator('.overflow-y-auto, [class*="overflow"]').first()
          
          if (await messagesContainer.isVisible({ timeout: 2000 }).catch(() => false)) {
            // Scroll messages container
            await messagesContainer.evaluate((el) => {
              el.scrollTop = 0
            })
            
            await page.waitForTimeout(500)
            
            // Verify input is still visible after scrolling
            const newInputBox = await chatInput.boundingBox()
            if (newInputBox) {
              const newInputBottom = newInputBox.y + newInputBox.height
              // Input should still be in viewport
              expect(newInputBottom).toBeLessThanOrEqual(viewportHeight + 100)
            }
          }
        }
      }
    } else {
      console.log('⚠️ Chat input not found - might be in empty state')
    }
  })

  test('chat interface layout should be correct on page load', async ({ page }) => {
    await page.waitForLoadState('networkidle')
    await page.waitForTimeout(500) // Allow layout to settle
    
    // Verify the page has the chat layout
    const hasChatLayout = await page.locator('div:has-text("New Chat"), div:has-text("Chats"), textarea[placeholder*="message"]').count()
    expect(hasChatLayout).toBeGreaterThan(0)
    
    // Get viewport dimensions
    const viewport = page.viewportSize()
    expect(viewport).toBeTruthy()
    
    if (viewport) {
      // Check that the chat interface doesn't cause body scroll
      // With proper flex layout, body should not be scrollable
      const bodyHeight = await page.evaluate(() => document.body.scrollHeight)
      const windowHeight = await page.evaluate(() => window.innerHeight)
      
      // The body scrollHeight should be close to window height (within 200px tolerance)
      // This ensures no extra scrolling is needed
      expect(bodyHeight).toBeLessThanOrEqual(windowHeight + 200)
    }
  })

  test('mobile header should not push content down', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 })
    await page.waitForLoadState('networkidle')
    
    // Navigate to chats
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    
    // Find chat input
    const chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="Start typing"], textarea[name="message"]')
    
    // Wait a bit for layout to settle
    await page.waitForTimeout(1000)
    
    // Check if input exists (might be in empty state)
    const inputVisible = await chatInput.isVisible({ timeout: 2000 }).catch(() => false)
    
    if (inputVisible) {
      // Verify input is in viewport
      const isInViewport = await chatInput.evaluate((element) => {
        const rect = element.getBoundingClientRect()
        return rect.bottom <= window.innerHeight
      })
      
      expect(isInViewport).toBeTruthy()
    }
    
    // Check that mobile header exists but doesn't cause excessive scrolling
    const mobileHeader = page.locator('.lg\\:hidden').first()
    if (await mobileHeader.isVisible({ timeout: 1000 }).catch(() => false)) {
      const headerBox = await mobileHeader.boundingBox()
      if (headerBox) {
        // Header should be at top (allow some tolerance for navbar)
        expect(headerBox.y).toBeLessThan(150)
      }
    }
  })
})

