<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Prompts
        </h2>
        <Link
          :href="route('prompts.create')"
          class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
        >
          Create Prompt
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
          <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search prompts..."
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
            </div>
            <select
              v-model="selectedFolder"
              class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
              <option value="">All Folders</option>
              <option
                v-for="folder in folders"
                :key="folder.id"
                :value="folder.id"
              >
                {{ folder.name }}
              </option>
            </select>
            <select
              v-model="sortBy"
              class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
              <option value="name">Sort by Name</option>
              <option value="usage_count">Sort by Usage</option>
              <option value="created_at">Sort by Date</option>
            </select>
          </div>
        </div>

        <!-- Prompts Grid -->
        <div v-if="filteredPrompts.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="prompt in filteredPrompts"
            :key="prompt.id"
            class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-200"
          >
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-indigo-600 font-semibold text-lg">💡</span>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ prompt.name }}</h3>
                    <p class="text-sm text-gray-500">{{ prompt.folder_name || 'No folder' }}</p>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <span
                    v-if="prompt.is_favorite"
                    class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    ⭐ Favorite
                  </span>
                  <span
                    v-if="prompt.is_public"
                    class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded"
                  >
                    Public
                  </span>
                </div>
              </div>
              
              <p v-if="prompt.description" class="text-gray-600 text-sm mb-4">
                {{ prompt.description }}
              </p>
              
              <div class="text-sm text-gray-500 mb-4">
                {{ prompt.content_preview }}
              </div>
              
              <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                  Used {{ prompt.usage_count }} times
                </div>
                <div class="flex space-x-2">
                  <button
                    @click="usePrompt(prompt.id)"
                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                  >
                    Use
                  </button>
                  <Link
                    :href="route('prompts.show', prompt.id)"
                    class="text-gray-600 hover:text-gray-900 text-sm font-medium"
                  >
                    View
                  </Link>
                  <Link
                    :href="route('prompts.edit', prompt.id)"
                    class="text-gray-600 hover:text-gray-900 text-sm font-medium"
                  >
                    Edit
                  </Link>
                  <button
                    @click="deletePrompt(prompt.id)"
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
            <Lightbulb class="h-12 w-12 text-gray-400" />
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No prompts found</h3>
          <p class="text-gray-500 mb-6">
            Create your first prompt to get started with reusable AI instructions.
          </p>
          <Link
            :href="route('prompts.create')"
            class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
          >
            Create Prompt
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Lightbulb } from 'lucide-vue-next'

interface Prompt {
  id: number
  name: string
  description?: string
  content_preview: string
  usage_count: number
  is_favorite: boolean
  is_public: boolean
  folder_name?: string
}

interface Folder {
  id: number
  name: string
}

interface Props {
  prompts: Prompt[]
  folders: Folder[]
}

const props = defineProps<Props>()

const searchQuery = ref('')
const selectedFolder = ref('')
const sortBy = ref('name')

const filteredPrompts = computed(() => {
  // Ensure prompts is an array
  let filtered = Array.isArray(props.prompts) ? props.prompts : []

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(prompt =>
      prompt.name.toLowerCase().includes(query) ||
      prompt.description?.toLowerCase().includes(query) ||
      prompt.content_preview.toLowerCase().includes(query)
    )
  }

  // Filter by folder
  if (selectedFolder.value) {
    filtered = filtered.filter(prompt => prompt.folder_name === selectedFolder.value)
  }

  // Sort
  filtered.sort((a, b) => {
    switch (sortBy.value) {
      case 'usage_count':
        return b.usage_count - a.usage_count
      case 'created_at':
        return new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
      default:
        return a.name.localeCompare(b.name)
    }
  })

  return filtered
})

const usePrompt = (promptId: number) => {
  router.post(route('prompts.use', promptId))
}

const deletePrompt = (promptId: number) => {
  if (confirm('Are you sure you want to delete this prompt? This action cannot be undone.')) {
    router.delete(route('prompts.destroy', promptId))
  }
}
</script>
