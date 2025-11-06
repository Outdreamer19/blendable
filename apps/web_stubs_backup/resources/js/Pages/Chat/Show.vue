<template>
  <div class="p-6 space-y-4">
    <div class="flex items-center justify-between">
      <ModelSwitcher :model="model" @update:model="model = $event" />
      <PersonaPicker :personas="[]" :personaId="null" />
    </div>

    <div class="border rounded p-4 h-96 overflow-auto">
      <div v-for="(m,i) in messages" :key="i" class="mb-3">
        <div class="text-xs uppercase text-gray-400">{{ m.role }}</div>
        <div class="whitespace-pre-wrap">{{ m.content }}</div>
      </div>
    </div>

    <form @submit.prevent="send">
      <textarea v-model="input" class="w-full border rounded p-3" rows="3" placeholder="Type a message…"></textarea>
      <div class="mt-2 flex items-center gap-2">
        <button class="px-3 py-2 border rounded">Send</button>
        <button type="button" class="px-3 py-2 border rounded" @click="switchModel">Switch to {{ nextModelLabel }}</button>
      </div>
    </form>

    <TipTapEditor />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import ModelSwitcher from '../../components/ModelSwitcher.vue'
import PersonaPicker from '../../components/PersonaPicker.vue'
import TipTapEditor from '../../components/TipTapEditor.vue'

const props = defineProps<{ chatId: number }>()

const model = ref('auto')
const messages = ref<Array<{role:string, content:string}>>([
  { role:'system', content:'Welcome to Blendable (stub).' }
])
const input = ref('')

const nextModelLabel = computed(() => model.value === 'gpt-4o' ? 'Claude 3.7' : 'GPT‑4o')

function send(){
  if(!input.value.trim()) return
  messages.value.push({ role:'user', content: input.value })
  messages.value.push({ role:'assistant', content: '(streamed response will appear here…)'})
  input.value = ''
}
function switchModel(){
  model.value = model.value === 'gpt-4o' ? 'claude-3-7-sonnet' : 'gpt-4o'
}
</script>
