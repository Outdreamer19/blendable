<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="currentWorkspace">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Personas
          </h2>
          <Link
            :href="route('personas.create')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
          >
            Create Persona
          </Link>
        </div>
        
        <!-- Personas Grid -->
        <div v-if="personas.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="persona in personas"
            :key="persona.id"
            class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200"
          >
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-indigo-600 font-semibold text-lg">
                      {{ getPersonaIcon(persona.name) }}
                    </span>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ persona.name }}</h3>
                    <p class="text-sm text-gray-500">{{ persona.knowledge_count }} knowledge items</p>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <span
                    v-if="persona.is_active"
                    class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    Active
                  </span>
                  <span
                    v-else
                    class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    Inactive
                  </span>
                </div>
              </div>
              
              <p v-if="persona.description" class="text-gray-600 text-sm mb-4">
                {{ persona.description }}
              </p>
              
              <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                  {{ persona.actions_count }} actions
                </div>
                <div class="flex space-x-2">
                  <Link
                    :href="route('personas.show', persona.id)"
                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                  >
                    View
                  </Link>
                  <Link
                    :href="route('personas.edit', persona.id)"
                    class="text-gray-600 hover:text-gray-900 text-sm font-medium"
                  >
                    Edit
                  </Link>
                  <button
                    @click="deletePersona(persona.id)"
                    class="text-red-600 hover:text-red-900 text-sm font-medium"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <User class="h-12 w-12 text-gray-400" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No personas yet</h3>
          <p class="text-gray-500 mb-6">
            Create your first persona to customize AI behavior for specific use cases.
          </p>
          <Link
            :href="route('personas.create')"
            class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
          >
            Create Persona
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { User } from 'lucide-vue-next'

interface Persona {
  id: number
  name: string
  description?: string
  is_active: boolean
  knowledge_count: number
  actions_count: number
}

interface Props {
  personas: Persona[]
  user: any
  workspaces: any[]
  currentWorkspace: any
}

const props = defineProps<Props>()

const getPersonaIcon = (name: string) => {
  const icons: Record<string, string> = {
    'Developer': '👨‍💻',
    'Writer': '✍️',
    'Designer': '🎨',
    'Analyst': '📊',
    'Teacher': '👨‍🏫',
    'Researcher': '🔬',
    'Creative': '💡',
    'Technical': '⚙️',
    'Business': '💼',
    'Marketing': '📈',
  }

  // Try to match by name or return a default
  for (const [key, icon] of Object.entries(icons)) {
    if (name.toLowerCase().includes(key.toLowerCase())) {
      return icon
    }
  }

  return '👤'
}

const deletePersona = (personaId: number) => {
  if (confirm('Are you sure you want to delete this persona? This action cannot be undone.')) {
    router.delete(route('personas.destroy', personaId))
  }
}
</script>
