<script setup lang="ts">
const props = defineProps<{ id: string; modelValue: boolean; label: string }>()
const emit = defineEmits<{'update:modelValue':[boolean]}>()
</script>

<template>
  <label :for="props.id" class="inline-flex items-center gap-3 cursor-pointer select-none">
    <input
      :id="props.id"
      type="checkbox"
      :checked="props.modelValue"
      @change="emit('update:modelValue', ($event.target as HTMLInputElement).checked)"
      class="h-4 w-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500/50 bg-white"
    />
    <span class="text-sm text-slate-600">{{ props.label }}</span>
  </label>
</template>
