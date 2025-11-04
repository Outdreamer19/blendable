<template>
  <div class="bg-white border-b border-slate-200 shadow-sm z-10 flex-shrink-0">
    <div class="p-2 md:p-3">
      <div class="flex items-center justify-between">
        <!-- Left: Chat Title and Model -->
        <div class="flex items-center gap-4">
          <div>
            <h1 class="text-lg font-semibold text-slate-900 leading-tight">
              {{ chatTitle || 'New Chat' }}
            </h1>
            <div class="flex items-center gap-2 mt-1">
              <!-- Model Switcher -->
              <ModelSwitcher
                :model="model"
                :available-models="availableModels"
                @update:model="$emit('update:model', $event)"
              />
              
              <!-- Persona Badge -->
              <div v-if="persona" class="flex items-center gap-1 px-2 py-1 bg-slate-100 rounded-lg">
                <User class="h-3 w-3 text-emerald-500" />
                <span class="text-xs font-medium text-slate-700">{{ persona }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2">
          <!-- Search -->
          <button
            @click="$emit('search')"
            class="p-2 text-slate-600 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors"
            title="Search (⌘K)"
          >
            <Search class="h-4 w-4" />
          </button>

          <!-- Share -->
          <button
            @click="$emit('share')"
            class="p-2 text-slate-600 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors"
            title="Share chat"
          >
            <Share2 class="h-4 w-4" />
          </button>

          <!-- Rename -->
          <button
            v-if="canEdit"
            @click="$emit('rename')"
            class="p-2 text-slate-600 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors"
            title="Rename chat"
          >
            <Edit3 class="h-4 w-4" />
          </button>

          <!-- Export -->
          <button
            v-if="canEdit"
            @click="$emit('export')"
            class="p-2 text-slate-600 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors"
            title="Export chat"
          >
            <Download class="h-4 w-4" />
          </button>

          <!-- Delete -->
          <button
            v-if="canDelete"
            @click="$emit('delete')"
            class="p-2 text-slate-600 hover:text-rose-500 rounded-lg hover:bg-slate-100 transition-colors"
            title="Delete chat"
          >
            <Trash2 class="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Search, Share2, Edit3, Download, Trash2, User } from 'lucide-vue-next'
import ModelSwitcher from './ModelSwitcher.vue'

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
  chatTitle?: string
  model?: string
  persona?: string
  availableModels?: Model[]
  canEdit?: boolean
  canDelete?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  canEdit: true,
  canDelete: true,
  availableModels: () => []
})

defineEmits<{
  'update:model': [model: string]
  search: []
  share: []
  rename: []
  export: []
  delete: []
}>()
</script>
