<template>
  <div 
    :class="[
      'flex gap-3 animate-fadeIn',
      message.role === 'user' ? 'justify-end' : 'justify-start'
    ]"
  >
    <!-- AI Avatar -->
    <div v-if="message.role === 'assistant'" class="flex-shrink-0">
      <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center">
        <Bot class="h-4 w-4 text-white" />
      </div>
    </div>

    <!-- Message Content -->
    <div 
      :class="[
        'max-w-3xl rounded-2xl px-4 py-3 border shadow-sm',
        message.role === 'user' 
          ? 'bg-indigo-50 border-indigo-200 text-slate-900' 
          : 'bg-white border-slate-200 text-slate-900'
      ]"
    >
      <!-- Message Header -->
      <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
          <span class="text-xs font-medium text-slate-600">
            {{ message.role === 'user' ? 'You' : 'Assistant' }}
          </span>
          <div v-if="message.role === 'assistant'" class="w-1 h-1 bg-emerald-500 rounded-full"></div>
          <!-- Model Badge -->
          <div 
            v-if="message.role === 'assistant' && message.model_key" 
            :class="[
              'px-2 py-1 rounded-md text-xs font-medium',
              getProviderColor(message.model_key)
            ]"
          >
            {{ formatModelName(message.model_key) }}
          </div>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-xs text-slate-500">
            {{ formatTime(message.createdAt) }}
          </span>
          <button
            v-if="message.role === 'assistant'"
            @click="copyMessage"
            class="p-1 text-slate-500 hover:text-slate-600 transition-colors"
            title="Copy message"
          >
            <Copy class="h-3 w-3" />
          </button>
        </div>
      </div>

      <!-- Message Body -->
      <div 
        :class="[
          'prose max-w-none text-sm',
          'prose-headings:text-slate-900 prose-headings:font-semibold',
          'prose-p:text-slate-700 prose-p:leading-relaxed',
          'prose-code:text-slate-700 prose-code:bg-slate-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-code:text-xs',
          'prose-pre:bg-slate-100 prose-pre:border prose-pre:border-slate-200',
          'prose-blockquote:border-slate-200 prose-blockquote:text-slate-600',
          'prose-strong:text-slate-900',
          'prose-a:text-sky-500 hover:prose-a:text-sky-600',
          'prose-ul:text-slate-700 prose-ol:text-slate-700',
          'prose-li:text-slate-700'
        ]"
        v-html="renderedContent"
      ></div>

      <!-- Code Copy Button for Code Blocks -->
      <div v-if="hasCodeBlocks" class="mt-2 flex justify-end">
        <button
          @click="copyCode"
          class="text-xs text-slate-500 hover:text-slate-600 transition-colors flex items-center gap-1"
        >
          <Copy class="h-3 w-3" />
          Copy code
        </button>
      </div>

      <!-- Token Information for Assistant Messages -->
      <div v-if="message.role === 'assistant' && (message.tokens_in || message.tokens_out)" class="mt-2 pt-2 border-t border-slate-200">
        <div class="flex items-center justify-between text-xs text-slate-500">
          <div class="flex items-center gap-3">
            <span v-if="message.tokens_in">In: {{ message.tokens_in }} tokens</span>
            <span v-if="message.tokens_out">Out: {{ message.tokens_out }} tokens</span>
          </div>
          <span v-if="message.tokens_in && message.tokens_out">
            Total: {{ message.tokens_in + message.tokens_out }} tokens
          </span>
        </div>
      </div>
    </div>

    <!-- User Avatar -->
    <div v-if="message.role === 'user'" class="flex-shrink-0">
      <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center">
        <User class="h-4 w-4 text-white" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { Bot, User, Copy } from 'lucide-vue-next'

interface Message {
  id: string
  role: 'user' | 'assistant' | 'system'
  content: string
  model_key?: string
  tokens_in?: number
  tokens_out?: number
  createdAt: string
}

interface Props {
  message: Message
}

const props = defineProps<Props>()

const formatModelName = (modelKey?: string): string => {
  if (!modelKey) return ''
  
  // Convert model keys to display names
  const modelNames: Record<string, string> = {
    'gpt-4o': 'GPT-4o',
    'gpt-4': 'GPT-4',
    'gpt-3.5-turbo': 'GPT-3.5 Turbo',
    'gpt-3.5-turbo-0125': 'GPT-3.5 Turbo',
    'claude-3-5-sonnet-20241022': 'Claude 3.5 Sonnet',
    'claude-3-haiku-20240307': 'Claude 3 Haiku',
    'gemini-2.5-pro': 'Gemini 2.5 Pro',
    'gemini-2.5-flash': 'Gemini 2.5 Flash',
    'gemini-2.0-flash': 'Gemini 2.0 Flash',
    'gemini-1.5-pro': 'Gemini 1.5 Pro',
  }
  
  return modelNames[modelKey] || modelKey
}

const getProviderColor = (modelKey?: string): string => {
  if (!modelKey) return 'bg-slate-100 text-slate-600'
  
  if (modelKey.startsWith('gpt-')) return 'bg-green-100 text-green-700'
  if (modelKey.startsWith('claude-')) return 'bg-orange-100 text-orange-700'
  if (modelKey.startsWith('gemini-')) return 'bg-blue-100 text-blue-700'
  
  return 'bg-slate-100 text-slate-600'
}

const renderedContent = computed(() => {
  // Simple markdown-like rendering for now
  // In a real app, you'd use a proper markdown parser like markdown-it
  let content = props.message.content
  
  // Convert code blocks
  content = content.replace(/```(\w+)?\n([\s\S]*?)```/g, '<pre><code class="language-$1">$2</code></pre>')
  
  // Convert inline code
  content = content.replace(/`([^`]+)`/g, '<code>$1</code>')
  
  // Convert bold
  content = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
  
  // Convert italic
  content = content.replace(/\*(.*?)\*/g, '<em>$1</em>')
  
  // Convert links
  content = content.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>')
  
  // Convert line breaks
  content = content.replace(/\n/g, '<br>')
  
  return content
})

const hasCodeBlocks = computed(() => {
  return props.message.content.includes('```')
})

const formatTime = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

const copyMessage = async () => {
  try {
    await navigator.clipboard.writeText(props.message.content)
    // You could add a toast notification here
  } catch (err) {
    console.error('Failed to copy message:', err)
  }
}

const copyCode = async () => {
  const codeMatch = props.message.content.match(/```(\w+)?\n([\s\S]*?)```/)
  if (codeMatch) {
    try {
      await navigator.clipboard.writeText(codeMatch[2])
      // You could add a toast notification here
    } catch (err) {
      console.error('Failed to copy code:', err)
    }
  }
}
</script>
