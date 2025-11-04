<template>
  <section id="how-it-works" class="py-20 md:py-32 bg[#F9FAFB]">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-slate-900 dark:text-white mb-4">
          How it works
        </h2>
        <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
          Get started in three simple steps
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div
          v-for="(step, index) in steps"
          :key="index"
          class="text-center"
        >
          <div class="relative mb-6">
            <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500 flex items-center justify-center text-2xl font-bold text-white mb-4">
              {{ index + 1 }}
            </div>
            <div
              v-if="index < steps.length - 1"
              class="hidden md:block absolute top-10 left-[60%] w-full h-0.5 bg-gradient-to-r from-indigo-500/50 to-transparent"
            ></div>
          </div>
          <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">{{ step.title }}</h3>
          <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ step.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const steps = [
  {
    title: 'Connect providers',
    description: 'Add your API keys for OpenAI, Anthropic, Google, and more.',
  },
  {
    title: 'Enable Auto-Model',
    description: 'Blendable automatically routes each request to the optimal model.',
  },
  {
    title: 'Track + tune',
    description: 'Monitor performance, costs, and accuracy in real-time.',
  },
]
</script>

