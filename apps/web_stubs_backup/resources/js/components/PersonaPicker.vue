<template>
  <div class="flex items-center gap-2">
    <label class="text-sm">Persona</label>
    <select v-model="personaId" @change="$emit('update:personaId', personaId)" class="border rounded px-2 py-1">
      <option :value="null">None</option>
      <option v-for="p in personas" :key="p.id" :value="p.id">{{ p.name }}</option>
    </select>
  </div>
</template>
<script setup lang="ts">
const props = defineProps<{ personas: Array<{id:number,name:string}>, personaId?: number|null }>()
</script>
