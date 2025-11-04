<template>
  <div class="animate-pulse">
    <!-- Chat Message Skeleton -->
    <div v-if="type === 'chat-message'" class="flex space-x-3 p-4">
      <div class="flex-shrink-0">
        <div class="h-8 w-8 bg-gray-200 rounded-full"></div>
      </div>
      <div class="flex-1 space-y-2">
        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
        <div class="space-y-2">
          <div class="h-4 bg-gray-200 rounded"></div>
          <div class="h-4 bg-gray-200 rounded w-5/6"></div>
          <div class="h-4 bg-gray-200 rounded w-4/6"></div>
        </div>
      </div>
    </div>

    <!-- Card Skeleton -->
    <div v-else-if="type === 'card'" class="bg-white shadow rounded-lg p-6">
      <div class="space-y-4">
        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
        <div class="h-4 bg-gray-200 rounded w-5/6"></div>
      </div>
    </div>

    <!-- Table Skeleton -->
    <div v-else-if="type === 'table'" class="bg-white shadow rounded-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
      </div>
      <div class="divide-y divide-gray-200">
        <div v-for="i in rows" :key="i" class="px-6 py-4">
          <div class="flex space-x-4">
            <div class="h-4 bg-gray-200 rounded w-1/4"></div>
            <div class="h-4 bg-gray-200 rounded w-1/3"></div>
            <div class="h-4 bg-gray-200 rounded w-1/6"></div>
            <div class="h-4 bg-gray-200 rounded w-1/5"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- List Skeleton -->
    <div v-else-if="type === 'list'" class="space-y-3">
      <div v-for="i in rows" :key="i" class="flex items-center space-x-3 p-3 bg-white rounded-lg shadow">
        <div class="h-10 w-10 bg-gray-200 rounded-full"></div>
        <div class="flex-1 space-y-2">
          <div class="h-4 bg-gray-200 rounded w-3/4"></div>
          <div class="h-3 bg-gray-200 rounded w-1/2"></div>
        </div>
        <div class="h-8 w-20 bg-gray-200 rounded"></div>
      </div>
    </div>

    <!-- Dashboard Stats Skeleton -->
    <div v-else-if="type === 'dashboard-stats'" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <div v-for="i in 4" :key="i" class="bg-white overflow-hidden shadow rounded-lg p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
            <div class="h-6 bg-gray-200 rounded w-1/2"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Skeleton -->
    <div v-else-if="type === 'form'" class="space-y-6">
      <div class="space-y-4">
        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
        <div class="h-10 bg-gray-200 rounded"></div>
      </div>
      <div class="space-y-4">
        <div class="h-4 bg-gray-200 rounded w-1/3"></div>
        <div class="h-10 bg-gray-200 rounded"></div>
      </div>
      <div class="space-y-4">
        <div class="h-4 bg-gray-200 rounded w-1/5"></div>
        <div class="h-24 bg-gray-200 rounded"></div>
      </div>
      <div class="flex justify-end space-x-3">
        <div class="h-10 w-20 bg-gray-200 rounded"></div>
        <div class="h-10 w-24 bg-gray-200 rounded"></div>
      </div>
    </div>

    <!-- Image Grid Skeleton -->
    <div v-else-if="type === 'image-grid'" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <div v-for="i in rows" :key="i" class="bg-white shadow rounded-lg overflow-hidden">
        <div class="h-48 bg-gray-200"></div>
        <div class="p-4 space-y-2">
          <div class="h-4 bg-gray-200 rounded w-3/4"></div>
          <div class="h-3 bg-gray-200 rounded w-1/2"></div>
        </div>
      </div>
    </div>

    <!-- Custom Skeleton -->
    <div v-else class="space-y-2">
      <div v-for="i in rows" :key="i" class="h-4 bg-gray-200 rounded" :style="{ width: getRandomWidth() }"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  type?: 'chat-message' | 'card' | 'table' | 'list' | 'dashboard-stats' | 'form' | 'image-grid' | 'custom'
  rows?: number
}

const props = withDefaults(defineProps<Props>(), {
  type: 'custom',
  rows: 3,
})

const getRandomWidth = () => {
  const widths = ['w-1/4', 'w-1/3', 'w-1/2', 'w-2/3', 'w-3/4', 'w-5/6']
  return widths[Math.floor(Math.random() * widths.length)]
}
</script>
