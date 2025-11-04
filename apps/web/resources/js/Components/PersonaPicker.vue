<template>
  <div class="flex items-center gap-2">
    <label class="text-sm font-medium text-gray-700">Persona</label>
    <select 
      v-model="selectedPersonaId" 
      @change="handleChange" 
      class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
    >
      <option value="">👤 None</option>
      <option v-for="persona in (Array.isArray(personas) ? personas : [])" :key="persona.id" :value="persona.id">
        {{ getPersonaIcon(persona.name) }} {{ persona.name }}
      </option>
    </select>
  </div>
</template>

<script setup lang="ts">
import { ref, watchEffect } from 'vue'

interface Persona {
  id: number
  name: string
  description?: string
  system_prompt?: string
  avatar_url?: string
}

interface Props {
  personas: Persona[]
  personaId?: number | null
}

const props = withDefaults(defineProps<Props>(), {
  personas: () => [],
  personaId: null
})

const emit = defineEmits<{
  'update:persona': [personaId: number | null]
}>()

const selectedPersonaId = ref(props.personaId || null)

watchEffect(() => {
  selectedPersonaId.value = props.personaId || null
})

const handleChange = () => {
  emit('update:persona', selectedPersonaId.value ? Number(selectedPersonaId.value) : null)
}

const getPersonaIcon = (name: string) => {
  const icons: Record<string, string> = {
    'Developer': '👨‍💻',
    'Writer': '✍️',
    'Designer': '🎨',
    'Analyst': '📊',
    'Teacher': '👨‍🏫',
    'Researcher': '🔬',
    'Creative': '💡',
    'Technical': '⚙️',
    'Business': '💼',
    'Marketing': '📈',
  }
  
  // Try to match by name or return a default
  for (const [key, icon] of Object.entries(icons)) {
    if (name.toLowerCase().includes(key.toLowerCase())) {
      return icon
    }
  }
  
  return '👤'
}
</script>
