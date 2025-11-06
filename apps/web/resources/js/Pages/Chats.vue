<template>
	<AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="currentWorkspace">
		<div class="absolute inset-0 bg-slate-50 flex overflow-hidden">
			<!-- Mobile Sidebar Overlay -->
			<div v-if="showMobileSidebar" class="fixed inset-0 z-50 lg:hidden" @click="showMobileSidebar = false">
				<div class="absolute inset-0 bg-black/50"></div>
				<div class="relative w-80 h-full bg-white border-r border-slate-200" @click.stop>
					<div class="flex items-center justify-between p-4 border-b border-slate-200">
						<h2 class="text-lg font-semibold text-slate-900">Chats</h2>
						<button @click="showMobileSidebar = false"
							class="p-2 text-slate-600 hover:text-slate-700 rounded-lg hover:bg-slate-100">
							<X class="h-5 w-5" />
						</button>
					</div>
					<ChatList :chats="chats" :active-id="activeChat?.id" @select="selectChat" @pin="pinChat"
						@new-chat="createNewChat" />
				</div>
			</div>

			<!-- Desktop Sidebar -->
			<div class="hidden lg:flex lg:w-80 lg:flex-col lg:border-r lg:border-slate-200 lg:overflow-y-auto lg:h-full">
				<ChatList :chats="chats" :active-id="activeChat?.id" @select="selectChat" @pin="pinChat"
					@new-chat="createNewChat" />
			</div>

			<!-- Main Content Area -->
			<div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">
				<!-- Mobile Header -->
				<div class="lg:hidden flex items-center justify-between p-4 border-b border-slate-200 flex-shrink-0">
					<button @click="showMobileSidebar = true"
						class="p-2 text-slate-600 hover:text-slate-700 rounded-lg hover:bg-slate-100">
						<Menu class="h-5 w-5" />
					</button>
					<h1 class="text-lg font-semibold text-slate-900">Blendable</h1>
					<div class="w-9"></div> <!-- Spacer for centering -->
				</div>

				<!-- Chat Header -->
				<HeaderBar v-if="activeChat" :chat-title="activeChat.title" :model="activeChat.model"
					:persona="activeChat.persona" :available-models="availableModels" :can-edit="can.edit"
					:can-delete="can.delete" @update:model="handleModelUpdate" @search="handleSearch"
					@share="handleShare" @rename="handleRename" @export="handleExport" @delete="handleDelete" />

				<!-- Chat Content: Full height column layout -->
				<div class="flex-1 flex flex-col min-h-0 overflow-hidden h-full">
					<!-- Loading State -->
					<div v-if="isLoading" class="flex-1 flex flex-col min-h-0 overflow-hidden">
						<Skeletons type="header" />
						<div class="flex-1 overflow-y-auto p-3 md:p-4 min-h-0">
							<Skeletons type="messages" :count="6" />
						</div>
						<Skeletons type="composer" />
					</div>

					<!-- Empty State -->
					<EmptyState v-else-if="!activeChat" @use-prompt="handleUsePrompt"
						@quick-action="handleQuickAction" />

					<!-- Messages and Composer Layout -->
					<div v-else class="flex-1 flex flex-col min-h-0 overflow-hidden">
						<!-- Chat Messages: scrollable area -->
						<section
							ref="messagesContainer"
							class="flex-1 min-h-0 overflow-y-auto"
							style="scroll-behavior: smooth;"
						>
							<div class="max-w-3xl mx-auto px-3 md:px-6 pt-2 pb-4 space-y-2">
								<Message v-for="message in activeChat.messages" :key="message.id" :message="message" />

								<!-- Streaming Message -->
								<div v-if="streamingMessage" class="flex gap-3 animate-fadeIn">
									<div
										class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
										<Bot class="h-4 w-4 text-white" />
									</div>
									<div
										class="max-w-3xl rounded-2xl px-4 py-3 bg-white border border-slate-200 text-slate-900 shadow-sm">
										<div class="flex items-center gap-2 mb-2">
											<span class="text-xs font-medium text-slate-600">Assistant</span>
											<div class="w-1 h-1 bg-emerald-500 rounded-full"></div>
										</div>
										<div class="prose max-w-none text-sm">
											{{ streamingMessage }}
											<span class="inline-block w-2 h-4 bg-slate-400 animate-pulse ml-1"></span>
										</div>
									</div>
								</div>
							</div>
							<!-- Bottom spacer to keep last message visible above composer -->
							<div class="h-3"></div>
						</section>

						<!-- Composer: fixed at bottom, outside scrolling container -->
						<Composer :is-loading="isSending" @send="sendMessage" @attach="handleAttach" />
					</div>
				</div>
			</div>
		</div>
	</AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ChatList from '@/Components/chat/ChatList.vue'
import Message from '@/Components/chat/Message.vue'
import Composer from '@/Components/chat/Composer.vue'
import HeaderBar from '@/Components/chat/HeaderBar.vue'
import EmptyState from '@/Components/chat/EmptyState.vue'
import Skeletons from '@/Components/chat/Skeletons.vue'
import { X, Menu, Bot } from 'lucide-vue-next'

interface ChatSummary {
	id: string
	title: string
	lastMessage?: string
	updatedAt: string
	pinned?: boolean
	unread?: boolean
}

interface Message {
	id: string
	role: 'user' | 'assistant' | 'system'
	content: string
	createdAt: string
}

interface ActiveChat {
	id: string
	title: string
	model?: string
	persona?: string
	messages: Message[]
}

interface Model {
	id: number
	provider: string
	model_key: string
	display_name: string
	context_window: number
	multiplier: number
	enabled: boolean
}

interface Props {
	chats: ChatSummary[]
	activeChat?: ActiveChat
	availableModels: Model[]
	can: {
		create: boolean
		edit: boolean
		delete: boolean
	}
	user: any
	workspaces: any[]
	currentWorkspace: any
}

const props = defineProps<Props>()

// Reactive state
const showMobileSidebar = ref(false)
const isLoading = ref(false)
const isSending = ref(false)
const streamingMessage = ref('')
const messagesContainer = ref<HTMLElement | null>(null)

// Computed
const activeChat = computed(() => props.activeChat)

// Methods
const selectChat = (chatId: string) => {
	router.visit(`/chats/${chatId}`)
	showMobileSidebar.value = false
}

const pinChat = async (chatId: string) => {
	// Toggle pin status
	try {
		await fetch(`/chats/${chatId}/pin`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
				'Content-Type': 'application/json',
			},
		})
		router.reload()
	} catch (error) {
		console.error('Error pinning chat:', error)
	}
}

const createNewChat = () => {
	if (!props.currentWorkspace) {
		// Redirect to workspaces page if no workspace is available
		router.visit('/workspaces')
		return
	}

	// Create new chat using Inertia POST (handles CSRF automatically)
	router.post('/chats', {
		workspace_id: props.currentWorkspace.id,
	}, {
		preserveScroll: false,
		onSuccess: () => {
			// Chat creation will redirect to /chats/{id} via the controller
			showMobileSidebar.value = false
		},
		onError: (errors) => {
			console.error('Error creating chat:', errors)
		}
	})
}

const sendMessage = async (message: string) => {
	if (!activeChat.value || isSending.value) return

	isSending.value = true
	streamingMessage.value = ''

	try {
		// Get CSRF token - refresh if needed
		let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

		// If token not found, try to get it from Inertia
		if (!csrfToken) {
			// Inertia stores CSRF token in window
			csrfToken = (window as any).$inertia?.csrfToken ||
				document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

			if (!csrfToken) {
				// Reload page to get fresh token
				console.warn('CSRF token not found, reloading page')
				window.location.reload()
				return
			}
		}

		const response = await fetch(`/chats/${activeChat.value.id}/send-message`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrfToken,
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json',
			},
			credentials: 'same-origin',
			body: JSON.stringify({
				content: message,
			}),
		})

		if (!response.ok) {
			// If 419 error, reload to get fresh CSRF token
			if (response.status === 419) {
				console.error('CSRF token mismatch, reloading page')
				window.location.reload()
				return
			}
			throw new Error(`Failed to send message: ${response.status}`)
		}

		// Handle streaming response
		const reader = response.body?.getReader()
		const decoder = new TextDecoder()

		if (reader) {
			while (true) {
				const { done, value } = await reader.read()
				if (done) break

				const chunk = decoder.decode(value)
				const lines = chunk.split('\n')

				for (const line of lines) {
					if (line.startsWith('data: ')) {
						const data = line.slice(6)
						if (data === '[DONE]') {
							isSending.value = false
							streamingMessage.value = ''
							router.reload()
							return
						}

						try {
							const parsed = JSON.parse(data)
							if (parsed.delta) {
								streamingMessage.value += parsed.delta
							}
							if (parsed.error) {
								console.error('Streaming error:', parsed.error)
								isSending.value = false
							}
						} catch (e) {
							// Ignore parsing errors for incomplete chunks
						}
					}
				}
			}
		}
	} catch (error) {
		console.error('Error sending message:', error)
		isSending.value = false
	}
}

const handleUsePrompt = (prompt: string) => {
	// This would typically insert the prompt into the composer
	// For now, we'll create a new chat with the prompt
	if (!props.currentWorkspace) return

	router.post('/chats', {
		workspace_id: props.currentWorkspace.id,
		initial_message: prompt,
	})
}

const handleQuickAction = (action: string) => {
	switch (action) {
		case 'upload':
			// Handle file upload
			break
		case 'search':
			// Handle web search
			break
		case 'help':
			// Show help
			break
	}
}

const handleSearch = () => {
	// Focus search in sidebar
	showMobileSidebar.value = true
}

const handleShare = async () => {
	if (!activeChat.value) return

	try {
		const response = await fetch(`/chats/${activeChat.value.id}/share`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
			},
		})

		const data = await response.json()
		if (data.share_url) {
			await navigator.clipboard.writeText(data.share_url)
			// Show success toast
		}
	} catch (error) {
		console.error('Error sharing chat:', error)
	}
}

const handleRename = () => {
	// Implement rename functionality
	const newTitle = prompt('Enter new chat title:', activeChat.value?.title || '')
	if (newTitle && newTitle !== activeChat.value?.title) {
		// Update chat title
		router.post(`/chats/${activeChat.value?.id}/rename`, {
			title: newTitle
		})
	}
}

const handleExport = () => {
	if (!activeChat.value) return

	// Export chat as markdown or PDF
	router.post(`/chats/${activeChat.value.id}/export`, {
		format: 'markdown'
	})
}

const handleDelete = () => {
	if (!activeChat.value) return

	if (confirm('Are you sure you want to delete this chat?')) {
		router.delete(`/chats/${activeChat.value.id}`)
	}
}

const handleAttach = (files: FileList) => {
	// Handle file attachments
	console.log('Attaching files:', files)
}

const handleModelUpdate = async (modelKey: string) => {
	if (!activeChat.value) return

	try {
		await fetch(`/chats/${activeChat.value.id}/switch-model`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({ model: modelKey }),
		})

		// Update the active chat model locally
		if (activeChat.value) {
			activeChat.value.model = modelKey
		}
	} catch (error) {
		console.error('Error updating model:', error)
	}
}

// Keyboard shortcuts
const handleKeydown = (event: KeyboardEvent) => {
	// Cmd/Ctrl + K: Focus search
	if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
		event.preventDefault()
		handleSearch()
	}

	// Cmd/Ctrl + N: New chat
	if ((event.metaKey || event.ctrlKey) && event.key === 'n') {
		event.preventDefault()
		createNewChat()
	}
}

const scrollToBottom = () => {
  const el = messagesContainer.value
  if (!el) return
  // Scroll near-instant, but smooth if supported
  el.scrollTo({ top: el.scrollHeight, behavior: 'auto' })
}

// When messages change, scroll down
watch(() => activeChat.value?.messages, async () => {
  await nextTick()
  scrollToBottom()
}, { deep: true })

// Scroll to bottom when active chat changes
watch(() => activeChat.value?.id, async () => {
  await nextTick()
  scrollToBottom()
}, { immediate: false })

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
  // Wait for layout to settle before scrolling
  nextTick(() => {
    scrollToBottom()
  })
})

onUnmounted(() => {
	document.removeEventListener('keydown', handleKeydown)
})
</script>
