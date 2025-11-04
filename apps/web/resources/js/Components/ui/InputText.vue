<script setup lang="ts">
const props = defineProps<{
  id: string
  modelValue: string
  type?: string
  label?: string
  placeholder?: string
  error?: string
  autocomplete?: string
}>()
const emit = defineEmits<{'update:modelValue':[string]}>()
</script>

<template>
  <div class="space-y-2">
    <label :for="id" class="block text-sm font-medium text-slate-700">{{ label }}</label>
    <input
      :id="id"
      :type="type || 'text'"
      :value="modelValue"
      :placeholder="placeholder"
      :autocomplete="autocomplete"
      :aria-invalid="!!error"
      :aria-describedby="error ? id + '-error' : undefined"
      @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-200"
    />
    <p v-if="error" :id="id + '-error'" class="text-sm text-red-500">{{ error }}</p>
  </div>
</template>
