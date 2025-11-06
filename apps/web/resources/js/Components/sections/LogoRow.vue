<template>
  <section class="py-12 md:py-16 border-y border-slate-200 dark:border-white/10">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
      <p class="text-sm text-slate-600 dark:text-slate-400 text-center mb-8 uppercase tracking-wider">
        Integrations
      </p>
      <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12 opacity-60 hover:opacity-100 transition-opacity">
        <div
          v-for="(logo, index) in logos"
          :key="index"
          class="flex items-center justify-center h-12 md:h-16 grayscale hover:grayscale-0 transition-all duration-300"
        >
          <img
            :src="logo.path"
            :alt="logo.name"
            class="h-full w-auto object-contain max-w-[120px]"
            @error="(e) => handleImageError(e, logo)"
            loading="lazy"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const logos = [
  {
    name: 'ChatGPT',
    path: '/img/logo/ChatGPT_logo.svg-.png'
  },
  {
    name: 'Claude AI',
    path: '/img/logo/Claude_AI_logo.svg'
  },
  {
    name: 'Google Gemini',
    path: '/img/logo/Google_Gemini_Logo.png',
    fallback: '/img/logo/gemini-color.png'
  },
  {
    name: 'DeepSeek',
    path: '/img/logo/DeepSeek_logo.svg.png'
  }
]

const handleImageError = (event: Event, logo?: { name: string; path: string; fallback?: string }) => {
  const img = event.target as HTMLImageElement
  if (logo?.fallback && img.src !== logo.fallback) {
    // Try fallback image
    img.src = logo.fallback
  } else {
    console.warn('Failed to load logo:', img.src)
  }
}
</script>

