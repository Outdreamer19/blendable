<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="currentWorkspace">
    <div class="flex h-screen bg-gray-50">
      <!-- Left Sidebar -->
      <div class="w-16 bg-white border-r border-gray-200 flex flex-col items-center py-4 space-y-4">
        <!-- Logo -->
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
          <span class="text-white font-bold text-sm">O</span>
        </div>
        
        <!-- Quick Action Button -->
        <button
          @click="createNewChat"
          class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors"
        >
          <Plus class="h-5 w-5" />
        </button>
        
        <!-- Navigation Icons -->
        <nav class="flex flex-col space-y-2">
          <button
            v-for="item in navigationItems"
            :key="item.name"
            @click="navigateTo(item.route)"
            :class="[
              'w-10 h-10 rounded-lg flex items-center justify-center transition-colors',
              currentRoute === item.route
                ? 'bg-blue-100 text-blue-600'
                : 'text-gray-400 hover:text-gray-600 hover:bg-gray-100'
            ]"
            :title="item.name"
          >
            <component :is="item.icon" class="h-5 w-5" />
          </button>
        </nav>
        
        <!-- User Profile -->
        <div class="mt-auto">
          <div class="w-8 h-8 rounded-full border-2 border-gray-200 bg-purple-600 flex items-center justify-center">
            <span class="text-white text-sm font-medium">{{ user.name?.charAt(0) || 'U' }}</span>
          </div>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <!-- Model Selector -->
              <ModelSwitcher
                :model="selectedModel"
                :availableModels="availableModels"
                @update:model="updateModel"
              />
              
              <!-- Persona Selector -->
              <PersonaPicker
                :personas="personas"
                :personaId="selectedPersonaId"
                @update:persona="updatePersona"
              />
            </div>
            
            <div class="flex items-center space-x-2">
              <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                <Bell class="h-5 w-5" />
              </button>
              <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                <Settings class="h-5 w-5" />
              </button>
              <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                <HelpCircle class="h-5 w-5" />
              </button>
            </div>
          </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col items-center justify-center px-6">
          <!-- Welcome Message -->
          <div class="text-center max-w-2xl">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
              Hi, {{ user.name }}! How can I help?
            </h1>
            
            <!-- Input Field -->
            <div class="relative w-full max-w-4xl">
              <div class="relative">
                <textarea
                  v-model="inputMessage"
                  @keydown="handleKeydown"
                  class="w-full px-6 py-4 pr-16 text-lg border-2 border-gray-200 rounded-2xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  rows="3"
                  placeholder="Start typing..."
                  :disabled="isLoading"
                ></textarea>
                
                <!-- Input Icons -->
                <div class="absolute right-4 top-4 flex items-center space-x-2">
                  <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                    <Paperclip class="h-5 w-5" />
                  </button>
                  <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                    <Globe class="h-5 w-5" />
                  </button>
                  <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                    <Mic class="h-5 w-5" />
                  </button>
                  <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                    <Settings class="h-5 w-5" />
                  </button>
                  <button
                    @click="sendMessage"
                    :disabled="!inputMessage.trim() || isLoading"
                    class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    <Send class="h-5 w-5" />
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Suggested Actions -->
            <div class="mt-6 flex flex-wrap justify-center gap-3">
              <button
                v-for="suggestion in suggestedActions"
                :key="suggestion.label"
                @click="useSuggestion(suggestion.prompt)"
                class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
              >
                <component :is="suggestion.icon" class="h-4 w-4 text-gray-500" />
                <span class="text-sm text-gray-700">{{ suggestion.label }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ModelSwitcher from '@/Components/ModelSwitcher.vue'
import PersonaPicker from '@/Components/PersonaPicker.vue'
import {
  Plus,
  Grid3X3,
  MessageSquare,
  FolderOpen,
  Users,
  Lightbulb,
  Image,
  BarChart3,
  CreditCard,
  Settings,
  Download,
  Bell,
  HelpCircle,
  Paperclip,
  Globe,
  Mic,
  Send,
  Video,
  Gift
} from 'lucide-vue-next'

interface Props {
  user: any
  workspaces: any[]
  currentWorkspace: any
  availableModels: any[]
  personas: any[]
  recentChats: any[]
}

const props = defineProps<Props>()

const inputMessage = ref('')
const selectedModel = ref('auto')
const selectedPersonaId = ref<number | null>(null)
const isLoading = ref(false)

const currentRoute = computed(() => {
  return window.location.pathname
})

const navigationItems = [
  { name: 'Dashboard', icon: Grid3X3, route: '/dashboard' },
  { name: 'Chats', icon: MessageSquare, route: '/chats' },
  { name: 'Teams', icon: FolderOpen, route: '/teams' },
  { name: 'Personas', icon: Users, route: '/personas' },
  { name: 'Prompts', icon: Lightbulb, route: '/prompts' },
  { name: 'Images', icon: Image, route: '/images' },
  { name: 'Usage', icon: BarChart3, route: '/usage' },
  { name: 'Billing', icon: CreditCard, route: '/billing' },
  { name: 'Settings', icon: Settings, route: '/settings' },
  { name: 'Import', icon: Download, route: '/import' }
]

const suggestedActions = [
  {
    label: 'Summarize Video',
    icon: Video,
    prompt: 'Please summarize the key points from this video:'
  },
  {
    label: 'Brainstorm Ideas',
    icon: Lightbulb,
    prompt: 'Help me brainstorm creative ideas for:'
  },
  {
    label: 'Surprise me',
    icon: Gift,
    prompt: 'Give me a random interesting fact or creative prompt:'
  }
]

const createNewChat = () => {
  if (!props.currentWorkspace) {
    // Redirect to workspaces page if no workspace is available
    router.visit('/workspaces')
    return
  }
  
  router.post('/chats', {
    workspace_id: props.currentWorkspace.id,
    model: selectedModel.value,
    persona_id: selectedPersonaId.value
  })
}

const sendMessage = async () => {
  if (!inputMessage.value.trim() || isLoading.value) return
  
  // Create new chat if needed
  if (!props.recentChats.length) {
    await createNewChat()
  }
  
  // Send message to the most recent chat
  const chatId = props.recentChats[0]?.id
  if (chatId) {
    router.visit(`/chats/${chatId}`)
  }
}

const useSuggestion = (prompt: string) => {
  inputMessage.value = prompt
}

const updateModel = (model: string) => {
  selectedModel.value = model
}

const updatePersona = (personaId: number | null) => {
  selectedPersonaId.value = personaId
}

const navigateTo = (route: string) => {
  router.visit(route)
}

const handleKeydown = (event: KeyboardEvent) => {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault()
    sendMessage()
  }
}
</script>
