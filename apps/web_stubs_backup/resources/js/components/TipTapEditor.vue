<template>
  <div>
    <div ref="editorEl" class="prose max-w-none border rounded p-3"></div>
    <div class="mt-2 text-xs text-gray-500">Stub editor — Cursor will wire Tiptap here.</div>
  </div>
</template>
<script setup lang="ts">
import { onMounted, ref } from 'vue'
const editorEl = ref<HTMLElement|null>(null)
onMounted(() => {
  if (editorEl.value) editorEl.value.innerHTML = '<p><em>Editor will render assistant output here…</em></p>'
})
</script>
