<template>
  <section id="features" class="py-20 md:py-32">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-slate-900 dark:text-white mb-4">
          Everything you need to route AI intelligently
        </h2>
        <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
          Powerful features designed to help you get the most from every model
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="feature in features"
          :key="feature.title"
          class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/[0.04] backdrop-blur p-8 hover:bg-slate-50 dark:hover:bg-white/[0.06] transition-all shadow-sm dark:shadow-none"
        >
          <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500/20 via-blue-500/20 to-cyan-500/20 flex items-center justify-center mb-4">
            <div class="w-6 h-6 rounded bg-gradient-to-r from-indigo-500 to-cyan-500"></div>
          </div>
          <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">{{ feature.title }}</h3>
          <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ feature.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const features = [
  {
    title: 'Auto-Model Routing',
    description: 'Send once, get the best model response (Gemini, Claude, GPT, etc.).',
  },
  {
    title: 'Cost Controls',
    description: 'Budgets, per-plan caps, automatic fallbacks.',
  },
  {
    title: 'Teams & Workspaces',
    description: 'Shared memory, roles, audit.',
  },
  {
    title: 'Documents & Images',
    description: 'Drop PDFs and images; Blendable handles them.',
  },
  {
    title: 'Analytics',
    description: 'Latency, spend, accuracy—by model.',
  },
  {
    title: 'API & Embeds',
    description: 'Use Blendable anywhere.',
  },
]
</script>

