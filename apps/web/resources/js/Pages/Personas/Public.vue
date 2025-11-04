<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-6">
          <h1 class="text-3xl font-bold text-gray-900">Expert Personas</h1>
          <p class="mt-2 text-lg text-gray-600">
            Chat with AI assistants trained on real experts' knowledge, frameworks, and methodologies.
          </p>
        </div>
      </div>
    </div>

    <!-- Personas Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="personas.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="persona in personas"
          :key="persona.id"
          class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200"
        >
          <div class="p-6">
            <div class="flex items-center mb-4">
              <div class="h-16 w-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-xl">
                  {{ getPersonaIcon(persona.name) }}
                </span>
              </div>
              <div class="ml-4">
                <h3 class="text-xl font-semibold text-gray-900">{{ persona.name }}</h3>
                <p class="text-sm text-gray-500">{{ persona.knowledge_count }} knowledge items</p>
              </div>
            </div>
            
            <p class="text-gray-600 mb-4">{{ persona.description }}</p>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4 text-sm text-gray-500">
                <span>{{ persona.actions_count }} tools</span>
                <span>•</span>
                <span>Expert persona</span>
              </div>
              
              <Link
                :href="route('public.personas.show', persona.id)"
                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
              >
                Learn More
              </Link>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="text-center py-12">
        <div class="text-gray-400 text-6xl mb-4">👤</div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Expert Personas Available</h3>
        <p class="text-gray-500">Check back soon for expert personas you can chat with.</p>
      </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-bold text-gray-900 mb-8">Why Use Expert Personas?</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
              <div class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <span class="text-indigo-600 text-2xl">🎯</span>
              </div>
              <h3 class="text-lg font-semibold text-gray-900 mb-2">Specialized Expertise</h3>
              <p class="text-gray-600">Get advice from domain experts with years of experience and proven frameworks.</p>
            </div>
            
            <div class="text-center">
              <div class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <span class="text-indigo-600 text-2xl">📚</span>
              </div>
              <h3 class="text-lg font-semibold text-gray-900 mb-2">Trained on Real Content</h3>
              <p class="text-gray-600">Each persona is trained on books, courses, and real-world examples from the expert.</p>
            </div>
            
            <div class="text-center">
              <div class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <span class="text-indigo-600 text-2xl">⚡</span>
              </div>
              <h3 class="text-lg font-semibold text-gray-900 mb-2">Instant Access</h3>
              <p class="text-gray-600">Get expert-level advice instantly without scheduling calls or paying for consultations.</p>
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
  knowledge_count: number
  actions_count: number
}

interface Props {
  personas: Persona[]
}

const props = defineProps<Props>()

const getPersonaIcon = (name: string): string => {
  const icons: Record<string, string> = {
    'Alex Hormozi': '💼',
    'Gary Vaynerchuk': '🚀',
    'Tim Ferriss': '⚡',
    'Naval Ravikant': '🧠',
    'Seth Godin': '📈',
    'Ryan Holiday': '📚',
    'James Clear': '🎯',
    'Paul Graham': '💻',
    'Elon Musk': '🚀',
    'Steve Jobs': '🍎',
  }
  
  return icons[name] || '👤'
}
</script>
