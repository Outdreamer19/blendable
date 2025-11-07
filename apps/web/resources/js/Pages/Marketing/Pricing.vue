<template>
  <MarketingLayout>
    <div class="py-20 md:py-32 bg-[#F9FAFB] dark:bg-slate-900">
      <div class="max-w-7xl mx-auto px-4 md:px-6">
        <!-- Section Header -->
        <div class="text-center mb-12">
          <!-- PRICING Tag -->
          <div
            class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-100 dark:bg-slate-800 rounded-full shadow-sm border border-gray-200 dark:border-slate-700 mb-4">
            <span
              class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">PRICING</span>
          </div>

          <!-- Main Title -->
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-normal text-gray-900 dark:text-white mb-4">
            Simple Price For All
          </h1>

          <!-- Subtitle -->
          <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-8">
            Choose the perfect plan for your AI routing needs. Scale as you grow.
          </p>

          <!-- Monthly/Yearly Toggle -->
          <div class="flex items-center justify-center gap-2 mb-12">
            <div class="relative inline-flex bg-gray-100 dark:bg-slate-800 rounded-2xl p-1 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)]">
              <!-- Top highlight -->
              <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-lg"></div>
              <!-- Bottom shadow border -->
              <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
              <button @click="isYearly = false" :class="[
                'px-6 py-2 rounded-2xl font-medium text-sm transition-all relative z-10',
                !isYearly
                  ? 'bg-gray-900 dark:bg-gray-700 text-white shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_4px_-1px_rgba(0,0,0,0.4),0_1px_2px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_2px_4px_-1px_rgba(0,0,0,0.6),0_1px_2px_-1px_rgba(0,0,0,0.45)]'
                  : 'text-gray-700 dark:text-gray-300'
              ]">
                Monthly
              </button>
              <button @click="isYearly = true" :class="[
                'px-6 py-2 rounded-2xl font-medium text-sm transition-all relative z-10',
                isYearly
                  ? 'bg-gray-900 dark:bg-gray-700 text-white shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_4px_-1px_rgba(0,0,0,0.4),0_1px_2px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_2px_4px_-1px_rgba(0,0,0,0.6),0_1px_2px_-1px_rgba(0,0,0,0.45)]'
                  : 'text-gray-700 dark:text-gray-300'
              ]">
                Yearly
              </button>
              <!-- 30% off badge positioned to the right of Yearly -->
              <span
                class="absolute -right-16 top-1/2 -translate-y-1/2 bg-gray-900 dark:bg-gray-700 text-white text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_4px_-1px_rgba(0,0,0,0.4),0_1px_2px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_2px_4px_-1px_rgba(0,0,0,0.6),0_1px_2px_-1px_rgba(0,0,0,0.45)]">
                30% off
              </span>
            </div>
          </div>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto mb-16">
          <div
            v-for="plan in transformedPlans"
            :key="plan.key"
            class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
            <!-- Top highlight overlay - light from above -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
            <!-- Bottom shadow border -->
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300/50 to-transparent dark:via-slate-600/50"></div>
            <!-- Popular Badge -->
            <div v-if="plan.popular" class="absolute top-6 right-6 z-10">
              <span
                class="inline-flex items-center gap-1 bg-gray-900 dark:bg-gray-700 text-white text-xs font-semibold px-3 py-1 rounded-full">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                </svg>
                Popular
              </span>
            </div>

            <div class="relative mb-6">
              <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ plan.name }}</h3>
              <div class="mb-3">
                <span class="text-5xl font-bold text-gray-900 dark:text-white">{{ plan.price }}</span>
                <span class="text-gray-500 dark:text-gray-400 ml-2 text-lg">/month</span>
              </div>
              <p class="text-gray-600 dark:text-gray-400 text-sm">
                {{ plan.description }}
              </p>
            </div>

            <a
              :href="getCheckoutUrl(plan.key)"
              :class="[
                'relative w-full mb-6 px-6 py-2 rounded-lg font-bold text-sm flex items-center justify-center gap-2 transition-colors border',
                plan.popular
                  ? 'bg-gray-900 dark:bg-gray-700 text-white hover:bg-gray-800 dark:hover:bg-gray-600 border-gray-800/50 dark:border-gray-600/50 shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_5px_-1px_rgba(0,0,0,0.5),0_1px_4px_-1px_rgba(0,0,0,0.4)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_2px_5px_-1px_rgba(0,0,0,0.75),0_1px_4px_-1px_rgba(0,0,0,0.6)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_3px_6px_-2px_rgba(0,0,0,0.55),0_2px_5px_-1px_rgba(0,0,0,0.45)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_3px_6px_-2px_rgba(0,0,0,0.85),0_2px_5px_-1px_rgba(0,0,0,0.65)]'
                  : 'bg-gray-100 dark:bg-slate-700 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-slate-600 border-gray-200/50 dark:border-slate-600/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.15)_inset,0_2px_5px_-1px_rgba(0,0,0,0.4),0_1px_4px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.35)_inset,0_2px_5px_-1px_rgba(0,0,0,0.65),0_1px_4px_-1px_rgba(0,0,0,0.5)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.15)_inset,0_3px_6px_-2px_rgba(0,0,0,0.45),0_2px_5px_-1px_rgba(0,0,0,0.35)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.35)_inset,0_3px_6px_-2px_rgba(0,0,0,0.75),0_2px_5px_-1px_rgba(0,0,0,0.55)]'
              ]">
              <!-- Top highlight -->
              <div :class="[
                'absolute top-0 left-0 right-0 h-px rounded-t-lg',
                plan.popular
                  ? 'bg-gradient-to-b from-white/20 to-transparent opacity-40 dark:opacity-10'
                  : 'bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10'
              ]"></div>
              <span class="relative">Get Started</span>
              <svg class="w-4 h-4 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </a>

            <ul class="space-y-3 border-t-2 border-dotted border-gray-300 dark:border-slate-700/50 pt-4">
              <li v-for="feature in plan.features" :key="feature"
                class="flex items-start text-gray-600 dark:text-gray-400 text-sm">
                <svg class="w-5 h-5 text-gray-900 dark:text-gray-300 mr-3 flex-shrink-0 mt-0.5" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7" />
                </svg>
                {{ feature }}
              </li>
            </ul>
          </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-20 border-t border-gray-200 dark:border-slate-700/50 pt-16">
          <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">Pricing FAQ</h2>
          <div class="max-w-3xl mx-auto space-y-4">
            <div
              v-for="(faq, index) in faqs"
              :key="index"
              class="rounded-2xl border border-gray-200/50 dark:border-slate-700/50 bg-white dark:bg-slate-800 overflow-hidden shadow-sm dark:shadow-none"
            >
              <button
                @click="toggleFaq(index)"
                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors"
              >
                <span class="font-semibold text-gray-900 dark:text-white">{{ faq.question }}</span>
                <svg
                  :class="[
                    'w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform',
                    openFaqs.includes(index) ? 'rotate-180' : '',
                  ]"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div
                v-show="openFaqs.includes(index)"
                class="px-6 pb-4 text-gray-600 dark:text-gray-300 leading-relaxed"
                v-html="faq.answer"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MarketingLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import MarketingLayout from '@/Layouts/MarketingLayout.vue'

const props = defineProps({
  plans: Array,
  currentPlan: String,
  isAuthenticated: Boolean
})

const page = usePage()

const isYearly = ref(false)
const openFaqs = ref<number[]>([])

const getCheckoutUrl = (planKey: string): string => {
  const interval = isYearly.value ? 'yearly' : 'monthly'
  if (page.props.auth?.user) {
    return `/billing/checkout?plan=${planKey}&interval=${interval}`
  }
  return `/register?plan=${planKey}&interval=${interval}`
}

const toggleFaq = (index: number): void => {
  if (openFaqs.value.includes(index)) {
    openFaqs.value = openFaqs.value.filter((i) => i !== index)
  } else {
    openFaqs.value.push(index)
  }
}

// Price mapping for monthly/yearly
const priceMap: Record<string, { monthly: number; yearly: number }> = {
  pro: {
    monthly: 19.99,
    yearly: 13, // 30% off
  },
  business: {
    monthly: 79,
    yearly: 55, // 30% off
  },
}

const getPrice = (planKey: string): string => {
  const prices = priceMap[planKey] || { monthly: 0, yearly: 0 }
  const price = isYearly.value ? prices.yearly : prices.monthly
  return `$${price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}

// Transform plans to match component structure
const transformedPlans = computed(() => {
  return props.plans?.map((plan: any) => ({
    key: plan.key,
    name: plan.name,
    price: getPrice(plan.key),
    description: plan.key === 'pro' ? 'For power users and teams who need advanced AI routing' : 'For growing teams that need collaboration and advanced analytics',
    popular: plan.key === 'pro',
    features: plan.features || [],
  })) || []
})

const faqs = [
  {
    question: 'Can I change plans later?',
    answer: 'Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately.',
  },
  {
    question: 'What happens if I exceed my token limit?',
    answer:
      'When you reach your monthly token limit, you can upgrade your plan or wait for the next billing cycle. We\'ll notify you as you approach your limit.',
  },
  {
    question: 'Do you offer refunds?',
    answer:
      'Yes, we offer a 30-day money-back guarantee on all paid plans. Contact support for assistance.',
  },
  {
    question: 'Can I get a custom plan for my organization?',
    answer:
      'Absolutely! Contact us at support@blendable.com to discuss enterprise pricing and custom features.',
  },
]
</script>

