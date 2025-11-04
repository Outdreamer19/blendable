<template>
  <section class="py-20 md:py-32">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div
          v-for="(screen, index) in screens"
          :key="index"
          class="group relative"
        >
          <div
            class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/[0.04] backdrop-blur p-4 overflow-hidden transform transition-all duration-300 group-hover:scale-[1.02] group-hover:rotate-1 shadow-sm dark:shadow-none"
          >
            <div class="aspect-video bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 rounded-xl flex items-center justify-center">
              <div class="text-slate-500 dark:text-slate-400 text-sm">Screenshot {{ index + 1 }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const screens = [1, 2, 3]
</script>

