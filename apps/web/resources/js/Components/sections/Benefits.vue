<template>
  <section class="py-20 md:py-32 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <!-- BENEFITS Tag -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white dark:bg-slate-800 rounded-full shadow-[0_2px_4px_rgba(0,0,0,0.05)] mb-4">
          <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">BENEFITS</span>
        </div>
        
        <!-- Main Title -->
        <h2 class="text-4xl md:text-5xl lg:text-6xl font-normal text-gray-900 dark:text-white mb-4">
          Why Choose Us
        </h2>
        
        <!-- Subtitle -->
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Route every prompt to the right model—cut costs, boost quality, zero prompt changes.
        </p>
      </div>

      <!-- Three Main Feature Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <!-- Card 1: Smart Model Routing -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
          <!-- Top highlight -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
          <!-- Bottom shadow border -->
          <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
          <!-- Routing Graphic -->
          <div class="flex justify-center mb-6">
            <div class="relative w-32 h-32 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center shadow-[inset_0_2px_4px_rgba(0,0,0,0.1)]">
              <!-- Center Hub -->
              <div class="absolute w-8 h-8 bg-gray-800 dark:bg-gray-200 rounded-full z-10"></div>
              <!-- Routing Arrows -->
              <div class="absolute top-2 left-1/2 -translate-x-1/2 w-6 h-6 border-2 border-gray-800 dark:border-gray-200 rounded-full"></div>
              <div class="absolute bottom-2 left-1/2 -translate-x-1/2 w-6 h-6 border-2 border-gray-800 dark:border-gray-200 rounded-full"></div>
              <div class="absolute left-2 top-1/2 -translate-y-1/2 w-6 h-6 border-2 border-gray-800 dark:border-gray-200 rounded-full"></div>
              <div class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 border-2 border-gray-800 dark:border-gray-200 rounded-full"></div>
              <!-- Connection Lines -->
              <div class="absolute top-2 left-1/2 w-px h-8 bg-gray-300 dark:bg-slate-600 -translate-x-1/2"></div>
              <div class="absolute bottom-2 left-1/2 w-px h-8 bg-gray-300 dark:bg-slate-600 -translate-x-1/2"></div>
              <div class="absolute left-2 top-1/2 w-8 h-px bg-gray-300 dark:bg-slate-600 -translate-y-1/2"></div>
              <div class="absolute right-2 top-1/2 w-8 h-px bg-gray-300 dark:bg-slate-600 -translate-y-1/2"></div>
            </div>
          </div>
          
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 text-center">
            Smart Model Routing
          </h3>
          <p class="text-gray-600 dark:text-gray-400 text-center leading-relaxed">
            Automatically route every prompt to the best AI model—GPT-4, Claude, Gemini, and more.
          </p>
        </div>

        <!-- Card 2: Cost Optimization -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
          <!-- Top highlight -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
          <!-- Bottom shadow border -->
          <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
          <!-- Cost Optimization Icon -->
          <div class="flex justify-center mb-6">
            <div class="relative w-32 h-32 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-full flex items-center justify-center shadow-[inset_0_2px_4px_rgba(0,0,0,0.1)]">
              <!-- Dollar sign with trending down arrow -->
              <div class="relative">
                <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <!-- Downward arrow overlay -->
                <svg class="absolute -bottom-1 -right-1 w-6 h-6 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>
          </div>
          
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 text-center">
            Cost Optimization
          </h3>
          <p class="text-gray-600 dark:text-gray-400 text-center leading-relaxed">
            Set budgets, per-plan caps, and automatic fallbacks to cheaper models—cut costs without sacrificing quality.
          </p>
        </div>

        <!-- Card 3: Usage Analytics -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
          <!-- Top highlight -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
          <!-- Bottom shadow border -->
          <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
          <!-- Analytics Chart Graphic -->
          <div class="flex justify-center mb-6">
            <div class="relative w-32 h-32 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center shadow-[inset_0_2px_4px_rgba(0,0,0,0.1)]">
              <!-- Chart Lines -->
              <svg class="w-24 h-24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 18L8 12L12 16L21 7" stroke="gray" stroke-width="2" fill="none"/>
                <circle cx="3" cy="18" r="2" fill="gray"/>
                <circle cx="8" cy="12" r="2" fill="gray"/>
                <circle cx="12" cy="16" r="2" fill="gray"/>
                <circle cx="21" cy="7" r="2" fill="gray"/>
              </svg>
            </div>
          </div>
          
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 text-center">
            Usage Analytics
          </h3>
          <p class="text-gray-600 dark:text-gray-400 text-center leading-relaxed">
            Track latency, spend, and accuracy by model. Get comprehensive insights into performance and costs.
          </p>
        </div>
      </div>

      <!-- Bottom Feature Tags (Five Pills) - Carousel -->
      <div class="relative overflow-hidden">
        <!-- Left fade gradient -->
        <div class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-50 dark:from-slate-900 to-transparent z-10 pointer-events-none"></div>
        <!-- Right fade gradient -->
        <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-50 dark:from-slate-900 to-transparent z-10 pointer-events-none"></div>
        <div class="flex gap-4 my-4 animate-scroll">
          <!-- First Set -->
          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Auto Model Routing</span>
          </div>

          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" stroke-width="2" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Cost Optimization</span>
          </div>

          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Usage Analytics</span>
          </div>

          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Multi-Model Support</span>
          </div>

          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Unified API</span>
          </div>

          <!-- Duplicate Set for Seamless Loop -->
          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Auto Model Routing</span>
          </div>

          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" stroke-width="2" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Cost Optimization</span>
          </div>

          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Usage Analytics</span>
          </div>

          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Multi-Model Support</span>
          </div>

          <div class="relative inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 rounded-full border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] whitespace-nowrap flex-shrink-0">
            <!-- Top highlight -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-full"></div>
            <svg class="relative w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="relative text-sm font-medium text-gray-700 dark:text-gray-300">Unified API</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
// No props or emits needed for this static section
</script>

