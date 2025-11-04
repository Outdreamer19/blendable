<template>
  <div class="sticky bottom-0 bg-white/85 backdrop-blur-sm supports-[backdrop-filter]:bg-white/70 border-t border-slate-200 shadow-lg z-10 flex-shrink-0">
    <div class="p-2 md:p-3">
      <div class="max-w-3xl mx-auto">
        <!-- File Attachments -->
        <div v-if="attachments.length > 0" class="mb-3">
          <div class="flex flex-wrap gap-2">
            <div 
              v-for="attachment in attachments" 
              :key="attachment.id"
              class="flex items-center gap-2 bg-slate-100 border border-slate-200 rounded-lg px-3 py-2"
            >
              <Paperclip class="h-4 w-4 text-slate-600" />
              <span class="text-sm text-slate-700">{{ attachment.name }}</span>
              <button 
                @click="removeAttachment(attachment.id)"
                class="text-slate-500 hover:text-slate-600"
              >
                <X class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>

        <!-- Input Area -->
        <div class="relative">
          <textarea
            ref="textareaRef"
            v-model="message"
            @keydown="handleKeydown"
            @input="adjustHeight"
            :disabled="isLoading"
            :placeholder="isLoading ? 'Sending...' : 'Type your message...'"
            class="w-full px-4 py-3 pr-24 bg-white border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-500 resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-2 focus:ring-offset-slate-50 transition-all duration-200"
            rows="1"
            style="min-height: 48px; max-height: 144px;"
          ></textarea>
          
          <!-- Action Buttons -->
          <div class="absolute right-3 top-3 flex items-center gap-2">
            <!-- Attach Button -->
            <button
              @click="attachFile"
              :disabled="isLoading"
              class="p-2 text-slate-600 hover:text-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              title="Attach file"
            >
              <Paperclip class="h-4 w-4" />
            </button>
            
            <!-- Send Button -->
            <button
              @click="sendMessage"
              :disabled="!message.trim() || isLoading"
              :class="[
                'p-2 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-2 focus:ring-offset-slate-50',
                message.trim() && !isLoading
                  ? 'bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400 text-white hover:from-indigo-600 hover:via-sky-600 hover:to-cyan-500'
                  : 'bg-slate-200 text-slate-500 cursor-not-allowed'
              ]"
              title="Send message (Enter)"
            >
              <Send v-if="!isLoading" class="h-4 w-4" />
              <div v-else class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
            </button>
          </div>
        </div>

        <!-- Helper Text -->
        <div class="flex items-center justify-between mt-2 text-xs text-slate-500">
          <span>↵ to send, ⇧↵ for new line</span>
          <span v-if="message.length > 0" class="text-slate-600">
            {{ message.length }} characters
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, nextTick, onMounted } from 'vue'
import { Send, Paperclip, X } from 'lucide-vue-next'

interface Attachment {
  id: string
  name: string
  file: File
}

interface Props {
  isLoading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  isLoading: false
})

const emit = defineEmits<{
  send: [message: string]
  attach: [files: FileList]
}>()

const message = ref('')
const attachments = ref<Attachment[]>([])
const textareaRef = ref<HTMLTextAreaElement>()

const adjustHeight = async () => {
  await nextTick()
  if (textareaRef.value) {
    textareaRef.value.style.height = 'auto'
    const scrollHeight = textareaRef.value.scrollHeight
    const maxHeight = 144 // 6 lines * 24px
    textareaRef.value.style.height = `${Math.min(scrollHeight, maxHeight)}px`
  }
}

const handleKeydown = (event: KeyboardEvent) => {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault()
    sendMessage()
  }
}

const sendMessage = () => {
  if (!message.value.trim() || props.isLoading) return
  
  // Emit the message and clear the input
  const messageToSend = message.value.trim()
  message.value = ''
  
  // Reset textarea height
  if (textareaRef.value) {
    textareaRef.value.style.height = 'auto'
  }
  
  // Emit the send event
  emit('send', messageToSend)
}

const attachFile = () => {
  const input = document.createElement('input')
  input.type = 'file'
  input.multiple = true
  input.onchange = (e) => {
    const files = (e.target as HTMLInputElement).files
    if (files && files.length > 0) {
      const emit = defineEmits<{
        attach: [files: FileList]
      }>()
      emit('attach', files)
      
      // Add to local attachments for display
      Array.from(files).forEach(file => {
        attachments.value.push({
          id: Math.random().toString(36).substr(2, 9),
          name: file.name,
          file
        })
      })
    }
  }
  input.click()
}

const removeAttachment = (id: string) => {
  attachments.value = attachments.value.filter(att => att.id !== id)
}

onMounted(() => {
  // Focus the textarea on mount
  if (textareaRef.value) {
    textareaRef.value.focus()
  }
})
</script>
