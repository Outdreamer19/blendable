<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="close"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Select AI Model</h3>
            <button @click="close" class="text-gray-400 hover:text-gray-600">
              <X class="h-6 w-6" />
            </button>
          </div>
          <div class="mt-2">
            <p class="text-sm text-gray-600">Current AI Model: <span class="font-medium">{{ currentModel?.display_name || 'Auto' }}</span></p>
          </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
          <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="flex-1 relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Search class="h-5 w-5 text-gray-400" />
              </div>
              <input
                v-model="searchQuery"
                type="text"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Search Models..."
              />
            </div>
            
            <!-- Filter -->
            <div class="flex items-center">
              <Filter class="h-5 w-5 text-gray-400" />
            </div>
          </div>
          
          <!-- Filter Buttons -->
          <div class="mt-4 flex space-x-2">
            <button
              v-for="filter in filters"
              :key="filter.key"
              @click="activeFilter = filter.key"
              :class="[
                activeFilter === filter.key
                  ? 'bg-indigo-100 text-indigo-700 border-indigo-200'
                  : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50',
                'px-3 py-1 text-sm font-medium border rounded-md transition-colors'
              ]"
            >
              {{ filter.label }}
            </button>
          </div>
        </div>

        <!-- Models List -->
        <div class="bg-white max-h-96 overflow-y-auto">
          <div class="px-6 py-4">
            <!-- Recent Models Section -->
            <div v-if="recentModels.length > 0" class="mb-6">
              <h4 class="text-sm font-medium text-gray-900 mb-3">Recent Models</h4>
              <div class="space-y-2">
                <div
                  v-for="model in recentModels"
                  :key="`recent-${model.model_key}`"
                  @click="selectModel(model)"
                  :class="[
                    selectedModel?.model_key === model.model_key
                      ? 'bg-indigo-50 border-indigo-200'
                      : 'border-gray-200 hover:bg-gray-50',
                    'border rounded-lg p-3 cursor-pointer transition-colors'
                  ]"
                >
                  <div class="flex items-center space-x-3">
                    <div :class="getModelIconClass(model.provider)" class="h-6 w-6 rounded-full flex items-center justify-center">
                      <component :is="getModelIcon(model.provider)" class="h-4 w-4" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900 truncate">{{ model.display_name }}</p>
                      <p class="text-xs text-gray-500">{{ model.provider }}</p>
                    </div>
                    <span :class="getMultiplierClass(model.multiplier)" class="text-xs font-medium">
                      {{ formatMultiplier(model.multiplier) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- All Models Section -->
            <div>
              <h4 class="text-sm font-medium text-gray-900 mb-3">All Models</h4>
              <div class="space-y-2">
                <div
                  v-for="model in filteredModels"
                  :key="model.model_key"
                  @click="selectModel(model)"
                  :class="[
                    selectedModel?.model_key === model.model_key
                      ? 'bg-indigo-50 border-indigo-200'
                      : 'border-gray-200 hover:bg-gray-50',
                    'border rounded-lg p-4 cursor-pointer transition-colors'
                  ]"
                >
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                      <!-- Model Icon -->
                      <div class="flex-shrink-0">
                        <div :class="getModelIconClass(model.provider)" class="h-8 w-8 rounded-full flex items-center justify-center">
                          <component :is="getModelIcon(model.provider)" class="h-5 w-5" />
                        </div>
                      </div>
                      
                      <!-- Model Info -->
                      <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2">
                          <p class="text-sm font-medium text-gray-900 truncate">
                            {{ model.display_name }}
                          </p>
                          <span v-if="model.model_key === 'auto'" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                            Auto
                          </span>
                          <span v-if="isRecentModel(model)" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            Recent
                          </span>
                        </div>
                        <p class="text-xs text-gray-500">{{ model.provider }} • {{ model.model_key }}</p>
                      </div>
                    </div>
                    
                    <!-- Model Details -->
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                      <span>{{ model.type || 'Text' }}</span>
                      <span>{{ formatContextWindow(model.context_window) }}</span>
                      <span :class="getMultiplierClass(model.multiplier)">
                        {{ formatMultiplier(model.multiplier) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
          <button
            @click="close"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Cancel
          </button>
          <button
            @click="confirmSelection"
            :disabled="!selectedModel"
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Select Model
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { X, Search, Filter, Zap, Brain, Sparkles, Bot } from 'lucide-vue-next'

interface Model {
  id: number
  provider: string
  model_key: string
  display_name: string
  context_window: number
  multiplier: number
  type?: string
  enabled: boolean
}

interface Props {
  isOpen: boolean
  models: Model[]
  currentModel?: Model
}

const props = defineProps<Props>()
const emit = defineEmits<{
  close: []
  select: [model: Model]
}>()

const searchQuery = ref('')
const activeFilter = ref('LLM')
const selectedModel = ref<Model | null>(null)

const filters = [
  { key: 'LLM', label: 'LLM' },
  { key: 'Image / Video', label: 'Image / Video' },
  { key: 'All', label: 'All' },
  { key: 'Moderated', label: 'Moderated' },
  { key: 'Unmoderated', label: 'Unmoderated' }
]

const recentModels = computed(() => {
  // Get recently used models from localStorage
  const recent = JSON.parse(localStorage.getItem('recentModels') || '[]')
  if (!Array.isArray(props.models)) {
    return []
  }
  return props.models.filter(model => 
    model.enabled && recent.includes(model.model_key)
  ).slice(0, 3)
})

const filteredModels = computed(() => {
  if (!Array.isArray(props.models)) {
    return []
  }
  
  let filtered = props.models.filter(model => model.enabled)
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(model => 
      model.display_name.toLowerCase().includes(query) ||
      model.model_key.toLowerCase().includes(query) ||
      model.provider.toLowerCase().includes(query)
    )
  }
  
  // Apply type filter
  if (activeFilter.value !== 'All') {
    if (activeFilter.value === 'LLM') {
      filtered = filtered.filter(model => !model.type || model.type === 'Text')
    } else if (activeFilter.value === 'Image / Video') {
      filtered = filtered.filter(model => model.type === 'Image' || model.type === 'Video')
    }
  }
  
  return filtered
})

const getModelIcon = (provider: string) => {
  switch (provider.toLowerCase()) {
    case 'openai':
      return Sparkles
    case 'anthropic':
      return Brain
    case 'google':
      return Bot
    default:
      return Zap
  }
}

const getModelIconClass = (provider: string) => {
  switch (provider.toLowerCase()) {
    case 'openai':
      return 'bg-green-100 text-green-600'
    case 'anthropic':
      return 'bg-orange-100 text-orange-600'
    case 'google':
      return 'bg-blue-100 text-blue-600'
    default:
      return 'bg-gray-100 text-gray-600'
  }
}

const getMultiplierClass = (multiplier: number) => {
  if (multiplier === 0) return 'text-green-600 font-medium'
  if (multiplier <= 1) return 'text-blue-600 font-medium'
  if (multiplier <= 3) return 'text-yellow-600 font-medium'
  return 'text-red-600 font-medium'
}

const formatContextWindow = (context: number) => {
  if (context >= 1000000) return `${((context || 0) / 1000000).toFixed(1)}M`
  if (context >= 1000) return `${((context || 0) / 1000).toFixed(0)}K`
  return context.toString()
}

const formatMultiplier = (multiplier: number) => {
  if (multiplier === 0) return 'Free'
  if (multiplier === 1) return '1x'
  return `${multiplier}x`
}

const selectModel = (model: Model) => {
  selectedModel.value = model
}

const isRecentModel = (model: Model) => {
  const recent = JSON.parse(localStorage.getItem('recentModels') || '[]')
  return recent.includes(model.model_key)
}

const confirmSelection = () => {
  if (selectedModel.value) {
    // Track recent model usage
    const recent = JSON.parse(localStorage.getItem('recentModels') || '[]')
    const updatedRecent = [selectedModel.value.model_key, ...recent.filter((key: string) => key !== selectedModel.value!.model_key)].slice(0, 5)
    localStorage.setItem('recentModels', JSON.stringify(updatedRecent))
    
    emit('select', selectedModel.value)
    close()
  }
}

const close = () => {
  selectedModel.value = null
  emit('close')
}

// Watch for modal open to reset selection
watch(() => props.isOpen, (isOpen) => {
  if (isOpen) {
    selectedModel.value = props.currentModel || null
  }
})
</script>
