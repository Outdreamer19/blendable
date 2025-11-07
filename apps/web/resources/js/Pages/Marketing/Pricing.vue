<template>
  <MarketingLayout>
    <div class="py-20 md:py-32">
      <div class="max-w-6xl mx-auto px-4 md:px-6">
        <!-- Header -->
        <div class="text-center mb-16">
          <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-slate-900 dark:text-white mb-6">
            Simple, transparent pricing
          </h1>
          <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto mb-8">
            Choose a plan that grows with you. Upgrade anytime for more features and support.
          </p>

          <!-- Plan Toggle (placeholder) -->
          <div class="flex items-center justify-center gap-4">
            <span class="text-slate-600 dark:text-slate-300 font-medium">Monthly</span>
            <button
              @click="isYearly = !isYearly"
              class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
              :class="isYearly ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-white/10'"
            >
              <span
                :class="[
                  'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                  isYearly ? 'translate-x-6' : 'translate-x-1',
                ]"
              ></span>
            </button>
            <span class="text-slate-600 dark:text-slate-300 font-medium">
              Yearly
              <span class="text-indigo-600 dark:text-indigo-400 font-semibold ml-1">(Coming soon)</span>
            </span>
          </div>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16 max-w-5xl mx-auto">
          <div
            v-for="plan in transformedPlans"
            :key="plan.name"
            class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/[0.04] backdrop-blur p-8 relative shadow-sm dark:shadow-none"
            :class="plan.popular ? 'ring-2 ring-indigo-500/50' : ''"
          >
            <div v-if="plan.popular" class="absolute top-0 right-6 -translate-y-1/2">
              <span class="bg-gradient-to-r from-indigo-500 to-cyan-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                Popular
              </span>
            </div>

            <div class="mb-6">
              <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">{{ plan.name }}</h3>
              <div class="flex items-baseline">
                <span class="text-5xl font-bold text-slate-900 dark:text-white">{{ plan.price }}</span>
                <span class="text-slate-600 dark:text-slate-400 ml-2">{{ plan.period }}</span>
              </div>
              <p class="text-slate-600 dark:text-slate-300 text-sm mt-2">{{ plan.description }}</p>
            </div>

            <ul class="space-y-3 mb-8">
              <li v-for="feature in plan.features" :key="feature" class="flex items-start text-slate-600 dark:text-slate-300 text-sm">
                <svg class="w-5 h-5 text-indigo-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ feature }}
              </li>
            </ul>

            <MarketingButton
              :href="getCheckoutUrl(plan.key)"
              as="a"
              variant="ghost"
              class="w-full justify-center"
            >
              Get Started
            </MarketingButton>
          </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-20 border-t border-slate-200 dark:border-white/10 pt-16">
          <h2 class="text-3xl font-bold text-slate-900 dark:text-white text-center mb-12">Pricing FAQ</h2>
          <div class="max-w-3xl mx-auto space-y-4">
            <div
              v-for="(faq, index) in faqs"
              :key="index"
              class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/[0.04] backdrop-blur overflow-hidden shadow-sm dark:shadow-none"
            >
              <button
                @click="toggleFaq(index)"
                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 dark:hover:bg-white/[0.06] transition-colors"
              >
                <span class="font-semibold text-slate-900 dark:text-white">{{ faq.question }}</span>
                <svg
                  :class="[
                    'w-5 h-5 text-slate-500 dark:text-slate-400 transition-transform',
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
                class="px-6 pb-4 text-slate-600 dark:text-slate-300 leading-relaxed"
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
import MarketingButton from '@/Components/ui/MarketingButton.vue'

const props = defineProps({
  plans: Array,
  currentPlan: String,
  isAuthenticated: Boolean
})

const page = usePage()

const isYearly = ref(false)
const openFaqs = ref<number[]>([])

const getCheckoutUrl = (planKey: string): string => {
  if (page.props.auth?.user) {
    return `/billing/checkout?plan=${planKey}`
  }
  return `/register?plan=${planKey}`
}

const toggleFaq = (index: number): void => {
  if (openFaqs.value.includes(index)) {
    openFaqs.value = openFaqs.value.filter((i) => i !== index)
  } else {
    openFaqs.value.push(index)
  }
}

// Transform plans to match component structure
const transformedPlans = computed(() => {
  return props.plans?.map((plan: any) => ({
    key: plan.key, // Include key for checkout URL generation
    name: plan.name,
    price: `$${plan.price}`,
    period: '/mo',
    description: plan.key === 'pro' ? 'For power users and small teams' : 'For growing teams',
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

