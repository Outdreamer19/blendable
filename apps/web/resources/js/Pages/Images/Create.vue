<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="workspace.id">
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center space-x-4">
            <Link :href="route('images.index')" class="text-gray-400 hover:text-gray-600">
              <ArrowLeft class="h-6 w-6" />
            </Link>
            <div>
              <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Generate AI Image
              </h2>
              <p class="text-sm text-gray-600 mt-1">
                Create stunning images using AI models like DALL-E and Stable Diffusion
              </p>
            </div>
          </div>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
          <!-- Prompt Section -->
          <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Image Prompt</h3>
            
            <div>
              <label for="prompt" class="block text-sm font-medium text-gray-700">
                Describe the image you want to generate *
              </label>
              <textarea
                id="prompt"
                v-model="form.prompt"
                rows="4"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="A beautiful sunset over a mountain landscape with a lake in the foreground..."
              />
              <p class="mt-1 text-sm text-gray-500">
                Be as descriptive as possible. Include details about style, colors, composition, and mood.
              </p>
              <div v-if="form.errors.prompt" class="mt-1 text-sm text-red-600">
                {{ form.errors.prompt }}
              </div>
            </div>
          </div>

          <!-- Model Selection -->
          <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">AI Model</h3>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
              <div
                v-for="model in availableModels"
                :key="model.value"
                @click="form.model = model.value"
                :class="[
                  form.model === model.value
                    ? 'ring-2 ring-indigo-500 border-indigo-500'
                    : 'border-gray-300 hover:border-gray-400',
                  'relative rounded-lg border p-4 cursor-pointer transition-colors'
                ]"
              >
                <div class="flex items-center space-x-3">
                  <input
                    :id="model.value"
                    v-model="form.model"
                    :value="model.value"
                    type="radio"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                  />
                  <div class="flex-1">
                    <label :for="model.value" class="text-sm font-medium text-gray-900 cursor-pointer">
                      {{ model.label }}
                    </label>
                    <p class="text-xs text-gray-500">{{ model.description }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="form.errors.model" class="mt-2 text-sm text-red-600">
              {{ form.errors.model }}
            </div>
          </div>

          <!-- Size and Quality Settings -->
          <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Image Settings</h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <!-- Size -->
              <div>
                <label for="size" class="block text-sm font-medium text-gray-700">
                  Image Size
                </label>
                <select
                  id="size"
                  v-model="form.size"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="1024x1024">Square (1024×1024)</option>
                  <option value="1024x1792">Portrait (1024×1792)</option>
                  <option value="1792x1024">Landscape (1792×1024)</option>
                  <option value="512x512">Small Square (512×512)</option>
                </select>
                <div v-if="form.errors.size" class="mt-1 text-sm text-red-600">
                  {{ form.errors.size }}
                </div>
              </div>

              <!-- Quality (DALL-E 3 only) -->
              <div v-if="form.model === 'dall-e-3'">
                <label for="quality" class="block text-sm font-medium text-gray-700">
                  Quality
                </label>
                <select
                  id="quality"
                  v-model="form.quality"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="standard">Standard</option>
                  <option value="hd">HD (Higher Quality)</option>
                </select>
                <div v-if="form.errors.quality" class="mt-1 text-sm text-red-600">
                  {{ form.errors.quality }}
                </div>
              </div>

              <!-- Style (DALL-E 3 only) -->
              <div v-if="form.model === 'dall-e-3'">
                <label for="style" class="block text-sm font-medium text-gray-700">
                  Style
                </label>
                <select
                  id="style"
                  v-model="form.style"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="vivid">Vivid (More Creative)</option>
                  <option value="natural">Natural (More Realistic)</option>
                </select>
                <div v-if="form.errors.style" class="mt-1 text-sm text-red-600">
                  {{ form.errors.style }}
                </div>
              </div>
            </div>
          </div>

          <!-- Preview Section -->
          <div v-if="form.prompt" class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-2"><strong>Prompt:</strong> {{ form.prompt }}</p>
              <p class="text-sm text-gray-600 mb-2"><strong>Model:</strong> {{ getModelLabel(form.model) }}</p>
              <p class="text-sm text-gray-600 mb-2"><strong>Size:</strong> {{ form.size }}</p>
              <p v-if="form.model === 'dall-e-3'" class="text-sm text-gray-600 mb-2">
                <strong>Quality:</strong> {{ form.quality }} | <strong>Style:</strong> {{ form.style }}
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-3">
            <Link
              :href="route('images.index')"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing || !form.prompt"
              class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ form.processing ? 'Generating...' : 'Generate Image' }}
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
import { ArrowLeft } from 'lucide-vue-next'

interface Model {
  value: string
  label: string
  description: string
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
  availableModels: Model[]
  workspace: Workspace
  user: User
  workspaces: Workspace[]
}

const props = defineProps<Props>()

const form = useForm({
  prompt: '',
  model: 'dall-e-3',
  size: '1024x1024',
  quality: 'standard',
  style: 'vivid',
})

const getModelLabel = (value: string): string => {
  const model = props.availableModels.find(m => m.value === value)
  return model ? model.label : value
}

const submit = () => {
  form.post(route('images.store'))
}
</script>