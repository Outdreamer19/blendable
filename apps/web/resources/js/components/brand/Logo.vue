<template>
  <Link href="/" class="flex items-center gap-2 group">
    <div class="relative">
      <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500 rounded-full blur opacity-30 group-hover:opacity-50 transition-opacity"></div>
      <div class="relative w-8 h-8 rounded-full bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500 flex items-center justify-center">
        <div class="w-4 h-4 rounded-full bg-white/20 backdrop-blur-sm"></div>
      </div>
    </div>
    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Blendable</span>
  </Link>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>

