<script setup lang="ts">
const props = withDefaults(defineProps<{
  variant?: 'solid' | 'gradient' | 'ghost'
  type?: 'button' | 'submit' | 'reset'
  disabled?: boolean
}>(), { variant: 'solid', type: 'button', disabled: false })
</script>

<template>
  <button
    :type="props.type"
    :disabled="props.disabled"
    class="inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold transition-all duration-200 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-500/50 ring-offset-2 ring-offset-white disabled:opacity-60 disabled:cursor-not-allowed"
    :class="{
      'bg-slate-900 text-white hover:bg-slate-800 shadow-lg': props.variant === 'solid',
      'bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white hover:opacity-90 shadow-lg shadow-blue-500/25': props.variant === 'gradient',
      'bg-transparent border border-slate-300 text-slate-700 hover:bg-slate-50': props.variant === 'ghost'
    }"
  >
    <slot />
  </button>
</template>
