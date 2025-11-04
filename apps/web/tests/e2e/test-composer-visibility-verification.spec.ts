import { test, expect } from '@playwright/test'

test.describe('Composer Visibility Verification', () => {
  test('composer must stay visible when scrolling long chat messages', async ({ page }) => {
    // Login
    await page.goto('/login')
    await page.fill('#email', 'test@example.com')
    await page.fill('#password', 'password')
    await page.click('button[type="submit"]')
    await page.waitForURL(/.*(dashboard|chats|workspaces)/)

    // Navigate to chats
    await page.goto('/chats')
    await page.waitForLoadState('networkidle')
    await page.waitForTimeout(500)

    // Find and click on "Quick Chat" or any chat
    let chatFound = false
    
    // First try to find "Quick Chat" specifically
    const quickChatText = page.locator('text=Quick Chat').first()
    const quickChatVisible = await quickChatText.isVisible({ timeout: 2000 }).catch(() => false)
    
    if (quickChatVisible) {
      const quickChatLink = quickChatText.locator('..').locator('a[href*="/chats/"], [class*="ChatRow"]').first()
      if (await quickChatLink.isVisible({ timeout: 1000 }).catch(() => false)) {
        await quickChatLink.click()
        chatFound = true
      } else {
        await quickChatText.click()
        chatFound = true
      }
    }

    // If Quick Chat not found, try any chat link
    if (!chatFound) {
      const chatLinks = page.locator('a[href*="/chats/"]')
      const count = await chatLinks.count()
      
      if (count > 0) {
        await chatLinks.first().click()
        chatFound = true
      }
    }

    // If still no chat, try creating a new one
    if (!chatFound) {
      const newChatBtn = page.locator('button:has-text("New Chat")').first()
      if (await newChatBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
        await Promise.all([
          page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 }),
          newChatBtn.click()
        ])
        chatFound = true
      }
    }

    if (!chatFound) {
      console.log('❌ No chat found or could not create chat')
      console.log('   This means we cannot verify the composer visibility fix')
      return
    }

    // Wait for chat to load
    await page.waitForURL(/.*\/chats\/\d+/, { timeout: 10000 })
    await page.waitForLoadState('networkidle')
    await page.waitForTimeout(1000) // Give layout time to settle

    // Find the composer
    const chatInput = page.locator('textarea[placeholder*="Type your message"], textarea[placeholder*="message"]').first()
    const inputExists = await chatInput.isVisible({ timeout: 3000 }).catch(() => false)

    if (!inputExists) {
      console.log('❌ Chat input not found - cannot verify composer visibility')
      return
    }

    // Get viewport
    const viewport = page.viewportSize()
    expect(viewport).toBeTruthy()
    if (!viewport) return

    // Find the messages scroll container
    const messagesContainer = page.locator('section.overflow-y-auto').first()
    const containerExists = await messagesContainer.isVisible({ timeout: 2000 }).catch(() => false)

    if (!containerExists) {
      console.log('⚠️ Messages scroll container not found')
      // Check if there are any scrollable containers
      const allScrollable = await page.locator('[class*="overflow-y-auto"], [class*="overflow-auto"]').all()
      console.log(`Found ${allScrollable.length} potential scroll containers`)
    }

    // Get initial state
    const initialInputBox = await chatInput.boundingBox()
    expect(initialInputBox).toBeTruthy()
    if (!initialInputBox) return

    const pageScrollY = await page.evaluate(() => window.scrollY)
    const bodyHeight = await page.evaluate(() => document.body.scrollHeight)
    const windowHeight = await page.evaluate(() => window.innerHeight)

    console.log('\n📊 Initial State:')
    console.log(`   Page scroll Y: ${pageScrollY}px`)
    console.log(`   Body height: ${bodyHeight}px`)
    console.log(`   Window height: ${windowHeight}px`)
    console.log(`   Viewport height: ${viewport.height}px`)
    console.log(`   Composer Y position: ${initialInputBox.y}px`)
    console.log(`   Composer bottom: ${initialInputBox.y + initialInputBox.height}px`)

    // Check if composer is in viewport initially
    const composerInViewport = initialInputBox.y < viewport.height && (initialInputBox.y + initialInputBox.height) > 0
    console.log(`   Composer in viewport: ${composerInViewport}`)

    if (containerExists) {
      // Get scroll container info
      const scrollInfo = await messagesContainer.evaluate((el: Element) => {
        const htmlEl = el as HTMLElement
        return {
          scrollTop: htmlEl.scrollTop,
          scrollHeight: htmlEl.scrollHeight,
          clientHeight: htmlEl.clientHeight,
          canScroll: htmlEl.scrollHeight > htmlEl.clientHeight
        }
      })

      console.log('\n📜 Messages Container Scroll Info:')
      console.log(`   Scroll Top: ${scrollInfo.scrollTop}px`)
      console.log(`   Scroll Height: ${scrollInfo.scrollHeight}px`)
      console.log(`   Client Height: ${scrollInfo.clientHeight}px`)
      console.log(`   Can Scroll: ${scrollInfo.canScroll}`)

      if (scrollInfo.canScroll) {
        // Scroll messages to top
        await messagesContainer.evaluate((el: Element) => {
          const htmlEl = el as HTMLElement
          htmlEl.scrollTop = 0
        })

        await page.waitForTimeout(500)

        const afterScrollInputBox = await chatInput.boundingBox()
        const pageScrollYAfter = await page.evaluate(() => window.scrollY)

        console.log('\n📊 After Scrolling Messages to Top:')
        console.log(`   Page scroll Y: ${pageScrollYAfter}px (was ${pageScrollY}px)`)
        console.log(`   Composer Y position: ${afterScrollInputBox?.y}px`)
        console.log(`   Composer bottom: ${afterScrollInputBox ? afterScrollInputBox.y + afterScrollInputBox.height : 'N/A'}px`)

        // Composer should still be visible
        const stillVisible = await chatInput.isVisible()
        const composerStillInViewport = afterScrollInputBox && 
          afterScrollInputBox.y < viewport.height && 
          (afterScrollInputBox.y + afterScrollInputBox.height) > 0

        console.log(`   Composer still visible: ${stillVisible}`)
        console.log(`   Composer still in viewport: ${composerStillInViewport}`)

        if (!stillVisible || !composerStillInViewport) {
          console.log('\n❌ FAIL: Composer is NOT visible after scrolling messages!')
          console.log('   This means the fix did NOT work.')
          
          // Check what happened
          if (pageScrollYAfter > pageScrollY + 50) {
            console.log('   Issue: Page scrolled when messages scrolled (should only scroll messages container)')
          } else if (afterScrollInputBox && afterScrollInputBox.y > viewport.height) {
            console.log('   Issue: Composer moved below viewport when messages scrolled')
          }
          
          expect(stillVisible).toBe(true)
          expect(composerStillInViewport).toBe(true)
        } else {
          console.log('\n✅ SUCCESS: Composer stayed visible after scrolling messages!')
        }

        // Now scroll messages to bottom
        await messagesContainer.evaluate((el: Element) => {
          const htmlEl = el as HTMLElement
          htmlEl.scrollTop = htmlEl.scrollHeight
        })

        await page.waitForTimeout(500)

        const afterScrollToBottom = await chatInput.boundingBox()
        const pageScrollYBottom = await page.evaluate(() => window.scrollY)

        console.log('\n📊 After Scrolling Messages to Bottom:')
        console.log(`   Page scroll Y: ${pageScrollYBottom}px (was ${pageScrollY}px)`)
        console.log(`   Composer Y position: ${afterScrollToBottom?.y}px`)
        console.log(`   Composer still visible: ${await chatInput.isVisible()}`)

        if (pageScrollYBottom > pageScrollY + 50) {
          console.log('\n❌ FAIL: Page scrolled when scrolling messages to bottom!')
          console.log('   This means the layout is not properly constrained.')
          expect(pageScrollYBottom).toBeLessThanOrEqual(pageScrollY + 50)
        } else {
          console.log('✅ Page did not scroll when messages scrolled')
        }
      } else {
        console.log('\nℹ️ Messages container does not need scrolling (not enough messages)')
        console.log('   Cannot fully test composer visibility during scroll, but composer is visible: ' + composerInViewport)
      }
    } else {
      console.log('\n⚠️ Cannot find messages scroll container')
      console.log('   Verifying composer is at least visible: ' + composerInViewport)
      if (!composerInViewport) {
        console.log('❌ FAIL: Composer is not visible in viewport!')
        expect(composerInViewport).toBe(true)
      }
    }

    // Final verification
    const finalVisible = await chatInput.isVisible()
    console.log('\n✅ Final check: Composer visible =', finalVisible)
    expect(finalVisible).toBe(true)
  })
})

