<template>
  <AppLayout 
    :user="user" 
    :workspaces="workspaces" 
    :currentWorkspace="currentWorkspace"
  >
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Billing & Usage
        </h2>
        <div class="flex space-x-3">
          <a
            v-if="!subscription"
            :href="route('billing.checkout')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
          >
            Upgrade Plan
          </a>
          <Link
            v-else
            :href="route('billing.portal')"
            class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-150 ease-in-out"
          >
            Manage Billing
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Current Plan -->
        <div class="bg-white shadow rounded-lg mb-8">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Current Plan</h3>
            <div v-if="subscription" class="flex items-center justify-between">
              <div>
                <div class="text-2xl font-bold text-gray-900">{{ subscription.plan_name || 'Business' }}</div>
                <div class="text-sm text-gray-500">
                  ${{ subscription.plan_price || 79 }}/month
                </div>
                <div v-if="subscription.status === 'active'" class="mt-2">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    Active
                  </span>
                </div>
                <div v-else-if="subscription.status === 'past_due'" class="mt-2">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Past Due
                  </span>
                </div>
                <div v-else class="mt-2">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                    {{ subscription.status }}
                  </span>
                </div>
              </div>
              <div class="text-right">
                <div class="text-sm text-gray-500">Next billing date</div>
                <div class="text-lg font-medium text-gray-900">
                  {{ formatDate(subscription.current_period_end) }}
                </div>
              </div>
            </div>
            <div v-else class="text-center py-6">
              <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <span class="text-gray-400 text-2xl">💳</span>
              </div>
              <h4 class="text-lg font-medium text-gray-900 mb-2">No active subscription</h4>
              <p class="text-gray-500 mb-4">Please subscribe to a plan to access all features.</p>
              <a
                :href="route('billing.checkout')"
                class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
              >
                Upgrade to Pro
              </a>
            </div>
          </div>
        </div>

        <!-- Usage This Month -->
        <div class="bg-white shadow rounded-lg mb-8">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Usage This Month</h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
              <div>
                <dt class="text-sm font-medium text-gray-500">Words Used</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                  {{ formatNumber(currentUsage.words_used) }}
                </dd>
                <dd class="text-sm text-gray-500">
                  of {{ formatNumber(currentUsage.words_limit) }} words
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">API Calls</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                  {{ formatNumber(currentUsage.api_calls) }}
                </dd>
                <dd class="text-sm text-gray-500">
                  of {{ formatNumber(currentUsage.api_calls_limit) }} calls
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Cost This Month</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                  ${{ (currentUsage.cost || 0).toFixed(2) }}
                </dd>
                <dd class="text-sm text-gray-500">
                  {{ (currentUsage.cost_percentage || 0).toFixed(1) }}% of plan limit
                </dd>
              </div>
            </div>
            
            <!-- Usage Progress Bar -->
            <div class="mt-6">
              <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Usage Progress</span>
                <span>{{ (currentUsage.usage_percentage || 0).toFixed(1) }}%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div
                  class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                  :style="{ width: `${Math.min(currentUsage.usage_percentage, 100)}%` }"
                ></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Method -->
        <div v-if="subscription" class="bg-white shadow rounded-lg mb-8">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Payment Method</h3>
            <div v-if="paymentMethod" class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                  <span class="text-gray-600 font-semibold">💳</span>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">
                    {{ paymentMethod.brand }} •••• {{ paymentMethod.last_four }}
                  </div>
                  <div class="text-sm text-gray-500">
                    Expires {{ paymentMethod.exp_month }}/{{ paymentMethod.exp_year }}
                  </div>
                </div>
              </div>
              <Link
                :href="route('billing.portal')"
                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
              >
                Update
              </Link>
            </div>
            <div v-else class="text-center py-4">
              <p class="text-gray-500 mb-2">No payment method on file</p>
              <Link
                :href="route('billing.portal')"
                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
              >
                Add Payment Method
              </Link>
            </div>
          </div>
        </div>

        <!-- Recent Invoices -->
        <div v-if="subscription" class="bg-white shadow rounded-lg mb-8">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Invoices</h3>
            <div v-if="invoices.length > 0" class="space-y-3">
              <div
                v-for="invoice in invoices"
                :key="invoice.id"
                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
              >
                <div>
                  <div class="text-sm font-medium text-gray-900">
                    {{ formatDate(invoice.created_at) }}
                  </div>
                  <div class="text-sm text-gray-500">
                    Invoice #{{ invoice.number }}
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-sm font-medium text-gray-900">
                    ${{ (invoice.amount_paid || 0).toFixed(2) }}
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ invoice.status }}
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-4">
              <p class="text-gray-500">No invoices yet</p>
            </div>
          </div>
        </div>

        <!-- Plan Comparison -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Available Plans</h3>
            
            <!-- Monthly/Yearly Toggle -->
            <div class="flex items-center justify-center gap-2 mb-6">
              <div class="relative inline-flex items-center gap-2">
                <div class="inline-flex bg-gray-100 dark:bg-slate-800 rounded-2xl p-1 border border-gray-200/50 dark:border-slate-700/50 shadow-sm">
                  <button @click="isYearly = false" :class="[
                    'px-6 py-2 rounded-2xl font-medium text-sm transition-all relative z-10',
                    !isYearly
                      ? 'bg-gray-900 dark:bg-gray-700 text-white shadow-sm'
                      : 'text-gray-700 dark:text-gray-300'
                  ]">
                    Monthly
                  </button>
                  <button @click="isYearly = true" :class="[
                    'px-6 py-2 rounded-2xl font-medium text-sm transition-all relative z-10',
                    isYearly
                      ? 'bg-gray-900 dark:bg-gray-700 text-white shadow-sm'
                      : 'text-gray-700 dark:text-gray-300'
                  ]">
                    Yearly
                  </button>
                </div>
                <span
                  v-if="isYearly"
                  class="bg-gray-900 dark:bg-gray-700 text-white text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap shadow-sm">
                  30% off
                </span>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
              <div 
                v-for="plan in plans" 
                :key="plan.key"
                class="border border-gray-200 rounded-lg p-6"
                :class="plan.key === 'pro' ? 'border-2 border-indigo-500 relative' : ''"
              >
                <div v-if="plan.key === 'pro'" class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                  <span class="bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ plan.name }}</h4>
                <div class="text-3xl font-bold text-gray-900 mb-4">
                  ${{ getPlanPrice(plan.key) }}<span class="text-lg text-gray-500">/month</span>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                  <li>{{ plan.tokens.toLocaleString() }} tokens/month</li>
                  <li v-if="plan.chats">{{ plan.chats }} chats/month</li>
                  <li v-else>Unlimited chats</li>
                  <li>{{ plan.models.length }} AI models</li>
                  <li v-if="plan.seats">{{ plan.seats }} seats included</li>
                  <li v-else>Unlimited seats</li>
                </ul>
                <a
                  :href="route('billing.checkout', { plan: plan.key, interval: isYearly ? 'yearly' : 'monthly' })"
                  class="mt-4 w-full text-white py-2 px-4 rounded-md transition duration-150 ease-in-out text-center block"
                  :class="plan.key === 'pro' 
                    ? 'bg-indigo-600 hover:bg-indigo-700' 
                    : 'bg-gray-800 hover:bg-gray-900'"
                >
                  Choose {{ plan.name }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

interface Subscription {
  id: string
  plan_name: string
  plan_price: number
  status: string
  current_period_end: string
}

interface PaymentMethod {
  brand: string
  last_four: string
  exp_month: number
  exp_year: number
}

interface Invoice {
  id: string
  number: string
  amount_paid: number
  status: string
  created_at: string
}

interface CurrentUsage {
  words_used: number
  words_limit: number
  api_calls: number
  api_calls_limit: number
  cost: number
  cost_percentage: number
  usage_percentage: number
}

interface Props {
  user: {
    id: number
    name: string
    email: string
    plan: string
  }
  workspaces: Array<{
    id: number
    name: string
    slug: string
  }>
  currentWorkspace?: {
    id: number
    name: string
    slug: string
  }
  subscription?: Subscription
  paymentMethod?: PaymentMethod
  invoices: Invoice[]
  currentUsage: CurrentUsage
  plans: Array<{
    key: string
    name: string
    price: number
    tokens: number
    chats: number | null
    models: string[]
    features: string[]
    seats: number | null
  }>
}

const props = defineProps<Props>()

const isYearly = ref(false)

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

const getPlanPrice = (planKey: string): string => {
  const prices = priceMap[planKey] || { monthly: 0, yearly: 0 }
  const price = isYearly.value ? prices.yearly : prices.monthly
  return price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const formatNumber = (num: number) => {
  return new Intl.NumberFormat().format(num)
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}
</script>
