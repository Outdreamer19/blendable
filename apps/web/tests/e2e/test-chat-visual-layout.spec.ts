import { test, expect } from '@playwright/test'

test.describe('Chat Visual Layout - Whitespace Test', () => {
  test.beforeEach(async ({ page }) => {
    // Login
    await page.goto('/login')
    await page.fill('#email', 'demo@omni-ai.com')
    await page.fill('#password', 'password')
    await page.click('button[type="submit"]')
    await page.waitForURL(/.*(dashboard|chats|workspaces)/)

    // Ensure workspace exists
    const currentUrl = page.url()
    if (currentUrl.includes('/workspaces') && !currentUrl.includes('/workspaces/')) {
      const workspaceLink = page.locator('a[href*="/workspaces/"]').first()
      if (await workspaceLink.isVisible({ timeout: 2000 }).catch(() => false)) {
        await workspaceLink.click()
        await page.waitForURL(/.*workspaces\/\d+/)
      }
    }

    // Navigate to chats
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    
    // Wait for any animations or layout to settle
    await page.waitForTimeout(500)
  })

  test('should not have excessive whitespace above first message', async ({ page }) => {
    // Get or create a chat with messages
    let chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="message"]')
    const hasInput = await chatInput.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (!hasInput) {
      // Create a chat
      const newChatBtn = page.locator('button:has-text("New Chat")').first()
      if (await newChatBtn.isVisible({ timeout: 3000 }).catch(() => false)) {
        await Promise.all([
          page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 }),
          newChatBtn.click()
        ])
        await page.waitForLoadState('networkidle')
        await page.waitForTimeout(500)
      }
    }

    // Check if we have a chat with messages
    const firstMessage = page.locator('[class*="message"], .flex.gap-3').first()
    const hasMessages = await firstMessage.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (hasMessages) {
      // Get the header bar
      const header = page.locator('[class*="HeaderBar"], .border-b.border-slate-200').first()
      const headerBox = await header.boundingBox()
      
      // Get the first message
      const firstMessageBox = await firstMessage.boundingBox()
      
      if (headerBox && firstMessageBox) {
        // Calculate gap between header and first message
        const gap = firstMessageBox.y - (headerBox.y + headerBox.height)
        
        console.log(`Gap between header and first message: ${gap}px`)
        
        // Gap should be small (less than 50px for reasonable spacing)
        expect(gap).toBeLessThan(50)
      }
    }
  })

  test('should not have excessive whitespace below last message', async ({ page }) => {
    // Get or create a chat
    let chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="message"]')
    const hasInput = await chatInput.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (!hasInput) {
      const newChatBtn = page.locator('button:has-text("New Chat")').first()
      if (await newChatBtn.isVisible({ timeout: 3000 }).catch(() => false)) {
        await Promise.all([
          page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 }),
          newChatBtn.click()
        ])
        await page.waitForLoadState('networkidle')
        await page.waitForTimeout(500)
      }
    }

    // Wait for layout
    await page.waitForTimeout(500)
    
    // Get the composer/input box
    chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="message"]')
    const composerVisible = await chatInput.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (composerVisible) {
      const composerBox = await chatInput.boundingBox()
      
      // Get all messages
      const messages = page.locator('[class*="message"], .flex.gap-3')
      const messageCount = await messages.count()
      
      if (messageCount > 0 && composerBox) {
        const lastMessage = messages.last()
        const lastMessageBox = await lastMessage.boundingBox()
        
        if (lastMessageBox) {
          // Calculate gap between last message and composer
          const gap = composerBox.y - (lastMessageBox.y + lastMessageBox.height)
          
          console.log(`Gap between last message and composer: ${gap}px`)
          console.log(`Viewport height: ${page.viewportSize()?.height}`)
          console.log(`Composer Y position: ${composerBox.y}`)
          console.log(`Last message bottom: ${lastMessageBox.y + lastMessageBox.height}`)
          
          // Gap should be reasonable (less than 100px - allowing for padding and spacing)
          expect(gap).toBeLessThan(100)
        }
      }
    }
  })

  test('messages should start near top, composer near bottom', async ({ page }) => {
    await page.waitForLoadState('networkidle')
    await page.waitForTimeout(500)
    
    // Get header
    const header = page.locator('[class*="HeaderBar"], .border-b.border-slate-200').first()
    const headerVisible = await header.isVisible({ timeout: 3000 }).catch(() => false)
    
    // Get composer
    const composer = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="message"]')
    const composerVisible = await composer.isVisible({ timeout: 3000 }).catch(() => false)
    
    if (headerVisible && composerVisible) {
      const headerBox = await header.boundingBox()
      const composerBox = await composer.boundingBox()
      const viewport = page.viewportSize()
      
      if (headerBox && composerBox && viewport) {
        // Header should be near top
        expect(headerBox.y).toBeLessThan(100)
        
        // Composer should be near bottom of viewport
        const composerBottom = composerBox.y + composerBox.height
        const viewportHeight = viewport.height
        
        console.log(`Header Y: ${headerBox.y}`)
        console.log(`Composer bottom: ${composerBottom}, Viewport height: ${viewportHeight}`)
        
        // Composer should be in lower portion of viewport (within 150px of bottom)
        expect(composerBottom).toBeGreaterThan(viewportHeight - 150)
        
        // Available space between header and composer
        const availableSpace = composerBox.y - (headerBox.y + headerBox.height)
        console.log(`Available space for messages: ${availableSpace}px`)
        
        // This space should be mostly filled (check if messages exist)
        const messages = page.locator('[class*="message"], .flex.gap-3')
        const messageCount = await messages.count()
        
        if (messageCount > 0) {
          const firstMessage = messages.first()
          const lastMessage = messages.last()
          const firstBox = await firstMessage.boundingBox()
          const lastBox = await lastMessage.boundingBox()
          
          if (firstBox && lastBox) {
            const messageAreaHeight = (lastBox.y + lastBox.height) - firstBox.y
            const unusedSpace = availableSpace - messageAreaHeight
            
            console.log(`Message area height: ${messageAreaHeight}px`)
            console.log(`Unused space: ${unusedSpace}px`)
            
            // If we have messages, unused space should be minimal (less than 30% of available space)
            // unless there are very few messages
            if (messageCount >= 2) {
              expect(unusedSpace).toBeLessThan(availableSpace * 0.3)
            }
          }
        }
      }
    }
  })
})

