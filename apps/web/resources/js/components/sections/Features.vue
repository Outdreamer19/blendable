<template>
  <section class="py-20 md:py-20 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <!-- FEATURES Tag -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-100 dark:bg-slate-800 rounded-full shadow-sm border border-gray-200 dark:border-slate-700 mb-4">
          <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">FEATURES</span>
        </div>
        
        <!-- Main Title -->
        <h2 class="text-4xl md:text-5xl lg:text-6xl font-normal text-gray-900 dark:text-white mb-4">
          All features in 1 tool
        </h2>
        
        <!-- Subtitle -->
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Route every prompt to the right model—cut costs, boost quality, zero prompt changes.
        </p>
      </div>

      <!-- Feature Cards Grid (2x2) -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Card 1: Smart Model Routing (with image on left) -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow overflow-hidden">
          <!-- Top highlight -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
          <!-- Bottom shadow border -->
          <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
          <div class="flex flex-col md:flex-row gap-6">
            <!-- Image Section -->
            <div class="flex-shrink-0 md:w-1/2">
              <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center overflow-hidden">
                <!-- Routing Illustration Placeholder -->
                <div class="relative w-full h-full">
                  <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/20 dark:to-blue-900/20"></div>
                  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    <!-- Routing Nodes Illustration -->
                    <div class="w-32 h-32 bg-white dark:bg-slate-800 rounded-2xl shadow-lg flex items-center justify-center">
                      <svg class="w-20 h-20 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Content Section -->
            <div class="flex-1 md:w-1/2 flex flex-col">
              <!-- Icon -->
              <div class="w-10 h-10 bg-gray-900 dark:bg-slate-200 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
              </div>
              
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                Smart Model Routing
              </h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                Automatically route every prompt to the best AI model—GPT-4, Claude, Gemini, and more. Zero prompt changes required.
              </p>
            </div>
          </div>
        </div>

        <!-- Card 2: Cost Optimization (icon only) -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
          <!-- Top highlight -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
          <!-- Bottom shadow border -->
          <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
          <!-- Icon -->
          <div class="w-10 h-10 bg-gray-900 dark:bg-slate-200 rounded-lg flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
            Cost Optimization
          </h3>
          <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            Set budgets, per-plan caps, and automatic fallbacks to cheaper models when appropriate—cut costs without sacrificing quality.
          </p>
        </div>

        <!-- Card 3: Usage Analytics (icon only) -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
          <!-- Top highlight -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
          <!-- Bottom shadow border -->
          <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
          <!-- Icon -->
          <div class="w-10 h-10 bg-gray-900 dark:bg-slate-200 rounded-lg flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
            Usage Analytics
          </h3>
          <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            Track latency, spend, and accuracy by model. Get comprehensive insights into performance and costs across all AI providers.
          </p>
        </div>

        <!-- Card 4: Multi-Model Support (with image on left) -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow overflow-hidden">
          <!-- Top highlight -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
          <!-- Bottom shadow border -->
          <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
          <div class="flex flex-col md:flex-row gap-6">
            <!-- Image Section -->
            <div class="flex-shrink-0 md:w-1/2">
              <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center overflow-hidden">
                <!-- Multi-Model Illustration Placeholder -->
                <div class="relative w-full h-full">
                  <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-cyan-100 dark:from-indigo-900/20 dark:to-cyan-900/20"></div>
                  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    <!-- Multiple Models Illustration -->
                    <div class="w-32 h-32 bg-white dark:bg-slate-800 rounded-2xl shadow-lg flex items-center justify-center">
                      <svg class="w-20 h-20 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Content Section -->
            <div class="flex-1 md:w-1/2 flex flex-col">
              <!-- Icon -->
              <div class="w-10 h-10 bg-gray-900 dark:bg-slate-200 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                Multi-Model Support
              </h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                Access GPT-4, Claude 3, Gemini, and more through a single unified API. Switch models seamlessly or let Blendable choose automatically.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
// No props or emits needed for this static section
</script>

