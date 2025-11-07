<template>
  <section class="py-20 md:py-32 bg-slate-50 dark:bg-white/[0.02]">
    <div class="max-w-6xl mx-auto px-4 md:px-6 text-center">
      <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-slate-900 dark:text-white mb-4">
        Simple, transparent pricing
      </h2>
      <p class="text-xl text-slate-600 dark:text-slate-300 mb-8 max-w-2xl mx-auto">
        Choose a plan that grows with you
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 max-w-4xl mx-auto">
        <div
          v-for="plan in plans"
          :key="plan.name"
          class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/[0.04] backdrop-blur p-8 text-left shadow-sm dark:shadow-none"
        >
          <div class="mb-4">
            <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-1">{{ plan.name }}</h3>
            <div class="text-3xl font-bold text-slate-900 dark:text-white mb-1">
              {{ plan.price }}
            </div>
            <div class="text-sm text-slate-600 dark:text-slate-400">{{ plan.period }}</div>
          </div>
          <ul class="space-y-2 mb-6">
            <li v-for="feature in plan.features" :key="feature" class="text-slate-600 dark:text-slate-300 text-sm flex items-start">
              <svg class="w-5 h-5 text-indigo-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              {{ feature }}
            </li>
          </ul>
        </div>
      </div>

      <MarketingButton href="/pricing" as="a" variant="ghost">
        View full pricing 
      </MarketingButton>
    </div>
  </section>
</template>

<script setup lang="ts">
import MarketingButton from '@/Components/ui/MarketingButton.vue'

const plans = [
  {
    name: 'Pro',
    price: '$19.99',
    period: '/mo',
    features: ['500k tokens/mo', 'Full model roster', 'Uploads', 'Priority support'],
  },
  {
    name: 'Business',
    price: '$79',
    period: '/mo',
    features: ['5 seats', '2M tokens pooled', 'Analytics', 'Roles'],
  },
]
</script>

