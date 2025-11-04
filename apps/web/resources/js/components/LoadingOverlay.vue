<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4">
      <div class="flex items-center space-x-3">
        <!-- Spinner -->
        <div class="flex-shrink-0">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>
        
        <!-- Content -->
        <div class="flex-1">
          <h3 class="text-lg font-medium text-gray-900">{{ title }}</h3>
          <p v-if="message" class="text-sm text-gray-500 mt-1">{{ message }}</p>
          
          <!-- Progress bar -->
          <div v-if="showProgress && progress !== null" class="mt-3">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
              <span>Progress</span>
              <span>{{ Math.round(progress) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div 
                class="bg-indigo-600 h-2 rounded-full transition-all duration-300 ease-out"
                :style="{ width: `${progress}%` }"
              ></div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Cancel button -->
      <div v-if="showCancel" class="mt-4 flex justify-end">
        <button
          @click="$emit('cancel')"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  show: boolean
  title: string
  message?: string
  progress?: number | null
  showProgress?: boolean
  showCancel?: boolean
}

defineProps<Props>()

defineEmits<{
  cancel: []
}>()
</script>
