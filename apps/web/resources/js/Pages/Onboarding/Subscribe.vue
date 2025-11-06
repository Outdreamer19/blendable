<template>
  <MarketingLayout>
    <Head title="Choose Your Plan" />
    
    <section class="min-h-screen py-12 md:py-20 bg-gradient-to-b from-slate-50 to-white dark:from-slate-900 dark:to-slate-950">
      <div class="max-w-7xl mx-auto px-4 md:px-6">
        <!-- Welcome Header -->
        <div class="text-center mb-12">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-100 dark:bg-indigo-900/30 rounded-full shadow-sm border border-indigo-200 dark:border-indigo-800 mb-6">
            <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300 uppercase tracking-wide">Welcome to Blendable</span>
          </div>
          
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-normal tracking-tight text-gray-900 dark:text-white mb-4">
            Choose Your Plan
          </h1>
          <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-2">
            Get started with intelligent AI routing. Select a plan that fits your needs.
          </p>
          <p class="text-sm text-gray-500 dark:text-gray-500">
            All plans include a 14-day free trial. Cancel anytime.
          </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12 max-w-5xl mx-auto">
          <div
            v-for="plan in plans"
            :key="plan.key"
            class="relative rounded-3xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-lg hover:shadow-xl transition-all duration-300"
            :class="plan.popular ? 'border-indigo-500 dark:border-indigo-500 ring-2 ring-indigo-500/20' : ''"
          >
            <!-- Popular Badge -->
            <div v-if="plan.popular" class="absolute -top-4 left-1/2 -translate-x-1/2">
              <span class="bg-gradient-to-r from-indigo-500 to-cyan-500 text-white text-xs font-semibold px-4 py-1.5 rounded-full shadow-lg">
                Most Popular
              </span>
            </div>

            <!-- Plan Header -->
            <div class="mb-6">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ plan.name }}</h3>
              <div class="flex items-baseline">
                <span class="text-4xl font-bold text-gray-900 dark:text-white">£{{ plan.price }}</span>
                <span class="text-lg text-gray-500 dark:text-gray-400 ml-2">/month</span>
              </div>
            </div>

            <!-- Features -->
            <ul class="space-y-3 mb-8">
              <li class="flex items-start">
                <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">{{ plan.tokens.toLocaleString() }} tokens/month</span>
              </li>
              <li class="flex items-start">
                <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">{{ plan.models.length }} AI models</span>
              </li>
              <li v-for="feature in plan.features" :key="feature" class="flex items-start">
                <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">{{ formatFeature(feature) }}</span>
              </li>
              <li v-if="plan.seats" class="flex items-start">
                <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300">{{ plan.seats }} team seats included</span>
              </li>
            </ul>

            <!-- CTA Button -->
            <a
              :href="route('billing.checkout', { plan: plan.key })"
              class="block w-full text-center py-3 px-6 rounded-lg font-semibold text-white transition-all duration-200"
              :class="plan.popular 
                ? 'bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-700 hover:to-cyan-700 shadow-lg hover:shadow-xl' 
                : 'bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600'"
            >
              Get Started
            </a>
          </div>
        </div>

        <!-- Trust Indicators -->
        <div class="text-center mt-16">
          <div class="flex flex-wrap items-center justify-center gap-8 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>14-day free trial</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>Cancel anytime</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>Secure payment</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </MarketingLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import MarketingLayout from '@/Layouts/MarketingLayout.vue'

// TypeScript declaration for Ziggy route helper
declare const route: (name: string, params?: any) => string

interface Plan {
  key: string
  name: string
  price: number
  tokens: number
  chats: number | null
  models: string[]
  features: string[]
  seats: number | null
  popular?: boolean
}

interface Props {
  plans: Plan[]
  plan?: string | null
}

const props = defineProps<Props>()

// Mark Pro as popular and format plans
const plans = computed(() => {
  return props.plans.map(plan => ({
    ...plan,
    popular: plan.key === 'pro'
  }))
})

const formatFeature = (feature: string): string => {
  const featureMap: Record<string, string> = {
    'file_uploads': 'File uploads',
    'priority_queue': 'Priority queue',
    'saved_prompts': 'Saved prompts',
    'team_workspace': 'Team workspace',
    'shared_memory': 'Shared memory',
    'analytics': 'Advanced analytics',
    'roles': 'Role management',
  }
  return featureMap[feature] || feature.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}
</script>

