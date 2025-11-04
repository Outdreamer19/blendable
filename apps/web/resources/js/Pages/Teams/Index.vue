<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Teams
        </h2>
        <Link
          :href="route('teams.create')"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
        >
          Create Team
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Teams Grid -->
        <div v-if="teams.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="team in teams"
            :key="team.id"
            class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200"
          >
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-indigo-600 font-semibold text-lg">
                      {{ team.name.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ team.name }}</h3>
                    <p class="text-sm text-gray-500">{{ team.members_count }} members</p>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <span
                    v-if="team.role === 'admin'"
                    class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    Admin
                  </span>
                  <span
                    v-else-if="team.role === 'member'"
                    class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    Member
                  </span>
                </div>
              </div>
              
              <p v-if="team.description" class="text-gray-600 text-sm mb-4">
                {{ team.description }}
              </p>
              
              <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                  {{ team.workspaces_count }} workspaces
                </div>
                <div class="flex space-x-2">
                  <Link
                    :href="route('teams.show', team.id)"
                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                  >
                    View
                  </Link>
                  <Link
                    v-if="team.role === 'admin'"
                    :href="route('teams.edit', team.id)"
                    class="text-gray-600 hover:text-gray-900 text-sm font-medium"
                  >
                    Edit
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <Users class="h-12 w-12 text-gray-400" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No teams yet</h3>
          <p class="text-gray-500 mb-6">
            Create your first team to start collaborating with others.
          </p>
          <Link
            :href="route('teams.create')"
            class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
          >
            Create Team
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Users } from 'lucide-vue-next'

interface Team {
  id: number
  name: string
  description?: string
  role: string
  members_count: number
  workspaces_count: number
}

interface Props {
  teams: Team[]
}

defineProps<Props>()
</script>
