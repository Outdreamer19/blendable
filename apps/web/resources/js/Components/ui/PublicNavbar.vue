<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Menu, X, ChevronDown, ArrowRight, Brain } from 'lucide-vue-next'

const page = usePage()

// Reactive state
const isScrolled = ref(false)
const isScrollingUp = ref(true)
const lastScrollY = ref(0)
const isMobileMenuOpen = ref(false)
const isProductMenuOpen = ref(false)
const isDarkMode = ref(false)

// Product sections for mega menu
const productSections = [
  {
    title: "Create",
    items: [
      { name: "AI Chat", description: "Chat with multiple AI models in one place", href: "/chat", icon: "💬" },
      { name: "Templates", description: "Choose from professional AI prompts", href: "/templates", icon: "🎨" },
      { name: "Code Assistant", description: "Get help with coding and debugging", href: "/code-assistant", icon: "💻" }
    ]
  },
  {
    title: "Manage",
    items: [
      { name: "Dashboard", description: "Overview of all your AI interactions", href: "/dashboard", icon: "📊" },
      { name: "History", description: "Review past conversations and outputs", href: "/history", icon: "📚" },
      { name: "Settings", description: "Customize your AI experience", href: "/settings", icon: "⚙️" }
    ]
  },
  {
    title: "Grow",
    items: [
      { name: "API Access", description: "Integrate AI into your applications", href: "/api", icon: "🔌" },
      { name: "Team Collaboration", description: "Work together on AI projects", href: "/teams", icon: "👥" },
      { name: "Enterprise", description: "Advanced features for organizations", href: "/enterprise", icon: "🏢" }
    ]
  }
]

// Navigation links
const navigationLinks = [
  { name: "Features", href: "/features" },
  { name: "Pricing", href: "/pricing" },
  { name: "About", href: "/about" },
  { name: "Documentation", href: "/docs" },
  { name: "Support", href: "/support" }
]

// Scroll behavior logic
let scrollTimeout: NodeJS.Timeout | null = null

const handleScroll = () => {
  const currentScrollY = window.scrollY
  
  // Show navbar in hero section (first 800px) - keep full navbar
  if (currentScrollY < 800) {
    isScrolled.value = false
    isScrollingUp.value = true
  } else {
    isScrolled.value = true
    
    // Determine scroll direction
    if (currentScrollY > lastScrollY.value && currentScrollY > 800) {
      isScrollingUp.value = false // Scrolling down
    } else {
      isScrollingUp.value = true // Scrolling up
    }
  }
  
  lastScrollY.value = currentScrollY
}

// Throttled scroll handler
const throttledScroll = () => {
  if (scrollTimeout) return
  
  scrollTimeout = setTimeout(() => {
    handleScroll()
    scrollTimeout = null
  }, 16) // 60fps
}

// Mobile menu toggle
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

// Product menu toggle
const toggleProductMenu = () => {
  isProductMenuOpen.value = !isProductMenuOpen.value
}

// Close menus when clicking outside
const closeMenus = () => {
  isMobileMenuOpen.value = false
  isProductMenuOpen.value = false
}

// Dark mode toggle
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  document.documentElement.classList.toggle('dark')
}

// Lifecycle
onMounted(() => {
  window.addEventListener('scroll', throttledScroll, { passive: true })
  document.addEventListener('click', closeMenus)
  
  // Check initial scroll position
  handleScroll()
  
  // Check for dark mode preference
  isDarkMode.value = document.documentElement.classList.contains('dark')
})

onUnmounted(() => {
  window.removeEventListener('scroll', throttledScroll)
  document.removeEventListener('click', closeMenus)
  if (scrollTimeout) clearTimeout(scrollTimeout)
})
</script>

<template>
  <header 
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 ease-in-out flex justify-center px-4 sm:px-6"
    :class="{
      'translate-y-0': isScrollingUp || !isScrolled,
      '-translate-y-full': !isScrollingUp && isScrolled
    }"
  >
    <!-- Floating navbar container -->
    <div class="relative transition-all duration-500 ease-out"
         :style="{
           '--navbar-width': isScrolled ? '700px' : '1240px',
           width: 'var(--navbar-width)',
           minWidth: '320px'
         }">
      <!-- Glassmorphism background -->
      <div class="absolute inset-0 backdrop-blur-md border shadow-md transition-all duration-500 ease-out"
           :class="isScrolled 
             ? 'bg-white/60 dark:bg-slate-900/90 border-indigo-100 dark:border-slate-700/60 rounded-2xl mt-1' 
             : 'bg-white/30 dark:bg-slate-900/30 border-indigo-100 shadow-none dark:border-slate-700/20 rounded-2xl mt-3'"></div>
      
      <!-- Navbar content -->
      <div class="relative transition-all duration-500 ease-out"
           :class="isScrolled ? 'px-3 sm:px-4 lg:px-6' : 'px-4 sm:px-6 lg:px-8'">
        <div class="flex items-center justify-between transition-all duration-500 ease-out"
             :class="isScrolled ? 'h-16' : 'h-20'">
        <!-- Logo -->
        <div class="flex-shrink-0 transition-all duration-500 ease-out"
             :class="isScrolled ? 'mr-6' : 'mr-0'">
          <Link href="/" class="flex items-center transition-all duration-500 ease-out"
                :class="isScrolled ? 'space-x-0' : 'space-x-3'">
            <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center transition-all duration-500 ease-out"
                 :class="isScrolled ? 'w-7 h-7' : 'w-8 h-8'">
              <Brain class="text-white transition-all duration-500 ease-out"
                     :class="isScrolled ? 'w-4 h-4' : 'w-5 h-5'" />
            </div>
            <span class="text-xl font-bold text-slate-900 dark:text-white transition-all duration-500 ease-out overflow-hidden"
                  :class="isScrolled ? 'w-0 opacity-0' : 'w-auto opacity-100'">Omni-AI</span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex lg:items-center transition-all duration-500 ease-out"
             :style="{
               gap: isScrolled ? '16px' : '32px'
             }">
          <!-- Product Menu -->
          <div class="relative" @click.stop>
            <button
              @click="toggleProductMenu"
              class="flex items-center space-x-1 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors duration-200"
            >
              <span>Product</span>
              <ChevronDown 
                class="w-4 h-4 transition-transform duration-200"
                :class="{ 'rotate-180': isProductMenuOpen }"
              />
            </button>

            <!-- Mega Menu -->
            <div
              v-if="isProductMenuOpen"
              class="absolute top-full left-0 mt-2 w-screen max-w-4xl bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden"
            >
              <div class="p-8">
                <div class="grid grid-cols-3 gap-8">
                  <div v-for="section in productSections" :key="section.title" class="space-y-4">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wide">
                      {{ section.title }}
                    </h3>
                    <div class="space-y-3">
                      <Link
                        v-for="item in section.items"
                        :key="item.name"
                        :href="item.href"
                        class="group flex items-start space-x-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-200"
                      >
                        <span class="text-2xl">{{ item.icon }}</span>
                        <div>
                          <div class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ item.name }}
                          </div>
                          <div class="text-sm text-slate-600 dark:text-slate-400">
                            {{ item.description }}
                          </div>
                        </div>
                        <ArrowRight class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors ml-auto" />
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Other Navigation Links -->
          <Link
            v-for="link in navigationLinks"
            :key="link.name"
            :href="link.href"
            class="text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors duration-200"
          >
            {{ link.name }}
          </Link>
        </nav>

        <!-- Desktop Auth & Actions -->
        <div class="hidden lg:flex lg:items-center lg:space-x-4">
          <!-- Dark Mode Toggle -->
          <button
            @click="toggleDarkMode"
            class="rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 ease-in-out"
            :class="isScrolled ? 'p-1.5' : 'p-2'"
          >
            <span class="text-sm transition-all duration-300 ease-in-out"
                  :class="isScrolled ? 'text-xs' : 'text-sm'">{{ isDarkMode ? '☀️' : '🌙' }}</span>
          </button>

          <!-- Auth Links -->
          <template v-if="page.props.auth.user">
            <Link
              :href="route('dashboard')"
              class="text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors duration-200 text-sm font-medium"
            >
              Dashboard
            </Link>
          </template>
          <template v-else>
            <Link
              v-if="!isScrolled"
              :href="route('login')"
              class="text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all duration-500 ease-out text-sm font-medium"
              :class="isScrolled ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100 w-auto'"
            >
              Sign in
            </Link>
            <Link
              :href="route('register')"
              class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white rounded-lg hover:opacity-90 transition-all duration-500 ease-out font-medium shadow-lg shadow-blue-500/25 text-sm"
              :style="{
                paddingLeft: isScrolled ? '10px' : '12px',
                paddingRight: isScrolled ? '10px' : '12px',
                paddingTop: isScrolled ? '6px' : '6px',
                paddingBottom: isScrolled ? '6px' : '6px'
              }"
            >
              Get Started
            </Link>
          </template>
        </div>

        <!-- Mobile Menu Button -->
        <div class="lg:hidden">
          <button
            @click="toggleMobileMenu"
            class="rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 ease-in-out"
            :class="isScrolled ? 'p-1.5' : 'p-2'"
            :aria-label="isMobileMenuOpen ? 'Close menu' : 'Open menu'"
          >
            <Menu v-if="!isMobileMenuOpen" 
                  class="text-slate-700 dark:text-slate-300 transition-all duration-300 ease-in-out"
                  :class="isScrolled ? 'w-5 h-5' : 'w-6 h-6'" />
            <X v-else 
               class="text-slate-700 dark:text-slate-300 transition-all duration-300 ease-in-out"
               :class="isScrolled ? 'w-5 h-5' : 'w-6 h-6'" />
          </button>
        </div>
      </div>
    </div>
    </div>

    <!-- Mobile Menu -->
    <div
      v-if="isMobileMenuOpen"
      class="lg:hidden absolute top-full left-0 right-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-700/50"
    >
      <div class="px-4 py-6 space-y-6">
        <!-- Product Section -->
        <div>
          <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wide mb-4">
            Product
          </h3>
          <div class="space-y-3">
            <Link
              v-for="section in productSections"
              :key="section.title"
              :href="`#${section.title.toLowerCase()}`"
              class="block text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors duration-200"
            >
              {{ section.title }}
            </Link>
          </div>
        </div>

        <!-- Navigation Links -->
        <div>
          <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wide mb-4">
            Links
          </h3>
          <div class="space-y-3">
            <Link
              v-for="link in navigationLinks"
              :key="link.name"
              :href="link.href"
              class="block text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors duration-200"
            >
              {{ link.name }}
            </Link>
          </div>
        </div>

        <!-- Auth Section -->
        <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-slate-900 dark:text-white">Theme</span>
            <button
              @click="toggleDarkMode"
              class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors duration-200"
            >
              <span class="text-sm">{{ isDarkMode ? '☀️' : '🌙' }}</span>
            </button>
          </div>
          
          <template v-if="page.props.auth.user">
            <Link
              :href="route('dashboard')"
              class="block w-full text-center bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white py-3 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors duration-200"
            >
              Dashboard
            </Link>
          </template>
          <template v-else>
            <div class="space-y-3">
              <Link
                v-if="!isScrolled"
                :href="route('login')"
                class="block w-full text-center text-slate-700 dark:text-slate-300 px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-500 ease-in-out text-sm font-medium"
                :class="isScrolled ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100 h-auto'"
              >
                Sign in
              </Link>
              <Link
                :href="route('register')"
                class="block w-full text-center bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white px-3 py-2 rounded-lg hover:opacity-90 transition-all duration-500 ease-in-out text-sm font-medium shadow-lg shadow-blue-500/25"
              >
                Get Started
              </Link>
            </div>
          </template>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
/* Performance optimizations */
header {
  transform: translateZ(0);
  backface-visibility: hidden;
  perspective: 1000px;
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Custom backdrop blur for better browser support */
.backdrop-blur-xl {
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
}

/* Enhanced glassmorphism effect */
.bg-white\/80 {
  background-color: rgba(255, 255, 255, 0.8);
}

.dark .bg-slate-900\/80 {
  background-color: rgba(15, 23, 42, 0.8);
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}
</style>
