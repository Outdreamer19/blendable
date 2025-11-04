<template>
  <section class="py-20 md:py-32 bg-slate-50 dark:bg-white/[0.02]">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div
          v-for="kpi in kpis"
          :key="kpi.label"
          class="text-center"
        >
          <div class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500 bg-clip-text text-transparent mb-2">
            {{ kpi.value }}
          </div>
          <div class="text-xl text-slate-600 dark:text-slate-300">{{ kpi.label }}</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const kpis = [
  { value: '↓ 38%', label: 'cost reduction' },
  { value: '↑ 21%', label: 'accuracy improvement' },
  { value: '< 5.8s', label: 'p95 latency' },
]
</script>

