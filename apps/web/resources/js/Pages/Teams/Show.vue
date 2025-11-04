<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <Link :href="route('teams.index')" class="text-gray-500 hover:text-gray-700 mr-4">
            <ArrowLeft class="h-5 w-5" />
          </Link>
          <div class="flex items-center">
            <div class="h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
              <span class="text-indigo-600 font-semibold text-lg">
                {{ team.name.charAt(0).toUpperCase() }}
              </span>
            </div>
            <div>
              <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ team.name }}
              </h2>
              <p v-if="team.description" class="text-sm text-gray-600">
                {{ team.description }}
              </p>
            </div>
          </div>
        </div>
        <div class="flex space-x-3">
          <Link
            v-if="canEdit"
            :href="route('teams.edit', team.id)"
            class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-150 ease-in-out"
          >
            Edit Team
          </Link>
          <Link
            v-if="canEdit"
            :href="route('teams.invite', team.id)"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
          >
            Invite Members
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Team Info -->
          <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
              <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Team Overview</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Members</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ team.members_count }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Workspaces</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ team.workspaces_count }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ formatDate(team.created_at) }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                      <span
                        :class="team.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      >
                        {{ team.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </dd>
                  </div>
                </div>
              </div>
            </div>

            <!-- Workspaces -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-medium text-gray-900">Workspaces</h3>
                  <Link
                    v-if="canEdit"
                    :href="route('workspaces.create', { team_id: team.id })"
                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                  >
                    Create Workspace
                  </Link>
                </div>
                <div v-if="workspaces.length > 0" class="space-y-3">
                  <div
                    v-for="workspace in workspaces"
                    :key="workspace.id"
                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
                  >
                    <div class="flex items-center">
                      <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-gray-600 font-semibold text-sm">
                          {{ workspace.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ workspace.name }}</div>
                        <div class="text-sm text-gray-500">{{ workspace.members_count }} members</div>
                      </div>
                    </div>
                    <Link
                      :href="route('workspaces.show', workspace.id)"
                      class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                    >
                      View
                    </Link>
                  </div>
                </div>
                <div v-else class="text-center py-6">
                  <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <Folder class="h-6 w-6 text-gray-400" />
                  </div>
                  <p class="text-gray-500 text-sm">No workspaces yet</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Members -->
          <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-medium text-gray-900">Members</h3>
                  <span class="text-sm text-gray-500">{{ team.members_count }} total</span>
                </div>
                <div class="space-y-3">
                  <div
                    v-for="member in members"
                    :key="member.id"
                    class="flex items-center justify-between"
                  >
                    <div class="flex items-center">
                      <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-gray-600 font-semibold text-sm">
                          {{ member.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ member.name }}</div>
                        <div class="text-sm text-gray-500">{{ member.email }}</div>
                      </div>
                    </div>
                    <div class="flex items-center space-x-2">
                      <span
                        :class="getRoleBadgeClass(member.role)"
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      >
                        {{ member.role }}
                      </span>
                      <button
                        v-if="canEdit && member.id !== currentUserId"
                        @click="removeMember(member.id)"
                        class="text-red-600 hover:text-red-900 text-sm"
                      >
                        Remove
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ArrowLeft, Folder } from 'lucide-vue-next'

interface Member {
  id: number
  name: string
  email: string
  role: string
}

interface Workspace {
  id: number
  name: string
  members_count: number
}

interface Team {
  id: number
  name: string
  description?: string
  is_active: boolean
  members_count: number
  workspaces_count: number
  created_at: string
}

interface Props {
  team: Team
  members: Member[]
  workspaces: Workspace[]
  canEdit: boolean
  currentUserId: number
}

const props = defineProps<Props>()

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const getRoleBadgeClass = (role: string) => {
  switch (role) {
    case 'admin':
      return 'bg-purple-100 text-purple-800'
    case 'member':
      return 'bg-green-100 text-green-800'
    case 'viewer':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const removeMember = (memberId: number) => {
  if (confirm('Are you sure you want to remove this member from the team?')) {
    router.delete(route('teams.remove-member', { team: props.team.id, member: memberId }))
  }
}
</script>
