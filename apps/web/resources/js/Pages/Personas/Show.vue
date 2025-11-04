<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="currentWorkspace">
    <div class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ persona.name }}</h1>
            <p class="mt-1 text-gray-600">{{ persona.description }}</p>
          </div>
          <Link :href="route('personas.index')" class="text-sm text-indigo-600 hover:text-indigo-700">Back to Personas</Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Main -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Knowledge -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Knowledge</h2>
              <div v-if="(persona.knowledge || []).length" class="space-y-3">
                <div v-for="k in persona.knowledge" :key="k.id" class="border rounded p-3">
                  <div class="flex items-center justify-between">
                    <div class="font-medium text-gray-900">{{ k.name }}</div>
                    <div class="text-xs text-gray-500">{{ k.type }}</div>
                  </div>
                  <p v-if="k.description" class="text-sm text-gray-600 mt-1">{{ k.description }}</p>
                </div>
              </div>
              <div v-else class="text-sm text-gray-500">No knowledge attached.</div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
              <div v-if="(persona.actions || []).length" class="space-y-3">
                <div v-for="a in persona.actions" :key="a.id" class="border rounded p-3">
                  <div class="font-medium text-gray-900">{{ a.name }}</div>
                  <p class="text-sm text-gray-600 mt-1">{{ a.description }}</p>
                </div>
              </div>
              <div v-else class="text-sm text-gray-500">No actions available.</div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
              <h3 class="text-sm font-semibold text-gray-900 mb-2">Start a chat</h3>
              <p class="text-sm text-gray-600 mb-4">Chat with this persona in your workspace.</p>
              <Link :href="route('chats.start', { persona: persona.id })" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 text-sm">New Chat</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
  
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

interface Persona {
  id: number
  name: string
  description?: string
  knowledge?: Array<{ id: number; name: string; description?: string; type: string }>
  actions?: Array<{ id: number; name: string; description?: string }>
}

interface Workspace { id: number; name: string; slug: string }
interface User { id: number; name: string; email: string }

interface Props {
  persona: Persona
  user: User
  workspaces: Workspace[]
  currentWorkspace?: Workspace
}

defineProps<Props>()
</script>


