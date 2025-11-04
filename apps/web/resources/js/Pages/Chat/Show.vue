<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="chat.workspace_id">
    <div class="flex h-screen">
      <!-- Chat Sidebar -->
      <div class="w-80 bg-white border-r border-gray-200 flex flex-col">
        <!-- Chat Header -->
        <div class="p-4 border-b border-gray-200">
    <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">{{ chat.title || 'New Chat' }}</h2>
            <button @click="shareChat" class="text-gray-400 hover:text-gray-600">
              <Share2 class="h-5 w-5" />
            </button>
          </div>
        </div>

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto">
          <div class="p-4">
            <button @click="createNewChat" 
                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
              <Plus class="h-4 w-4 mr-2" />
              New Chat
            </button>
    </div>

          <div class="px-4 space-y-1">
            <div v-for="chatItem in recentChats" :key="chatItem.id" 
                 :class="[chatItem.id === chat.id ? 'bg-indigo-50 border-indigo-200' : 'border-transparent', 'border rounded-lg p-3 cursor-pointer hover:bg-gray-50']"
                 @click="navigateToChat(chatItem.id)">
              <div class="text-sm font-medium text-gray-900 truncate">
                {{ chatItem.title || 'Untitled Chat' }}
              </div>
              <div class="text-xs text-gray-500 mt-1">
                {{ formatDate(chatItem.last_message_at) }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Chat Area -->
      <div class="flex-1 flex flex-col">
        <!-- Chat Controls -->
        <div class="bg-white border-b border-gray-200 p-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <ModelSwitcher :model="selectedModel" :availableModels="availableModels" @update:model="updateModel" />
              <PersonaPicker :personas="personas" :personaId="chat.persona_id" @update:persona="updatePersona" />
            </div>
            
            <div class="flex items-center space-x-2">
              <button @click="clearChat" class="text-gray-400 hover:text-gray-600">
                <Trash2 class="h-5 w-5" />
              </button>
            </div>
          </div>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
          <!-- Loading skeleton for initial message load -->
          <div v-if="isLoadingMessages" class="space-y-4">
            <SkeletonLoader type="chat-message" :rows="3" />
          </div>
          
          <!-- Actual messages -->
          <div v-else>
            <div v-for="message in messages" :key="message.id" 
                 :class="[message.role === 'user' ? 'flex justify-end' : 'flex justify-start']">
              <div :class="[message.role === 'user' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-900', 'max-w-3xl rounded-lg px-4 py-2']">
                <div class="text-sm font-medium mb-1">
                  {{ message.role === 'user' ? 'You' : (message.model_key || 'Assistant') }}
                </div>
                <div class="whitespace-pre-wrap">{{ message.content }}</div>
                <div class="text-xs opacity-70 mt-2">
                  {{ formatTime(message.created_at) }}
                </div>
              </div>
      </div>
    </div>

          <!-- Streaming Message -->
          <div v-if="streamingMessage" class="flex justify-start">
            <div class="max-w-3xl rounded-lg px-4 py-2 bg-gray-100 text-gray-900">
              <div class="text-sm font-medium mb-1">Assistant</div>
              <div class="whitespace-pre-wrap">{{ streamingMessage }}</div>
              <div class="inline-block w-2 h-4 bg-gray-400 animate-pulse ml-1"></div>
            </div>
          </div>
        </div>

        <!-- Message Input -->
        <div class="bg-white border-t border-gray-200 p-4">
          <form @submit.prevent="sendMessage" class="flex space-x-4">
            <div class="flex-1">
              <textarea 
                v-model="inputMessage" 
                @keydown="handleKeydown"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 resize-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                rows="3"
                placeholder="Type your message..."
                :disabled="isLoading"
              ></textarea>
            </div>
            <div class="flex flex-col space-y-2">
              <button 
                type="submit" 
                :disabled="!inputMessage.trim() || isLoading"
                class="px-6 py-3 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <Send class="h-5 w-5" />
              </button>
              <button 
                type="button" 
                @click="attachFile"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
              >
                <Paperclip class="h-5 w-5" />
              </button>
      </div>
    </form>

          <!-- File Attachments -->
          <div v-if="attachments.length > 0" class="mt-4">
            <div class="flex flex-wrap gap-2">
              <div v-for="attachment in attachments" :key="attachment.id" 
                   class="flex items-center space-x-2 bg-gray-100 rounded-lg px-3 py-2">
                <File class="h-4 w-4 text-gray-500" />
                <span class="text-sm text-gray-700">{{ attachment.name }}</span>
                <button @click="removeAttachment(attachment.id)" class="text-gray-400 hover:text-gray-600">
                  <X class="h-4 w-4" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ModelSwitcher from '@/components/ModelSwitcher.vue'
import PersonaPicker from '@/components/PersonaPicker.vue'
import SkeletonLoader from '@/components/SkeletonLoader.vue'
import { Send, Paperclip, Share2, Plus, Trash2, File, X } from 'lucide-vue-next'

interface Message {
  id: number
  role: string
  content: string
  model_key?: string
  created_at: string
}

interface Chat {
  id: number
  title?: string
  workspace_id: number
  persona_id?: number
  messages: Message[]
}

interface Props {
  chat: Chat
  user: any
  workspaces: any[]
  availableModels: any[]
  personas: any[]
  recentChats: any[]
}

const props = defineProps<Props>()

const inputMessage = ref('')
const selectedModel = ref('auto')
const streamingMessage = ref('')
const isLoading = ref(false)
const isLoadingMessages = ref(false)
const attachments = ref<any[]>([])

const messages = computed(() => props.chat.messages || [])

let eventSource: EventSource | null = null

const sendMessage = async () => {
  if (!inputMessage.value.trim() || isLoading.value) return
  
  isLoading.value = true
  streamingMessage.value = ''
  
  try {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    if (!csrfToken) {
      throw new Error('CSRF token not found')
    }

    const response = await fetch(`/chats/${props.chat.id}/send-message`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
      credentials: 'same-origin',
      body: JSON.stringify({
        content: inputMessage.value,
        model: selectedModel.value,
      }),
    })

    if (!response.ok) {
      const errorText = await response.text()
      console.error('Response error:', response.status, errorText)
      throw new Error(`Failed to send message: ${response.status}`)
    }

    // Handle streaming response
    const reader = response.body?.getReader()
    const decoder = new TextDecoder()
    
    if (reader) {
      while (true) {
        const { done, value } = await reader.read()
        if (done) break
        
        const chunk = decoder.decode(value)
        const lines = chunk.split('\n')
        
        for (const line of lines) {
          if (line.startsWith('data: ')) {
            const data = line.slice(6)
            if (data === '[DONE]') {
              isLoading.value = false
              streamingMessage.value = ''
              // Refresh the page to show the new messages
              router.reload()
              return
            }
            
            try {
              const parsed = JSON.parse(data)
              if (parsed.delta) {
                streamingMessage.value += parsed.delta
              }
              if (parsed.error) {
                console.error('Streaming error:', parsed.error)
                isLoading.value = false
              }
            } catch (e) {
              // Ignore parsing errors for incomplete chunks
            }
          }
        }
      }
    }
  } catch (error) {
    console.error('Error sending message:', error)
    isLoading.value = false
  }
}

const updateModel = (model: string) => {
  selectedModel.value = model
  // Update chat settings
  router.post(`/chats/${props.chat.id}/switch-model`, { model })
}

const updatePersona = (personaId: number | null) => {
  // Update chat persona
  router.post(`/chats/${props.chat.id}/switch-persona`, { persona_id: personaId })
}

const shareChat = async () => {
  try {
    const response = await fetch(`/chats/${props.chat.id}/share`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
    })
    
    const data = await response.json()
    if (data.share_url) {
      await navigator.clipboard.writeText(data.share_url)
      // Show success message
    }
  } catch (error) {
    console.error('Error sharing chat:', error)
  }
}

const createNewChat = () => {
  router.post('/chats', {
    workspace_id: props.chat.workspace_id,
  })
}

const navigateToChat = (chatId: number) => {
  router.visit(`/chats/${chatId}`)
}

const clearChat = () => {
  if (confirm('Are you sure you want to clear this chat?')) {
    router.delete(`/chats/${props.chat.id}`)
  }
}

const attachFile = () => {
  // File attachment logic
}

const removeAttachment = (attachmentId: number) => {
  attachments.value = attachments.value.filter(a => a.id !== attachmentId)
}

const handleKeydown = (event: KeyboardEvent) => {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault()
    sendMessage()
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const formatTime = (dateString: string) => {
  return new Date(dateString).toLocaleTimeString()
}

onMounted(() => {
  // Set up any initial state
})

onUnmounted(() => {
  if (eventSource) {
    eventSource.close()
  }
})
</script>
