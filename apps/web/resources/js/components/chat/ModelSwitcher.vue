<template>
  <div class="relative">
    <button
      @click="openModal"
      class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 hover:bg-slate-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-2 focus:ring-offset-slate-50"
    >
      <div :class="getModelIconClass(currentModel?.provider || 'auto')" class="h-4 w-4 rounded-full flex items-center justify-center">
        <component :is="getModelIcon(currentModel?.provider || 'auto')" class="h-3 w-3" />
      </div>
      <span>{{ currentModel?.display_name || 'Auto' }}</span>
      <ChevronDown class="h-4 w-4 text-slate-600" />
    </button>

    <ModelSelectionModal
      :isOpen="isModalOpen"
      :models="availableModels"
      :currentModel="currentModel"
      @close="closeModal"
      @select="handleModelSelect"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { ChevronDown, Sparkles, Brain, Bot, Zap } from 'lucide-vue-next'
import ModelSelectionModal from '@/Components/ModelSelectionModal.vue'

interface Model {
  id: number
  provider: string
  model_key: string
  display_name: string
  context_window: number
  multiplier: number
  enabled: boolean
}

interface Props {
  model?: string
  availableModels?: Model[]
}

const props = withDefaults(defineProps<Props>(), {
  model: 'auto',
  availableModels: () => []
})

const emit = defineEmits<{
  'update:model': [model: string]
}>()

const isModalOpen = ref(false)

const currentModel = computed(() => {
  if (props.model === 'auto') {
    return { model_key: 'auto', display_name: 'Auto', provider: 'auto' }
  }
  if (!Array.isArray(props.availableModels)) {
    return null
  }
  return props.availableModels.find(m => m.model_key === props.model)
})

const openModal = () => {
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const handleModelSelect = (model: Model) => {
  emit('update:model', model.model_key)
  closeModal()
}

const getModelIcon = (provider: string) => {
  switch (provider.toLowerCase()) {
    case 'openai':
      return Sparkles
    case 'anthropic':
      return Brain
    case 'google':
      return Bot
    case 'auto':
      return Zap
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
    case 'auto':
      return 'bg-purple-100 text-purple-600'
    default:
      return 'bg-gray-100 text-gray-600'
  }
}
</script>
