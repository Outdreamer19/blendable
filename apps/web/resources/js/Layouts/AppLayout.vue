<template>
	<!-- Page as vertical flex: header + content -->
	<div class="min-h-screen bg-gray-50 flex flex-col">
		<!-- Navigation -->
		<nav class="bg-white shadow-sm border-b flex-shrink-0">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between h-16">
					<div class="flex items-center">
						<Link :href="route('dashboard')" class="flex items-center">
							<div class="flex-shrink-0">
								<h1 class="text-xl font-bold text-gray-900">OmniAI</h1>
							</div>
						</Link>
					</div>

					<div class="flex items-center space-x-4">
						<!-- Workspace Selector -->
						<div class="relative">
							<select v-model="currentWorkspace" @change="switchWorkspace"
								class="bg-white border border-gray-300 rounded-md px-3 py-2 text-sm">
								<option v-for="workspace in workspaces" :key="workspace.id" :value="workspace">
									{{ workspace.name }}
								</option>
							</select>
						</div>

						<!-- User Menu -->
						<div class="relative">
							<button @click="showUserMenu = !showUserMenu"
								class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
								<div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
									<span class="text-white text-sm font-medium">{{ user?.name?.charAt(0) || 'U'
										}}</span>
								</div>
							</button>

							<div v-show="showUserMenu"
								class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
								<Link :href="route('profile.edit')"
									class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
									Settings
								</Link>
								<Link :href="route('logout')" method="post"
									class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
									Sign out
								</Link>
							</div>
						</div>
					</div>
				</div>
			</div>
		</nav>

		<!-- Content row: takes remaining height; children manage their own scroll -->
		<div class="flex flex-1 min-h-0">
			<!-- Sidebar: allow its own scrolling if it overflows -->
			<aside class="w-64 bg-white shadow-sm overflow-y-auto flex-shrink-0">
				<nav class="mt-5 px-2">
					<div class="space-y-1">
						<Link :href="route('dashboard')"
							:class="[isDashboard ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<LayoutDashboard class="mr-3 h-5 w-5" />
							Dashboard
						</Link>

						<Link :href="route('chats.index')"
							:class="[isChats ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<MessageSquare class="mr-3 h-5 w-5" />
							Chats
						</Link>

						<Link :href="route('personas.index')"
							:class="[isPersonas ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<Users class="mr-3 h-5 w-5" />
							Personas
						</Link>

						<Link :href="route('prompts.index')"
							:class="[isPrompts ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<Bookmark class="mr-3 h-5 w-5" />
							Prompts
						</Link>

						<Link :href="route('images.index')"
							:class="[isImages ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<Image class="mr-3 h-5 w-5" />
							Images
						</Link>

						<Link :href="route('usage.index')"
							:class="[isUsage ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<BarChart3 class="mr-3 h-5 w-5" />
							Usage
						</Link>

						<Link :href="route('teams.index')"
							:class="[isTeams ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<Users class="mr-3 h-5 w-5" />
							Teams
						</Link>

						<Link :href="route('workspaces.index')"
							:class="[isWorkspaces ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<Folder class="mr-3 h-5 w-5" />
							Workspaces
						</Link>

						<Link :href="route('billing.index')"
							:class="[isBilling ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
							<CreditCard class="mr-3 h-5 w-5" />
							Billing
						</Link>
					</div>
				</nav>
			</aside>

			<!-- Main: owns scroll; no viewport height classes -->
			<main class="flex-1 relative min-h-0 overflow-hidden">
				<slot />
			</main>
		</div>
	</div>

	<!-- Global Notifications -->
	<!-- <NotificationContainer /> -->
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import { LayoutDashboard, MessageSquare, Users, Bookmark, Image, BarChart3, CreditCard, Folder } from 'lucide-vue-next'

interface Props {
	user: {
		id: number
		name: string
		email: string
	}
	workspaces: Array<{
		id: number
		name: string
		slug: string
	}>
	currentWorkspace?: {
		id: number
		name: string
		slug: string
	}
}

const props = defineProps<Props>()

const showUserMenu = ref(false)
const currentWorkspace = ref(props.currentWorkspace)

// Use window.location directly to avoid usePage() issues
const currentUrl = ref(window.location.pathname || '/')

// Watch for route changes using window.location
watch(() => window.location.pathname, (newPath) => {
	currentUrl.value = newPath
}, { immediate: true })

// Update URL on browser navigation
if (typeof window !== 'undefined') {
	window.addEventListener('popstate', () => {
		currentUrl.value = window.location.pathname
	})
}

const isDashboard = computed(() => {
	try {
		return currentUrl.value === '/dashboard'
	} catch (error) {
		return false
	}
})

const isChats = computed(() => {
	try {
		return currentUrl.value.startsWith('/chats')
	} catch (error) {
		return false
	}
})

const isPersonas = computed(() => {
	try {
		return currentUrl.value.startsWith('/personas')
	} catch (error) {
		return false
	}
})

const isPrompts = computed(() => {
	try {
		return currentUrl.value.startsWith('/prompts')
	} catch (error) {
		return false
	}
})

const isImages = computed(() => {
	try {
		return currentUrl.value.startsWith('/images')
	} catch (error) {
		return false
	}
})

const isUsage = computed(() => {
	try {
		return currentUrl.value.startsWith('/usage')
	} catch (error) {
		return false
	}
})

const isTeams = computed(() => {
	try {
		return currentUrl.value.startsWith('/teams')
	} catch (error) {
		return false
	}
})

const isWorkspaces = computed(() => {
	try {
		return currentUrl.value.startsWith('/workspaces')
	} catch (error) {
		return false
	}
})

const isBilling = computed(() => {
	try {
		return currentUrl.value.startsWith('/billing')
	} catch (error) {
		return false
	}
})

const switchWorkspace = () => {
	// Handle workspace switching
	if (currentWorkspace.value && typeof currentWorkspace.value === 'object') {
		window.location.href = `/workspaces/${currentWorkspace.value.id}`
	}
}
</script>
