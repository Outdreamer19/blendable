<template>
  <div class="flex-1 flex items-center justify-center p-3 md:p-4 lg:p-6">
    <div class="max-w-2xl mx-auto text-center">
      <!-- Hero Icon -->
      <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 via-sky-500 to-cyan-400 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
        <MessageSquare class="h-10 w-10 text-white" />
      </div>

      <!-- Title -->
      <h2 class="text-2xl font-semibold text-slate-900 mb-3 leading-tight">
        Start a new conversation
      </h2>

      <!-- Description -->
      <p class="text-slate-600 mb-8 text-sm md:text-base">
        Ask me anything, get help with coding, brainstorm ideas, or just have a chat. I'm here to help!
      </p>

      <!-- Suggested Prompts -->
      <div class="space-y-3">
        <h3 class="text-sm font-medium text-slate-700 mb-4">
          Try asking me:
        </h3>
        
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
          <button
            v-for="prompt in suggestedPrompts"
            :key="prompt.id"
            @click="$emit('use-prompt', prompt.text)"
            class="p-4 bg-white border border-slate-200 rounded-2xl text-left hover:bg-slate-50 transition-all duration-200 group focus:outline-none focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-2 focus:ring-offset-slate-50"
          >
            <div class="flex items-start gap-3">
              <div class="flex-shrink-0 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                <component :is="prompt.icon" class="h-4 w-4 text-slate-600" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-slate-900 mb-1">
                  {{ prompt.title }}
                </h4>
                <p class="text-xs text-slate-600 line-clamp-2">
                  {{ prompt.description }}
                </p>
              </div>
            </div>
          </button>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="mt-8 pt-6 border-t border-slate-200">
        <div class="flex flex-wrap justify-center gap-3">
          <button
            v-for="action in quickActions"
            :key="action.id"
            @click="$emit('quick-action', action.action)"
            class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-2 focus:ring-offset-slate-50"
          >
            <component :is="action.icon" class="h-4 w-4" />
            {{ action.label }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { 
  MessageSquare, 
  Code, 
  Lightbulb, 
  FileText, 
  Zap, 
  Brain, 
  Image, 
  Globe,
  BookOpen,
  HelpCircle
} from 'lucide-vue-next'

defineEmits<{
  'use-prompt': [text: string]
  'quick-action': [action: string]
}>()

const suggestedPrompts = [
  {
    id: '1',
    title: 'Code Review',
    description: 'Review my code and suggest improvements',
    text: 'Can you review this code and suggest improvements?',
    icon: Code
  },
  {
    id: '2',
    title: 'Brainstorm Ideas',
    description: 'Help me brainstorm creative solutions',
    text: 'Help me brainstorm creative ideas for:',
    icon: Lightbulb
  },
  {
    id: '3',
    title: 'Write Documentation',
    description: 'Create clear and comprehensive docs',
    text: 'Help me write documentation for:',
    icon: FileText
  },
  {
    id: '4',
    title: 'Explain Concept',
    description: 'Break down complex topics simply',
    text: 'Can you explain this concept in simple terms:',
    icon: BookOpen
  },
  {
    id: '5',
    title: 'Generate Content',
    description: 'Create engaging content and copy',
    text: 'Help me create engaging content about:',
    icon: Zap
  },
  {
    id: '6',
    title: 'Problem Solving',
    description: 'Work through challenges step by step',
    text: 'Help me solve this problem:',
    icon: Brain
  }
]

const quickActions = [
  {
    id: '1',
    label: 'Upload File',
    action: 'upload',
    icon: Image
  },
  {
    id: '2',
    label: 'Web Search',
    action: 'search',
    icon: Globe
  },
  {
    id: '3',
    label: 'Get Help',
    action: 'help',
    icon: HelpCircle
  }
]
</script>
