import { ref, watch, computed } from 'vue'

type Theme = 'light' | 'dark' | 'system'

const theme = ref<Theme>('system')
let systemMediaQuery: MediaQueryList | null = null

const getSystemTheme = (): boolean => {
  if (typeof window === 'undefined') {
    return false
  }
  return window.matchMedia('(prefers-color-scheme: dark)').matches
}

const updateTheme = (): void => {
  if (typeof document === 'undefined') {
    return
  }

  let shouldBeDark = false

  if (theme.value === 'system') {
    shouldBeDark = getSystemTheme()
  } else {
    shouldBeDark = theme.value === 'dark'
  }

  if (shouldBeDark) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

const setTheme = (newTheme: Theme): void => {
  theme.value = newTheme
  if (typeof window !== 'undefined') {
    localStorage.setItem('theme', newTheme)
  }
  updateTheme()
}

// Initialize theme function that can be called explicitly
const initTheme = (): void => {
  if (typeof window === 'undefined' || typeof document === 'undefined') {
    return
  }

  // Load saved theme or default to system
  const savedTheme = localStorage.getItem('theme') as Theme | null
  if (savedTheme && ['light', 'dark', 'system'].includes(savedTheme)) {
    theme.value = savedTheme
  }

  updateTheme()

  // Watch for system theme changes
  if (systemMediaQuery) {
    systemMediaQuery.removeEventListener('change', handleSystemThemeChange)
  }
  
  systemMediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
  systemMediaQuery.addEventListener('change', handleSystemThemeChange)
}

const handleSystemThemeChange = (): void => {
  if (theme.value === 'system') {
    updateTheme()
  }
}

// Initialize on module load - use nextTick to ensure DOM exists
if (typeof window !== 'undefined') {
  // Wait for next tick to ensure document is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(initTheme, 0)
    })
  } else {
    // DOM already ready, initialize immediately
    setTimeout(initTheme, 0)
  }
}

export const useDarkMode = () => {
  const isDark = computed(() => {
    if (theme.value === 'system') {
      return getSystemTheme()
    }
    return theme.value === 'dark'
  })

  watch(theme, () => {
    updateTheme()
  })

  // Ensure theme is initialized when composable is used
  if (typeof window !== 'undefined' && typeof document !== 'undefined') {
    initTheme()
  }

  return {
    theme,
    isDark,
    setTheme,
  }
}

