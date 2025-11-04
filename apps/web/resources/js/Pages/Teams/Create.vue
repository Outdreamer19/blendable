<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center">
        <Link :href="route('teams.index')" class="text-gray-500 hover:text-gray-700 mr-4">
          <ArrowLeft class="h-5 w-5" />
        </Link>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Create Team
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submit">
              <div class="space-y-6">
                <!-- Team Name -->
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700">
                    Team Name
                  </label>
                  <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.name }"
                    required
                  />
                  <div v-if="errors.name" class="mt-1 text-sm text-red-600">
                    {{ errors.name }}
                  </div>
                </div>

                <!-- Description -->
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">
                    Description
                  </label>
                  <textarea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.description }"
                    placeholder="Describe your team's purpose and goals..."
                  />
                  <div v-if="errors.description" class="mt-1 text-sm text-red-600">
                    {{ errors.description }}
                  </div>
                </div>

                <!-- Avatar URL -->
                <div>
                  <label for="avatar_url" class="block text-sm font-medium text-gray-700">
                    Avatar URL (Optional)
                  </label>
                  <input
                    id="avatar_url"
                    v-model="form.avatar_url"
                    type="url"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.avatar_url }"
                    placeholder="https://example.com/avatar.jpg"
                  />
                  <div v-if="errors.avatar_url" class="mt-1 text-sm text-red-600">
                    {{ errors.avatar_url }}
                  </div>
                </div>

                <!-- Settings -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-3">
                    Team Settings
                  </label>
                  <div class="space-y-3">
                    <div class="flex items-center">
                      <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                      />
                      <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Team is active
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="mt-8 flex justify-end space-x-3">
                <Link
                  :href="route('teams.index')"
                  class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="processing"
                  class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                >
                  {{ processing ? 'Creating...' : 'Create Team' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ArrowLeft } from 'lucide-vue-next'

interface Errors {
  name?: string
  description?: string
  avatar_url?: string
  is_active?: string
}

interface Props {
  errors: Errors
}

const props = defineProps<Props>()

const form = useForm({
  name: '',
  description: '',
  avatar_url: '',
  is_active: true,
})

const processing = ref(false)

const submit = () => {
  processing.value = true
  form.post(route('teams.store'), {
    onFinish: () => {
      processing.value = false
    },
  })
}
</script>
