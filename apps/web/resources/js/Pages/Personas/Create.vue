<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="currentWorkspace">
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center space-x-4">
            <Link :href="route('personas.index')" class="text-gray-400 hover:text-gray-600">
              <ArrowLeft class="h-6 w-6" />
            </Link>
            <div>
              <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Persona
              </h2>
              <p class="text-sm text-gray-600 mt-1">
                Create a custom AI assistant with specific knowledge and capabilities
              </p>
            </div>
          </div>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
          <!-- Basic Information -->
          <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                  Name *
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="e.g., Marketing Assistant"
                />
                <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                  {{ form.errors.name }}
                </div>
              </div>

              <div>
                <label for="avatar_url" class="block text-sm font-medium text-gray-700">
                  Avatar URL
                </label>
                <input
                  id="avatar_url"
                  v-model="form.avatar_url"
                  type="url"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="https://example.com/avatar.png"
                />
                <div v-if="form.errors.avatar_url" class="mt-1 text-sm text-red-600">
                  {{ form.errors.avatar_url }}
                </div>
              </div>
            </div>

            <div class="mt-6">
              <label for="description" class="block text-sm font-medium text-gray-700">
                Description
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="3"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Brief description of this persona's role and capabilities"
              />
              <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                {{ form.errors.description }}
              </div>
            </div>

            <div class="mt-6">
              <label for="system_prompt" class="block text-sm font-medium text-gray-700">
                System Prompt
              </label>
              <textarea
                id="system_prompt"
                v-model="form.system_prompt"
                rows="6"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="You are a helpful marketing assistant. Your role is to..."
              />
              <p class="mt-1 text-sm text-gray-500">
                Define how this persona should behave and respond. This will be used as the system prompt for all conversations.
              </p>
              <div v-if="form.errors.system_prompt" class="mt-1 text-sm text-red-600">
                {{ form.errors.system_prompt }}
              </div>
            </div>

            <div class="mt-6 flex items-center">
              <input
                id="is_public"
                v-model="form.is_public"
                type="checkbox"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="is_public" class="ml-2 block text-sm text-gray-900">
                Make this persona public (visible to other workspace members)
              </label>
            </div>
          </div>

          <!-- Knowledge Base -->
          <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Knowledge Base</h3>
            <p class="text-sm text-gray-600 mb-4">
              Attach knowledge items to give this persona access to specific information.
            </p>
            
            <div v-if="availableKnowledge.length > 0" class="space-y-3">
              <div
                v-for="knowledge in availableKnowledge"
                :key="knowledge.id"
                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
              >
                <div class="flex items-center space-x-3">
                  <input
                    :id="`knowledge-${knowledge.id}`"
                    v-model="form.knowledge_ids"
                    :value="knowledge.id"
                    type="checkbox"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <div>
                    <label :for="`knowledge-${knowledge.id}`" class="text-sm font-medium text-gray-900">
                      {{ knowledge.name }}
                    </label>
                    <p class="text-xs text-gray-500">{{ knowledge.description }}</p>
                  </div>
                </div>
                <span class="text-xs text-gray-400">{{ knowledge.type }}</span>
              </div>
            </div>
            
            <div v-else class="text-center py-6">
              <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <BookOpen class="h-6 w-6 text-gray-400" />
              </div>
              <p class="text-sm text-gray-500">No knowledge items available</p>
              <p class="text-xs text-gray-400 mt-1">Create knowledge items to attach to personas</p>
            </div>
          </div>

          <!-- Actions -->
          <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions & Tools</h3>
            <p class="text-sm text-gray-600 mb-4">
              Enable specific actions and tools for this persona.
            </p>
            
            <div v-if="availableActions.length > 0" class="space-y-3">
              <div
                v-for="action in availableActions"
                :key="action.id"
                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
              >
                <div class="flex items-center space-x-3">
                  <input
                    :id="`action-${action.id}`"
                    v-model="form.action_ids"
                    :value="action.id"
                    type="checkbox"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <div>
                    <label :for="`action-${action.id}`" class="text-sm font-medium text-gray-900">
                      {{ action.name }}
                    </label>
                    <p class="text-xs text-gray-500">{{ action.description }}</p>
                  </div>
                </div>
                <span class="text-xs text-gray-400">{{ action.type }}</span>
              </div>
            </div>
            
            <div v-else class="text-center py-6">
              <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <Wrench class="h-6 w-6 text-gray-400" />
              </div>
              <p class="text-sm text-gray-500">No actions available</p>
              <p class="text-xs text-gray-400 mt-1">Actions will be available once configured</p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-3">
            <Link
              :href="route('personas.index')"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ form.processing ? 'Creating...' : 'Create Persona' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ArrowLeft, BookOpen, Wrench } from 'lucide-vue-next'

interface Knowledge {
  id: number
  name: string
  description: string
  type: string
}

interface Action {
  id: number
  name: string
  description: string
  type: string
}

interface Workspace {
  id: number
  name: string
}

interface User {
  id: number
  name: string
  email: string
}

interface Props {
  availableKnowledge: Knowledge[]
  availableActions: Action[]
  currentWorkspace: Workspace
  user: User
  workspaces: Workspace[]
}

const props = defineProps<Props>()

const form = useForm({
  name: '',
  description: '',
  system_prompt: '',
  avatar_url: '',
  is_public: false,
  knowledge_ids: [] as number[],
  action_ids: [] as number[],
})

const submit = () => {
  form.post(route('personas.store'))
}
</script>