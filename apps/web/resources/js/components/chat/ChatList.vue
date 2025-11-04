<template>
  <div class="flex flex-col h-full">
    <!-- Search and New Chat -->
    <div class="p-3 md:p-4 lg:p-6 space-y-3">
      <div class="relative">
        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-slate-400" />
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search chats..."
          class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-2 focus:ring-offset-slate-50"
        />
      </div>
      
      <button
        @click="$emit('new-chat')"
        class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400 text-white rounded-xl font-medium text-sm hover:from-indigo-600 hover:via-sky-600 hover:to-cyan-500 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-2 focus:ring-offset-slate-50"
      >
        <Plus class="h-4 w-4" />
        New Chat
      </button>
    </div>

    <!-- Chat Sections -->
    <div class="flex-1 overflow-y-auto px-3 md:px-4 lg:px-6 space-y-6">
      <!-- Pinned Chats -->
      <div v-if="pinnedChats.length > 0" class="space-y-2">
        <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
          Pinned
        </h3>
        <div class="space-y-1">
          <ChatRow
            v-for="chat in pinnedChats"
            :key="chat.id"
            :chat="chat"
            :active-id="activeId"
            @select="$emit('select', $event)"
            @pin="$emit('pin', $event)"
          />
        </div>
      </div>

      <!-- Recent Chats -->
      <div v-if="recentChats.length > 0" class="space-y-2">
        <h3 class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
          Recent
        </h3>
        <div class="space-y-1">
          <ChatRow
            v-for="chat in recentChats"
            :key="chat.id"
            :chat="chat"
            :active-id="activeId"
            @select="$emit('select', $event)"
            @pin="$emit('pin', $event)"
          />
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredChats.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
        <div class="w-16 h-16 bg-slate-200 rounded-2xl flex items-center justify-center mb-4">
          <MessageSquare class="h-8 w-8 text-slate-600" />
        </div>
        <h3 class="text-sm font-semibold text-slate-700 mb-2">
          {{ searchQuery ? 'No chats found' : 'No chats yet' }}
        </h3>
        <p class="text-xs text-slate-500 max-w-xs">
          {{ searchQuery ? 'Try adjusting your search terms' : 'Start a new conversation to get started' }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { Search, Plus, MessageSquare } from 'lucide-vue-next'
import ChatRow from './ChatRow.vue'

interface ChatSummary {
  id: string
  title: string
  lastMessage?: string
  updatedAt: string
  pinned?: boolean
  unread?: boolean
}

interface Props {
  chats: ChatSummary[]
  activeId?: string
}

const props = defineProps<Props>()

defineEmits<{
  select: [id: string]
  pin: [id: string]
  'new-chat': []
}>()

const searchQuery = ref('')

const filteredChats = computed(() => {
  if (!searchQuery.value) return props.chats
  
  const query = searchQuery.value.toLowerCase()
  return props.chats.filter(chat => 
    chat.title.toLowerCase().includes(query) ||
    chat.lastMessage?.toLowerCase().includes(query)
  )
})

const pinnedChats = computed(() => 
  filteredChats.value.filter(chat => chat.pinned)
)

const recentChats = computed(() => 
  filteredChats.value.filter(chat => !chat.pinned)
)

// Debounce search
watch(searchQuery, (newQuery) => {
  // Search is handled reactively, no need for debouncing in this simple case
}, { immediate: true })
</script>
