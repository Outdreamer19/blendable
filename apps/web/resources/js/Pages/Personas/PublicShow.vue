<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-6">
          <div class="flex items-center gap-4">
            <Link 
              href="/experts" 
              class="text-indigo-600 hover:text-indigo-500 flex items-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
              Back to Experts
            </Link>
          </div>
          <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ persona.name }}</h1>
          <p class="mt-2 text-lg text-gray-600">{{ persona.description }}</p>
        </div>
      </div>
    </div>

    <!-- Persona Details -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
          <!-- About Section -->
          <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">About {{ persona.name }}</h2>
            <div class="prose max-w-none">
              <p class="text-gray-600 leading-relaxed">
                {{ persona.description }}
              </p>
            </div>
          </div>

          <!-- Knowledge Base -->
          <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Knowledge Base</h2>
            <div v-if="persona.knowledge && persona.knowledge.length > 0" class="space-y-4">
              <div 
                v-for="knowledge in persona.knowledge" 
                :key="knowledge.id"
                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
              >
                <h3 class="font-semibold text-gray-900 mb-2">{{ knowledge.name }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ knowledge.description }}</p>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                  <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded">{{ knowledge.type }}</span>
                  <span v-if="knowledge.author" class="bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ knowledge.author }}</span>
                  <span v-if="knowledge.category" class="bg-green-100 text-green-800 px-2 py-1 rounded">{{ knowledge.category }}</span>
                </div>
              </div>
            </div>
            <div v-else class="text-gray-500 text-center py-8">
              No knowledge items available
            </div>
          </div>

          <!-- Available Actions -->
          <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Available Actions</h2>
            <div v-if="persona.actions && persona.actions.length > 0" class="space-y-4">
              <div 
                v-for="action in persona.actions" 
                :key="action.id"
                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
              >
                <h3 class="font-semibold text-gray-900 mb-2">{{ action.name }}</h3>
                <p class="text-gray-600 text-sm">{{ action.description }}</p>
                <div class="mt-2">
                  <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ action.type }}</span>
                </div>
              </div>
            </div>
            <div v-else class="text-gray-500 text-center py-8">
              No actions available
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <!-- Chat with Expert -->
          <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Chat with {{ persona.name }}</h3>
            <p class="text-gray-600 text-sm mb-4">
              Start a conversation with this expert to get personalized advice and insights.
            </p>
            <Link
              :href="route('chats.start', { persona: persona.id })"
              class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out text-center block"
            >
              Start Chat
            </Link>
          </div>

          <!-- Expert Stats -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Expert Stats</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600">Knowledge Items</span>
                <span class="font-semibold">{{ persona.knowledge_count || 0 }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Available Actions</span>
                <span class="font-semibold">{{ persona.actions_count || 0 }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Expertise Level</span>
                <span class="font-semibold text-indigo-600">Expert</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Persona {
  id: number
  name: string
  description: string
  avatar_url?: string
  knowledge?: Array<{
    id: number
    name: string
    description: string
    type: string
    author?: string
    category?: string
  }>
  actions?: Array<{
    id: number
    name: string
    description: string
    type: string
  }>
  knowledge_count?: number
  actions_count?: number
}

interface Props {
  persona: Persona
}

defineProps<Props>()
</script>
