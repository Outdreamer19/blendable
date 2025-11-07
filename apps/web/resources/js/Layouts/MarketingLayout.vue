<template>
  <div class="min-h-screen text-slate-900 dark:text-white flex flex-col bg-[#F5F5F5] dark:bg-slate-950">
    <Head>
      <title>Blendable — The AI model router</title>
      <meta
        name="description"
        content="Blendable chooses the best AI model for every task—cut costs, boost quality, zero prompt changes."
      />
      <meta property="og:title" content="Blendable — The AI model router" />
      <meta
        property="og:description"
        content="Blendable chooses the best AI model for every task—cut costs, boost quality, zero prompt changes."
      />
      <meta property="og:type" content="website" />
      <meta name="twitter:card" content="summary_large_image" />
    </Head>

    <!-- Skip to content link -->
    <a
      href="#main-content"
      class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-white dark:focus:bg-slate-800 focus:text-slate-950 dark:focus:text-white focus:rounded-lg"
    >
      Skip to content
    </a>

    <!-- Header -->
    <header class="sticky top-0 z-40 backdrop-blur-sm bg-white/30 dark:bg-gray-900/30 border-b border-slate-200/50 dark:border-white/5">
      <nav class="max-w-6xl mx-auto px-4 md:px-6 py-4">
        <div class="flex items-center">
          <!-- Left: Logo -->
          <div class="flex-1">
            <Logo />
          </div>

          <!-- Center: Navigation Links -->
          <div class="hidden md:flex items-center gap-6 flex-1 justify-center">
            <a href="#features" class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors text-sm">
              Features
            </a>
            <Link href="/pricing" class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors text-sm">
              Pricing
            </Link>
            <Link href="/about" class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors text-sm">
              About
            </Link>
            <Link href="/blog" class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors text-sm">
              Blog
            </Link>
            <Link
              v-if="$page.props.auth?.user"
              href="/dashboard"
              class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors text-sm"
            >
              Dashboard
            </Link>
            <Link
              v-else
              href="/login"
              class="text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors text-sm"
            >
              Login
            </Link>
          </div>

          <!-- Right: Actions -->
          <div class="hidden md:flex items-center gap-4 flex-1 justify-end">
            <DarkModeToggle />
            <MarketingButton
              v-if="!$page.props.auth?.user"
              href="/register"
              as="a"
              variant="ghost"
            >
              Get Started
            </MarketingButton>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center gap-2">
            <DarkModeToggle />
            <button
              @click="showMobileMenu = !showMobileMenu"
              class="p-2 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white"
              aria-label="Toggle menu"
            >
            <svg v-if="!showMobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          </div>
        </div>

        <!-- Mobile menu -->
        <div v-if="showMobileMenu" class="md:hidden mt-4 pb-4 space-y-4 border-t border-slate-200 dark:border-white/10 pt-4">
          <a href="#features" class="block text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors" @click="showMobileMenu = false">
            Features
          </a>
          <Link href="/pricing" class="block text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors" @click="showMobileMenu = false">
            Pricing
          </Link>
          <Link href="/about" class="block text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors" @click="showMobileMenu = false">
            About
          </Link>
          <Link href="/blog" class="block text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors" @click="showMobileMenu = false">
            Blog
          </Link>
          <Link
            v-if="$page.props.auth?.user"
            href="/dashboard"
            class="block text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors"
            @click="showMobileMenu = false"
          >
            Dashboard
          </Link>
          <Link
            v-else
            href="/login"
            class="block text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors"
            @click="showMobileMenu = false"
          >
            Login
          </Link>
          <MarketingButton
            v-if="!$page.props.auth?.user"
            href="/register"
            as="a"
            variant="primary"
            class="w-full"
          >
            Get Started
          </MarketingButton>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main id="main-content" class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 dark:border-white/10">
      <div class="max-w-6xl mx-auto px-4 md:px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <div class="md:col-span-1">
            <Logo />
            <p class="mt-4 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
              The AI model router. Route every prompt to the right model.
            </p>
          </div>

          <div>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Product</h3>
            <ul class="space-y-3">
              <li>
                <a href="#features" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                  Features
                </a>
              </li>
              <li>
                <Link href="/pricing" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                  Pricing
                </Link>
              </li>
            </ul>
          </div>

          <div>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Company</h3>
            <ul class="space-y-3">
              <li>
                <Link href="/about" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                  About
                </Link>
              </li>
              <li>
                <Link href="/blog" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                  Blog
                </Link>
              </li>
              <li>
                <Link href="/terms" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                  Terms
                </Link>
              </li>
              <li>
                <Link href="/privacy" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                  Privacy
                </Link>
              </li>
            </ul>
          </div>

          <div>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Contact</h3>
            <ul class="space-y-3">
              <li>
                <Link
                  href="/contact"
                  class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors"
                >
                  Contact Us
                </Link>
              </li>
              <li>
                <a
                  href="mailto:support@blendable.com"
                  class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors"
                >
                  support@blendable.com
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="pt-8 border-t border-slate-200 dark:border-white/10">
          <p class="text-sm text-slate-600 dark:text-slate-400 text-center">
            &copy; {{ new Date().getFullYear() }} Blendable. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Logo from '@/Components/brand/Logo.vue'
import MarketingButton from '@/Components/ui/MarketingButton.vue'
import DarkModeToggle from '@/Components/ui/DarkModeToggle.vue'
// Import to ensure dark mode initializes
import '@/composables/useDarkMode'

const showMobileMenu = ref(false)

// Ensure dark mode is initialized on mount
onMounted(() => {
  // Force re-initialization to ensure dark class is applied
  if (typeof document !== 'undefined' && typeof window !== 'undefined') {
    const savedTheme = localStorage.getItem('theme') || 'system'
    const html = document.documentElement
    
    let shouldBeDark = false
    
    if (savedTheme === 'system') {
      shouldBeDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    } else {
      shouldBeDark = savedTheme === 'dark'
    }
    
    if (shouldBeDark) {
      html.classList.add('dark')
    } else {
      html.classList.remove('dark')
    }
  }
})
</script>

