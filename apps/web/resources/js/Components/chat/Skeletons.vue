<template>
  <div class="space-y-4">
    <!-- Chat List Skeletons -->
    <div v-if="type === 'chat-list'" class="space-y-3">
      <div v-for="i in count" :key="i" class="p-3 rounded-2xl bg-slate-900 dark:bg-slate-100 border border-slate-800 dark:border-slate-200">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 bg-slate-800 dark:bg-slate-200 rounded-full animate-pulse"></div>
          <div class="flex-1 space-y-2">
            <div class="h-4 bg-slate-800 dark:bg-slate-200 rounded animate-pulse w-3/4"></div>
            <div class="h-3 bg-slate-800 dark:bg-slate-200 rounded animate-pulse w-1/2"></div>
            <div class="h-3 bg-slate-800 dark:bg-slate-200 rounded animate-pulse w-1/4"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Message Skeletons -->
    <div v-else-if="type === 'messages'" class="space-y-6">
      <div v-for="i in count" :key="i" class="flex gap-3 animate-pulse">
        <!-- Avatar -->
        <div class="w-8 h-8 bg-slate-800 dark:bg-slate-200 rounded-full flex-shrink-0"></div>
        
        <!-- Message Content -->
        <div class="flex-1 max-w-3xl">
          <div class="bg-slate-900 dark:bg-slate-100 border border-slate-800 dark:border-slate-200 rounded-2xl p-4">
            <!-- Header -->
            <div class="flex items-center justify-between mb-3">
              <div class="h-3 bg-slate-800 dark:bg-slate-200 rounded w-16"></div>
              <div class="h-3 bg-slate-800 dark:bg-slate-200 rounded w-12"></div>
            </div>
            
            <!-- Content Lines -->
            <div class="space-y-2">
              <div class="h-4 bg-slate-800 dark:bg-slate-200 rounded w-full"></div>
              <div class="h-4 bg-slate-800 dark:bg-slate-200 rounded w-5/6"></div>
              <div v-if="i % 3 === 0" class="h-4 bg-slate-800 dark:bg-slate-200 rounded w-4/6"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Composer Skeleton -->
    <div v-else-if="type === 'composer'" class="sticky bottom-0 bg-slate-950 dark:bg-slate-50 border-t border-slate-800 dark:border-slate-200 p-3 md:p-4 lg:p-6">
      <div class="max-w-3xl mx-auto">
        <div class="relative">
          <div class="h-12 bg-slate-900 dark:bg-slate-100 border border-slate-800 dark:border-slate-200 rounded-2xl animate-pulse"></div>
          <div class="absolute right-3 top-3 flex gap-2">
            <div class="w-8 h-8 bg-slate-800 dark:bg-slate-200 rounded-lg animate-pulse"></div>
            <div class="w-8 h-8 bg-slate-800 dark:bg-slate-200 rounded-lg animate-pulse"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Header Skeleton -->
    <div v-else-if="type === 'header'" class="sticky top-0 bg-slate-950 dark:bg-slate-50 border-b border-slate-800 dark:border-slate-200 p-3 md:p-4 lg:p-6">
      <div class="flex items-center justify-between">
        <div class="space-y-2">
          <div class="h-6 bg-slate-800 dark:bg-slate-200 rounded w-32 animate-pulse"></div>
          <div class="flex gap-2">
            <div class="h-6 bg-slate-800 dark:bg-slate-200 rounded w-20 animate-pulse"></div>
            <div class="h-6 bg-slate-800 dark:bg-slate-200 rounded w-16 animate-pulse"></div>
          </div>
        </div>
        <div class="flex gap-2">
          <div v-for="i in 4" :key="i" class="w-8 h-8 bg-slate-800 dark:bg-slate-200 rounded-lg animate-pulse"></div>
        </div>
      </div>
    </div>

    <!-- Sidebar Skeleton -->
    <div v-else-if="type === 'sidebar'" class="w-80 bg-slate-950 dark:bg-slate-50 border-r border-slate-800 dark:border-slate-200 flex flex-col">
      <!-- Search and New Chat -->
      <div class="p-3 md:p-4 lg:p-6 space-y-3">
        <div class="h-10 bg-slate-900 dark:bg-slate-100 border border-slate-800 dark:border-slate-200 rounded-xl animate-pulse"></div>
        <div class="h-10 bg-slate-900 dark:bg-slate-100 border border-slate-800 dark:border-slate-200 rounded-xl animate-pulse"></div>
      </div>
      
      <!-- Chat List -->
      <div class="flex-1 overflow-y-auto px-3 md:px-4 lg:px-6 space-y-6">
        <div class="space-y-2">
          <div class="h-4 bg-slate-800 dark:bg-slate-200 rounded w-16 animate-pulse"></div>
          <div class="space-y-1">
            <div v-for="i in 5" :key="i" class="p-3 bg-slate-900 dark:bg-slate-100 border border-slate-800 dark:border-slate-200 rounded-2xl animate-pulse">
              <div class="space-y-2">
                <div class="h-4 bg-slate-800 dark:bg-slate-200 rounded w-3/4"></div>
                <div class="h-3 bg-slate-800 dark:bg-slate-200 rounded w-1/2"></div>
                <div class="h-3 bg-slate-800 dark:bg-slate-200 rounded w-1/4"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  type: 'chat-list' | 'messages' | 'composer' | 'header' | 'sidebar'
  count?: number
}

const props = withDefaults(defineProps<Props>(), {
  count: 3
})
</script>
