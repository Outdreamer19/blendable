<template>
  <div v-if="show" class="fixed top-4 right-4 z-50 max-w-sm w-full">
    <div
      :class="[
        'rounded-lg shadow-lg p-4 border-l-4',
        type === 'error' ? 'bg-red-50 border-red-400' : '',
        type === 'warning' ? 'bg-yellow-50 border-yellow-400' : '',
        type === 'success' ? 'bg-green-50 border-green-400' : '',
        type === 'info' ? 'bg-blue-50 border-blue-400' : '',
      ]"
    >
      <div class="flex">
        <div class="flex-shrink-0">
          <component
            :is="iconComponent"
            :class="[
              'h-5 w-5',
              type === 'error' ? 'text-red-400' : '',
              type === 'warning' ? 'text-yellow-400' : '',
              type === 'success' ? 'text-green-400' : '',
              type === 'info' ? 'text-blue-400' : '',
            ]"
          />
        </div>
        <div class="ml-3">
          <h3
            :class="[
              'text-sm font-medium',
              type === 'error' ? 'text-red-800' : '',
              type === 'warning' ? 'text-yellow-800' : '',
              type === 'success' ? 'text-green-800' : '',
              type === 'info' ? 'text-blue-800' : '',
            ]"
          >
            {{ title }}
          </h3>
          <div
            :class="[
              'mt-1 text-sm',
              type === 'error' ? 'text-red-700' : '',
              type === 'warning' ? 'text-yellow-700' : '',
              type === 'success' ? 'text-green-700' : '',
              type === 'info' ? 'text-blue-700' : '',
            ]"
          >
            {{ message }}
          </div>
          <div v-if="details" class="mt-2">
            <details class="text-xs">
              <summary class="cursor-pointer font-medium">Technical Details</summary>
              <pre class="mt-1 whitespace-pre-wrap">{{ details }}</pre>
            </details>
          </div>
        </div>
        <div class="ml-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button
              @click="close"
              :class="[
                'inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2',
                type === 'error' ? 'text-red-500 hover:bg-red-100 focus:ring-red-600' : '',
                type === 'warning' ? 'text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-600' : '',
                type === 'success' ? 'text-green-500 hover:bg-green-100 focus:ring-green-600' : '',
                type === 'info' ? 'text-blue-500 hover:bg-blue-100 focus:ring-blue-600' : '',
              ]"
            >
              <span class="sr-only">Dismiss</span>
              <X class="h-4 w-4" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { X, AlertCircle, AlertTriangle, CheckCircle, Info } from 'lucide-vue-next'

interface Props {
  type: 'error' | 'warning' | 'success' | 'info'
  title: string
  message: string
  details?: string
  duration?: number
}

const props = withDefaults(defineProps<Props>(), {
  duration: 5000,
})

const emit = defineEmits<{
  close: []
}>()

const show = ref(true)

const iconComponent = computed(() => {
  switch (props.type) {
    case 'error':
      return AlertCircle
    case 'warning':
      return AlertTriangle
    case 'success':
      return CheckCircle
    case 'info':
      return Info
    default:
      return Info
  }
})

const close = () => {
  show.value = false
  emit('close')
}

onMounted(() => {
  if (props.duration > 0) {
    setTimeout(() => {
      close()
    }, props.duration)
  }
})
</script>
