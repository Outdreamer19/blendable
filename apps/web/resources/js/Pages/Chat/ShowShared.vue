<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
      <div class="max-w-4xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-sm">O</span>
            </div>
            <div>
              <h1 class="text-lg font-semibold text-gray-900">{{ chat.title || 'Shared Chat' }}</h1>
              <p class="text-sm text-gray-500">Shared conversation</p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <span v-if="chat.persona" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
              {{ chat.persona.name }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
              {{ chat.model_key || 'Auto' }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Chat Messages -->
    <div class="max-w-4xl mx-auto px-4 py-6">
      <div class="space-y-6">
        <div
          v-for="message in chat.messages"
          :key="message.id"
          class="flex"
          :class="message.role === 'user' ? 'justify-end' : 'justify-start'"
        >
          <div
            class="max-w-3xl"
            :class="[
              message.role === 'user'
                ? 'bg-blue-600 text-white'
                : 'bg-white border border-gray-200',
              'rounded-lg px-4 py-3 shadow-sm'
            ]"
          >
            <div class="flex items-start space-x-3">
              <div
                v-if="message.role === 'assistant'"
                class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center"
              >
                <span class="text-white font-bold text-sm">O</span>
              </div>
              <div class="flex-1">
                <div class="text-sm font-medium text-gray-900 mb-1" v-if="message.role === 'assistant'">
                  Blendable
                </div>
                <div class="prose prose-sm max-w-none" v-html="formatMessage(message.content)"></div>
                <div class="mt-2 text-xs text-gray-500">
                  {{ formatDate(message.created_at) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="chat.messages.length === 0" class="text-center py-12">
        <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <MessageSquare class="h-12 w-12 text-gray-400" />
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No messages yet</h3>
        <p class="text-gray-500">This shared chat doesn't have any messages yet.</p>
      </div>
    </div>

    <!-- Footer -->
    <div class="bg-white border-t border-gray-200 mt-12">
      <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="text-center">
          <p class="text-sm text-gray-500">
            This chat was shared from <span class="font-medium text-gray-900">Blendable</span>
          </p>
          <p class="text-xs text-gray-400 mt-1">
            Create your own AI conversations at <a href="/" class="text-blue-600 hover:text-blue-500">blendable.com</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { MessageSquare } from 'lucide-vue-next'

interface Message {
  id: number
  role: 'user' | 'assistant' | 'system'
  content: string
  created_at: string
  model_key?: string
}

interface Persona {
  id: number
  name: string
  description: string
}

interface Chat {
  id: number
  title?: string
  model_key?: string
  persona?: Persona
  messages: Message[]
  created_at: string
  updated_at: string
}

interface Props {
  chat: Chat
}

const props = defineProps<Props>()

const formatMessage = (content: string): string => {
  // Convert markdown-like formatting to HTML
  return content
    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
    .replace(/\*(.*?)\*/g, '<em>$1</em>')
    .replace(/`(.*?)`/g, '<code class="bg-gray-100 px-1 py-0.5 rounded text-sm">$1</code>')
    .replace(/\n/g, '<br>')
}

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
