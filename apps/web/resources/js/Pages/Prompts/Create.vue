<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="currentWorkspace">
    <div class="max-w-4xl mx-auto py-8">
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h1 class="text-2xl font-bold text-gray-900">Create New Prompt</h1>
        </div>
        
        <form @submit.prevent="submit" class="p-6 space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              required
            />
          </div>
          
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              id="description"
              v-model="form.description"
              rows="3"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>
          </div>
          
          <div>
            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
            <textarea
              id="content"
              v-model="form.content"
              rows="10"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              required
            ></textarea>
          </div>
          
          <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags (comma-separated)</label>
            <input
              id="tags"
              v-model="form.tags"
              type="text"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          
          <div class="flex items-center">
            <input
              id="is_public"
              v-model="form.is_public"
              type="checkbox"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            />
            <label for="is_public" class="ml-2 block text-sm text-gray-900">
              Make this prompt public
            </label>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="$inertia.visit(route('prompts.index'))"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              Create Prompt
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

interface Props {
  user: any
  workspaces: any[]
  currentWorkspace: any
}

const props = defineProps<Props>()

const form = useForm({
  name: '',
  description: '',
  content: '',
  tags: '',
  is_public: false,
})

const submit = () => {
  form.post(route('prompts.store'))
}
</script>
