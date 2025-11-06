<template>
  <section class="py-20 md:py-32 bg-slate-50 dark:bg-white/[0.02]">
    <div class="max-w-3xl mx-auto px-4 md:px-6">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-5xl font-normal tracking-tight text-slate-900 dark:text-white mb-4">
          Questions? Answers!
        </h2>
        <p class="text-md text-slate-600 dark:text-slate-300 max-w-2xl mx-auto font-light">
          Find some quick answers to the most common questions.
        </p>
      </div>

      <div class="space-y-4">
        <div
          v-for="(faq, index) in faqs"
          :key="index"
          class="relative rounded-2xl border border-gray-200/50 dark:border-slate-700/50 bg-white dark:bg-slate-800 overflow-hidden shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow"
        >
          <!-- Top highlight -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
          <!-- Bottom shadow border -->
          <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
          <button
            @click="toggleFaq(index)"
            class="relative z-10 w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 dark:hover:bg-white/[0.06] transition-colors"
          >
            <span class="font-semibold text-slate-900 dark:text-white">{{ faq.question }}</span>
            <svg
              :class="['w-5 h-5 text-slate-500 dark:text-slate-400 transition-transform', openFaqs.includes(index) ? 'rotate-180' : '']"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div
            v-show="openFaqs.includes(index)"
            class="relative z-10 px-6 pb-4 text-slate-600 dark:text-slate-300 leading-relaxed"
            v-html="faq.answer"
          ></div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const openFaqs = ref<number[]>([])

const toggleFaq = (index: number): void => {
  if (openFaqs.value.includes(index)) {
    openFaqs.value = openFaqs.value.filter((i) => i !== index)
  } else {
    openFaqs.value.push(index)
  }
}

const faqs = [
  {
    question: 'How does auto-model routing work?',
    answer:
      'Blendable analyzes each request and automatically selects the best model based on cost, latency, and quality requirements. You send one prompt, and we handle the rest.',
  },
  {
    question: 'Can I use my own API keys?',
    answer:
      'Yes! Blendable uses your existing API keys for OpenAI, Anthropic, Google, and other providers. We never store your prompts or data.',
  },
  {
    question: 'What happens if a model is unavailable?',
    answer:
      'Blendable automatically falls back to alternative models to ensure your requests are always fulfilled.',
  },
  {
    question: 'How is pricing calculated?',
    answer:
      'Pricing is based on token usage across all models. We pass through model costs at cost, plus a small platform fee.',
  },
  {
    question: 'Do I need to change my prompts?',
    answer:
      'No! Your existing prompts work exactly as-is. Blendable handles model selection transparently.',
  },
  {
    question: 'What plans are available?',
    answer:
      'We offer Pro and Business plans. Pro is perfect for individuals and small teams, while Business provides advanced features for growing organizations.',
  },
]
</script>

