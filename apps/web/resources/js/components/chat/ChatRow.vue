<template>
  <button
    @click="$emit('select', chat.id)"
    :class="[
      'w-full text-left p-3 rounded-2xl transition-all duration-200 group',
      'focus:outline-none focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-2 focus:ring-offset-slate-50',
      isActive 
        ? 'bg-slate-100 ring-1 ring-slate-200' 
        : 'hover:bg-slate-50'
    ]"
    :aria-current="isActive ? 'page' : undefined"
  >
    <div class="flex items-start justify-between gap-3">
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1">
          <h3 class="text-sm font-semibold text-slate-900 truncate">
            {{ chat.title || 'Untitled Chat' }}
          </h3>
          <div v-if="chat.unread" class="w-2 h-2 bg-indigo-500 rounded-full flex-shrink-0"></div>
        </div>
        
        <p v-if="chat.lastMessage" class="text-xs text-slate-600 truncate">
          {{ chat.lastMessage }}
        </p>
        
        <div class="flex items-center justify-between mt-2">
          <span class="text-xs text-slate-500">
            {{ formatTime(chat.updatedAt) }}
          </span>
          <div v-if="chat.pinned" class="text-slate-500">
            <Pin class="h-3 w-3" />
          </div>
        </div>
      </div>
      
      <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
        <button
          @click.stop="$emit('pin', chat.id)"
          :class="[
            'p-1 rounded-lg transition-colors',
            chat.pinned 
              ? 'text-amber-500 hover:text-amber-400' 
              : 'text-slate-500 hover:text-slate-600'
          ]"
          :title="chat.pinned ? 'Unpin chat' : 'Pin chat'"
        >
          <Pin class="h-3 w-3" />
        </button>
      </div>
    </div>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Pin } from 'lucide-vue-next'

interface ChatSummary {
  id: string
  title: string
  lastMessage?: string
  updatedAt: string
  pinned?: boolean
  unread?: boolean
}

interface Props {
  chat: ChatSummary
  activeId?: string
}

const props = defineProps<Props>()

defineEmits<{
  select: [id: string]
  pin: [id: string]
}>()

const isActive = computed(() => props.chat.id === props.activeId)

const formatTime = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = (now.getTime() - date.getTime()) / (1000 * 60 * 60)
  
  if (diffInHours < 1) {
    return 'Just now'
  } else if (diffInHours < 24) {
    return `${Math.floor(diffInHours)}h ago`
  } else if (diffInHours < 168) { // 7 days
    return `${Math.floor(diffInHours / 24)}d ago`
  } else {
    return date.toLocaleDateString()
  }
}
</script>
