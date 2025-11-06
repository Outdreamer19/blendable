<template>
  <section class="py-10 md:py-20 bg-[#F9FAFB] dark:bg-slate-900 relative overflow-hidden">
    <!-- Gradient background -->
    <div class="absolute inset-0 bg-gradient-to-r from-indigo-100/10 via-blue-100/10 to-cyan-100/10"></div>

    <div class="max-w-4xl mx-auto px-4 md:px-6 text-center relative z-10">
      <h2 class="text-3xl md:text-5xl font-normal tracking-tight text-slate-900 dark:text-white mb-6">
        Route every prompt to the right model.
      </h2>
      <p class="text-xl text-slate-600 dark:text-slate-300 mb-8 max-w-2xl mx-auto font-light">
        Join thousands of teams already using Blendable to optimize their AI workflows.
      </p>
      <MarketingButton :href="registerRoute" as="a" variant="ghost" class="text-lg px-8 py-4">
        Get Started
      </MarketingButton>
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import MarketingButton from '@/Components/ui/MarketingButton.vue'

const page = usePage()

const registerRoute = computed(() => {
  return page.props.auth?.user ? '/dashboard' : '/register'
})
</script>

