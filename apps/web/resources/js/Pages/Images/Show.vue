<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <Link :href="route('images.index')" class="text-gray-500 hover:text-gray-700 mr-4">
            <ArrowLeft class="h-5 w-5" />
          </Link>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Image Generation
          </h2>
        </div>
        <div class="flex space-x-3">
          <button
            v-if="image.status === 'completed'"
            @click="downloadImage"
            class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-150 ease-in-out"
          >
            Download
          </button>
          <button
            v-if="image.status === 'failed'"
            @click="regenerateImage"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
          >
            Regenerate
          </button>
          <button
            @click="deleteImage"
            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-150 ease-in-out"
          >
            Delete
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Image Display -->
          <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6">
                <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center mb-6">
                  <div v-if="image.status === 'completed' && image.image_url" class="w-full h-full">
                    <img
                      :src="image.image_url"
                      :alt="image.prompt"
                      class="w-full h-full object-cover rounded-lg"
                    />
                  </div>
                  <div v-else-if="image.status === 'processing'" class="text-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Generating Image...</h3>
                    <p class="text-gray-600">This may take a few moments</p>
                  </div>
                  <div v-else-if="image.status === 'failed'" class="text-center">
                    <div class="h-16 w-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                      <X class="h-8 w-8 text-red-600" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Generation Failed</h3>
                    <p class="text-red-600">{{ image.error_message || 'An error occurred during generation' }}</p>
                  </div>
                  <div v-else class="text-center">
                    <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                      <Clock class="h-8 w-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pending</h3>
                    <p class="text-gray-600">Your image is queued for generation</p>
                  </div>
                </div>

                <!-- Prompt -->
                <div class="mb-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-2">Prompt</h3>
                  <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ image.prompt }}</p>
                </div>

                <!-- Actions -->
                <div v-if="image.status === 'completed'" class="flex space-x-3">
                  <button
                    @click="downloadImage"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 ease-in-out"
                  >
                    Download Original
                  </button>
                  <button
                    @click="upscaleImage"
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-150 ease-in-out"
                  >
                    Upscale
                  </button>
                  <button
                    @click="regenerateImage"
                    class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-150 ease-in-out"
                  >
                    Generate Variation
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Image Details -->
          <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
              <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                <dl class="space-y-3">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd>
                      <span
                        :class="getStatusBadgeClass(image.status)"
                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      >
                        {{ image.status }}
                      </span>
                    </dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Model</dt>
                    <dd class="text-sm text-gray-900">{{ image.model }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Size</dt>
                    <dd class="text-sm text-gray-900">{{ image.size }}</dd>
                  </div>
                  <div v-if="image.quality">
                    <dt class="text-sm font-medium text-gray-500">Quality</dt>
                    <dd class="text-sm text-gray-900">{{ image.quality }}</dd>
                  </div>
                  <div v-if="image.style">
                    <dt class="text-sm font-medium text-gray-500">Style</dt>
                    <dd class="text-sm text-gray-900">{{ image.style }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="text-sm text-gray-900">{{ formatDate(image.created_at) }}</dd>
                  </div>
                  <div v-if="image.updated_at !== image.created_at">
                    <dt class="text-sm font-medium text-gray-500">Updated</dt>
                    <dd class="text-sm text-gray-900">{{ formatDate(image.updated_at) }}</dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Metadata -->
            <div v-if="image.metadata" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Metadata</h3>
                <pre class="text-xs text-gray-600 bg-gray-50 p-3 rounded-lg overflow-auto">{{ JSON.stringify(image.metadata, null, 2) }}</pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ArrowLeft, X, Clock } from 'lucide-vue-next'

interface ImageJob {
  id: number
  prompt: string
  model: string
  size: string
  quality?: string
  style?: string
  status: string
  image_url?: string
  error_message?: string
  metadata?: any
  created_at: string
  updated_at: string
}

interface Props {
  image: ImageJob
}

const props = defineProps<Props>()

const getStatusBadgeClass = (status: string) => {
  switch (status) {
    case 'completed':
      return 'bg-green-100 text-green-800'
    case 'processing':
      return 'bg-blue-100 text-blue-800'
    case 'failed':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const downloadImage = () => {
  if (props.image.image_url) {
    const link = document.createElement('a')
    link.href = props.image.image_url
    link.download = `image-${props.image.id}.png`
    link.click()
  }
}

const regenerateImage = () => {
  router.post(route('images.regenerate', props.image.id))
}

const upscaleImage = () => {
  router.post(route('images.upscale', props.image.id))
}

const deleteImage = () => {
  if (confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
    router.delete(route('images.destroy', props.image.id))
  }
}
</script>
