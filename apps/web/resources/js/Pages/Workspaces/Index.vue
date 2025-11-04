<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Workspaces
        </h2>
        <Link
          :href="route('workspaces.create')"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
        >
          Create Workspace
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Workspaces Grid -->
        <div v-if="workspaces.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="workspace in workspaces"
            :key="workspace.id"
            class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200"
          >
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-indigo-600 font-semibold text-lg">
                      {{ workspace.name.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ workspace.name }}</h3>
                    <p class="text-sm text-gray-500">{{ workspace.members_count }} members</p>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <span
                    v-if="workspace.role === 'admin'"
                    class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    Admin
                  </span>
                  <span
                    v-else-if="workspace.role === 'member'"
                    class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    Member
                  </span>
                  <span
                    v-else
                    class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    Viewer
                  </span>
                </div>
              </div>
              
              <p v-if="workspace.description" class="text-gray-600 text-sm mb-4">
                {{ workspace.description }}
              </p>
              
              <div class="flex items-center justify-between mb-4">
                <div class="text-sm text-gray-500">
                  {{ workspace.chats_count }} chats
                </div>
                <div class="text-sm text-gray-500">
                  Team: {{ workspace.team_name }}
                </div>
              </div>
              
              <div class="flex space-x-2">
                <Link
                  :href="route('workspaces.show', workspace.id)"
                  class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                >
                  View
                </Link>
                <Link
                  v-if="workspace.role === 'admin'"
                  :href="route('workspaces.edit', workspace.id)"
                  class="text-gray-600 hover:text-gray-900 text-sm font-medium"
                >
                  Edit
                </Link>
                <button
                  v-if="workspace.role === 'admin'"
                  @click="deleteWorkspace(workspace.id)"
                  class="text-red-600 hover:text-red-900 text-sm font-medium"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <Folder class="h-12 w-12 text-gray-400" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No workspaces yet</h3>
          <p class="text-gray-500 mb-6">
            Create your first workspace to start organizing your AI projects and collaborations.
          </p>
          <Link
            :href="route('workspaces.create')"
            class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
          >
            Create Workspace
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Folder } from 'lucide-vue-next'

interface Workspace {
  id: number
  name: string
  description?: string
  role: string
  members_count: number
  chats_count: number
  team_name: string
}

interface Props {
  workspaces: Workspace[]
}

const props = defineProps<Props>()

const deleteWorkspace = (workspaceId: number) => {
  if (confirm('Are you sure you want to delete this workspace? This action cannot be undone.')) {
    router.delete(route('workspaces.destroy', workspaceId))
  }
}
</script>
