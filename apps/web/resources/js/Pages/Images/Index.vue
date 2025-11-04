<template>
  <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="workspace.id">
    <div class="flex h-screen bg-gray-50">
      <!-- Left Sidebar -->
      <div class="w-16 bg-white border-r border-gray-200 flex flex-col items-center py-4 space-y-4">
        <!-- Logo -->
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
          <span class="text-white font-bold text-sm">O</span>
        </div>
        
        <!-- Quick Action Button -->
        <button
          @click="createNewImage"
          class="w-10 h-10 bg-purple-600 text-white rounded-full flex items-center justify-center hover:bg-purple-700 transition-colors"
        >
          <Plus class="h-5 w-5" />
        </button>
        
        <!-- Navigation Icons -->
        <nav class="flex flex-col space-y-2">
          <button
            v-for="item in navigationItems"
            :key="item.name"
            @click="navigateTo(item.route)"
            :class="[
              'w-10 h-10 rounded-lg flex items-center justify-center transition-colors',
              currentRoute === item.route
                ? 'bg-purple-100 text-purple-600'
                : 'text-gray-400 hover:text-gray-600 hover:bg-gray-100'
            ]"
            :title="item.name"
          >
            <component :is="item.icon" class="h-5 w-5" />
          </button>
        </nav>
        
        <!-- User Profile -->
        <div class="mt-auto">
          <div class="w-8 h-8 rounded-full border-2 border-gray-200 bg-purple-600 flex items-center justify-center">
            <span class="text-white text-sm font-medium">{{ user.name?.charAt(0) || 'U' }}</span>
          </div>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
          <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Images</h1>
            
            <div class="flex items-center space-x-4">
              <!-- Search -->
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Q Search Images..."
                  class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
                <Search class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
              </div>
              
              <!-- Filters -->
              <div class="flex items-center space-x-2">
                <select
                  v-model="selectedType"
                  class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
                  <option value="">Type</option>
                  <option value="generated">Generated</option>
                  <option value="uploaded">Uploaded</option>
                </select>
                
                <button
                  :class="[
                    'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                    selectedFilter === 'all'
                      ? 'bg-purple-100 text-purple-700'
                      : 'text-gray-600 hover:bg-gray-100'
                  ]"
                  @click="selectedFilter = 'all'"
                >
                  All
                </button>
                
                <button
                  :class="[
                    'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                    selectedFilter === 'favorites'
                      ? 'bg-purple-100 text-purple-700'
                      : 'text-gray-600 hover:bg-gray-100'
                  ]"
                  @click="selectedFilter = 'favorites'"
                >
                  Favorites
                </button>
                
                <button
                  :class="[
                    'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                    selectedFilter === 'bulk'
                      ? 'bg-purple-100 text-purple-700'
                      : 'text-gray-600 hover:bg-gray-100'
                  ]"
                  @click="selectedFilter = 'bulk'"
                >
                  Bulk
                </button>
                
                <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                  <Filter class="h-5 w-5" />
                </button>
              </div>
              
              <!-- New Image Button -->
              <Link
                :href="route('images.create')"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
              >
                <Plus class="h-4 w-4 mr-2" />
                New Image
              </Link>
            </div>
          </div>
        </div>

        <!-- Images Grid -->
        <div class="flex-1 p-6 overflow-y-auto">
          <div v-if="filteredImages.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <div
              v-for="image in filteredImages"
              :key="image.id"
              class="group relative aspect-square bg-gray-100 rounded-lg overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
              @click="viewImage(image)"
            >
              <div v-if="image.status === 'completed' && image.image_url" class="w-full h-full">
                <img
                  :src="image.image_url"
                  :alt="image.prompt"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                />
              </div>
              <div v-else-if="image.status === 'processing'" class="w-full h-full flex items-center justify-center">
                <div class="text-center">
                  <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600 mx-auto mb-2"></div>
                  <p class="text-xs text-gray-600">Processing...</p>
                </div>
              </div>
              <div v-else-if="image.status === 'failed'" class="w-full h-full flex items-center justify-center">
                <div class="text-center">
                  <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <X class="h-4 w-4 text-red-600" />
                  </div>
                  <p class="text-xs text-red-600">Failed</p>
                </div>
              </div>
              <div v-else class="w-full h-full flex items-center justify-center">
                <div class="text-center">
                  <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <Clock class="h-4 w-4 text-gray-400" />
                  </div>
                  <p class="text-xs text-gray-600">Pending</p>
                </div>
              </div>
              
              <!-- Overlay on hover -->
              <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                <div class="flex space-x-2">
                  <button
                    v-if="image.status === 'completed'"
                    @click.stop="downloadImage(image)"
                    class="p-2 bg-white rounded-full text-gray-700 hover:bg-gray-100 transition-colors"
                  >
                    <Download class="h-4 w-4" />
                  </button>
                  <button
                    @click.stop="deleteImage(image.id)"
                    class="p-2 bg-white rounded-full text-gray-700 hover:bg-gray-100 transition-colors"
                  >
                    <Trash2 class="h-4 w-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="flex-1 flex items-center justify-center">
            <div class="text-center">
              <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Image class="h-12 w-12 text-gray-400" />
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">No images yet</h3>
              <p class="text-gray-500 mb-6">
                Generate your first AI image using our powerful image generation tools.
              </p>
              <Link
                :href="route('images.create')"
                class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
              >
                <Plus class="h-4 w-4 mr-2" />
                Generate Image
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import {
  Plus,
  Grid3X3,
  MessageSquare,
  FolderOpen,
  Users,
  Lightbulb,
  Image,
  BarChart3,
  CreditCard,
  Settings,
  Download,
  Search,
  Filter,
  X,
  Clock,
  Trash2
} from 'lucide-vue-next'

interface ImageJob {
  id: number
  prompt: string
  model: string
  size: string
  status: string
  image_url?: string
  created_at: string
}

interface Props {
  user: any
  images: ImageJob[]
  workspaces: any[]
  workspace: any
}

const props = defineProps<Props>()

const searchQuery = ref('')
const selectedType = ref('')
const selectedFilter = ref('all')

const currentRoute = computed(() => {
  return window.location.pathname
})

const navigationItems = [
  { name: 'Dashboard', icon: Grid3X3, route: '/dashboard' },
  { name: 'Chats', icon: MessageSquare, route: '/chats' },
  { name: 'Teams', icon: FolderOpen, route: '/teams' },
  { name: 'Personas', icon: Users, route: '/personas' },
  { name: 'Prompts', icon: Lightbulb, route: '/prompts' },
  { name: 'Images', icon: Image, route: '/images' },
  { name: 'Usage', icon: BarChart3, route: '/usage' },
  { name: 'Billing', icon: CreditCard, route: '/billing' },
  { name: 'Settings', icon: Settings, route: '/settings' },
  { name: 'Import', icon: Download, route: '/import' }
]

const filteredImages = computed(() => {
  // Ensure images is an array
  let filtered = Array.isArray(props.images) ? props.images : []

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(image =>
      image.prompt.toLowerCase().includes(query)
    )
  }

  // Filter by type
  if (selectedType.value) {
    filtered = filtered.filter(image => image.type === selectedType.value)
  }

  // Filter by filter selection
  if (selectedFilter.value === 'favorites') {
    filtered = filtered.filter(image => image.is_favorite)
  }

  // Sort by creation date (newest first)
  filtered.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime())

  return filtered
})

const createNewImage = () => {
  router.visit(route('images.create'))
}

const viewImage = (image: ImageJob) => {
  router.visit(route('images.show', image.id))
}

const downloadImage = (image: ImageJob) => {
  if (image.image_url) {
    const link = document.createElement('a')
    link.href = image.image_url
    link.download = `image-${image.id}.png`
    link.click()
  }
}

const deleteImage = (imageId: number) => {
  if (confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
    router.delete(route('images.destroy', imageId))
  }
}

const navigateTo = (route: string) => {
  router.visit(route)
}
</script>
