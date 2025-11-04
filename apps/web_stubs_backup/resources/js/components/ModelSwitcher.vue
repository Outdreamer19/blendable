<template>
  <div class="flex items-center gap-2">
    <label class="text-sm">Model</label>
    <select v-model="model" @change="$emit('update:model', model)" class="border rounded px-2 py-1">
      <option value="auto">Auto</option>
      <option value="gpt-4o">GPT‑4o</option>
      <option value="claude-3-7-sonnet">Claude 3.7 Sonnet</option>
      <option value="gemini-pro">Gemini Pro</option>
    </select>
  </div>
</template>
<script setup lang="ts">
import { ref, watchEffect } from 'vue'
const props = defineProps<{ model?: string }>()
const model = ref(props.model || 'auto')
watchEffect(() => (model.value = props.model || 'auto'))
</script>
