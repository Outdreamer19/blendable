<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="workspace || currentWorkspace">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Usage Analytics
          </h2>
          <div class="flex space-x-3">
            <select
              v-model="selectedPeriod"
              @change="updatePeriod"
              class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
              <option value="7">Last 7 days</option>
              <option value="30">Last 30 days</option>
              <option value="90">Last 90 days</option>
              <option value="365">Last year</option>
            </select>
            <Link
              :href="route('usage.export', { period: selectedPeriod })"
              class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
            >
              Export CSV
            </Link>
          </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mb-8">
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-indigo-600 font-semibold">📊</span>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Words</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ formatNumber(usageStats.total_words || 0) }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-green-600 font-semibold">💰</span>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Cost</dt>
                    <dd class="text-lg font-medium text-gray-900">${{ (usageStats.total_cost || 0).toFixed(2) }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 font-semibold">🤖</span>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">API Calls</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ formatNumber(usageStats.total_calls) }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-purple-600 font-semibold">📈</span>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Avg Cost/Day</dt>
                    <dd class="text-lg font-medium text-gray-900">${{ (usageStats.avg_cost_per_day || 0).toFixed(2) }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-green-600 font-semibold">💰</span>
                  </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Estimated Cost</dt>
                    <dd class="text-lg font-medium text-gray-900">${{ (usageStats.estimated_cost || 0).toFixed(2) }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Usage by Model -->
        <div class="bg-white shadow rounded-lg mb-8">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Usage by Model</h3>
            <div class="space-y-4">
              <div
                v-for="model in modelUsage"
                :key="model.model_key"
                class="flex items-center justify-between"
              >
                <div class="flex items-center">
                  <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-gray-600 font-semibold text-sm">
                      {{ getModelIcon(model.provider) }}
                    </span>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ model.display_name }}</div>
                    <div class="text-sm text-gray-500">{{ model.provider }}</div>
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-sm font-medium text-gray-900">{{ formatNumber(model.words) }} words</div>
                  <div class="text-sm text-gray-500">${{ (model.cost || 0).toFixed(2) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Daily Usage Chart -->
        <div class="bg-white shadow rounded-lg mb-8">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Daily Usage</h3>
            <div class="h-64 flex items-end justify-between space-x-1">
              <div
                v-for="day in dailyUsage"
                :key="day.date"
                class="flex-1 bg-indigo-200 rounded-t"
                :style="{ height: `${(day.words / Math.max(...dailyUsage.map(d => d.words))) * 100}%` }"
                :title="`${day.date}: ${formatNumber(day.words)} words, $${(day.cost || 0).toFixed(2)}`"
              ></div>
            </div>
            <div class="mt-4 flex justify-between text-xs text-gray-500">
              <span>{{ dailyUsage[0]?.date }}</span>
              <span>{{ dailyUsage[dailyUsage.length - 1]?.date }}</span>
            </div>
          </div>
        </div>

        <!-- Usage by Workspace -->
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Usage by Workspace</h3>
            <div class="space-y-4">
              <div
                v-for="workspace in workspaceUsage"
                :key="workspace.id"
                class="flex items-center justify-between"
              >
                <div class="flex items-center">
                  <div class="h-8 w-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-indigo-600 font-semibold text-sm">
                      {{ workspace.name.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ workspace.name }}</div>
                    <div class="text-sm text-gray-500">{{ workspace.team_name }}</div>
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-sm font-medium text-gray-900">{{ formatNumber(workspace.words) }} words</div>
                  <div class="text-sm text-gray-500">${{ (workspace.cost || 0).toFixed(2) }}</div>
                </div>
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
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

interface UsageStats {
  total_words: number
  total_cost: number
  total_calls: number
  avg_cost_per_day: number
}

interface ModelUsage {
  model_key: string
  display_name: string
  provider: string
  words: number
  cost: number
}

interface DailyUsage {
  date: string
  words: number
  cost: number
}

interface WorkspaceUsage {
  id: number
  name: string
  team_name: string
  words: number
  cost: number
}

interface Props {
  usageStats: UsageStats
  modelUsage: ModelUsage[]
  dailyUsage: DailyUsage[]
  workspaceUsage: WorkspaceUsage[]
  period: number
  user: any
  workspaces: any[]
  workspace: any | null
  currentWorkspace?: any
}

const props = defineProps<Props>()

const selectedPeriod = ref(props.period)

const formatNumber = (num: number) => {
  return new Intl.NumberFormat().format(num)
}

const getModelIcon = (provider: string) => {
  const icons: Record<string, string> = {
    openai: '🤖',
    anthropic: '🧠',
    google: '🔍',
    mistral: '🌪️',
    deepseek: '🔬',
  }
  return icons[provider] || '🤖'
}

const updatePeriod = () => {
  router.get(route('usage.index'), { period: selectedPeriod.value })
}
</script>
