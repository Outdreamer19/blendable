<template>
  <section class="py-12 md:py-16 border-y border-slate-200 dark:border-white/10">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
      <p class="text-sm text-slate-600 dark:text-slate-400 text-center mb-8 uppercase tracking-wider">
        Trusted by teams worldwide
      </p>
      <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12 opacity-60 hover:opacity-100 transition-opacity">
        <div
          v-for="(logo, index) in logos"
          :key="index"
          class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors text-2xl font-bold grayscale hover:grayscale-0"
        >
          {{ logo }}
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const logos = ['Company A', 'Company B', 'Company C', 'Company D', 'Company E']
</script>

