<template>
  <component
    :is="as"
    :href="as === 'a' ? href : undefined"
    :type="as === 'button' ? type : undefined"
    :disabled="disabled"
    :class="[
      'inline-flex items-center justify-center rounded-full px-6 py-3 font-semibold text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-950 disabled:opacity-60 disabled:cursor-not-allowed',
      buttonClasses
    ]"
  >
    <template v-if="variant === 'primary'">
      <!-- Top highlight for primary button -->
      <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
    </template>
    <template v-else-if="variant === 'ghost'">
      <!-- Top highlight for ghost button -->
      <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
    </template>
    <span class="relative z-10"><slot /></span>
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  variant?: 'primary' | 'ghost' | 'link'
  as?: 'a' | 'button'
  href?: string
  type?: 'button' | 'submit' | 'reset'
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  as: 'button',
  type: 'button',
  disabled: false,
})

const buttonClasses = computed(() => {
  switch (props.variant) {
    case 'primary':
      return 'relative bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500 text-slate-950 hover:opacity-95 focus:ring-indigo-500 shadow-[0_1px_0_0_rgba(255,255,255,0.3)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_5px_-1px_rgba(0,0,0,0.4),0_1px_4px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.15)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_5px_-1px_rgba(0,0,0,0.6),0_1px_4px_-1px_rgba(0,0,0,0.5)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.3)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_3px_6px_-2px_rgba(0,0,0,0.45),0_2px_5px_-1px_rgba(0,0,0,0.35)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.15)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_3px_6px_-2px_rgba(0,0,0,0.75),0_2px_5px_-1px_rgba(0,0,0,0.55)]'
    case 'ghost':
      return 'relative bg-slate-100 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-900 dark:text-white hover:bg-slate-200 dark:hover:bg-white/10 focus:ring-slate-400 dark:focus:ring-white/20 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_3px_5px_-1px_rgba(0,0,0,0.2),0_2px_3px_-1px_rgba(0,0,0,0.15)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_3px_5px_-1px_rgba(0,0,0,0.4),0_2px_3px_-1px_rgba(0,0,0,0.3)]'
    case 'link':
      return 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white underline underline-offset-4 bg-transparent focus:ring-indigo-500'
    default:
      return ''
  }
})
</script>

