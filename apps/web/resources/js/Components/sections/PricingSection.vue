<template>
	<section class="py-20 md:py-32 bg-[#F9FAFB] dark:bg-slate-900">
		<div class="max-w-7xl mx-auto px-4 md:px-6">
			<!-- Section Header -->
			<div class="text-center mb-12">
				<!-- PRICING Tag -->
				<div
					class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-100 dark:bg-slate-800 rounded-full shadow-sm border border-gray-200 dark:border-slate-700 mb-4">
					<span
						class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">PRICING</span>
				</div>

				<!-- Main Title -->
				<h2 class="text-4xl md:text-5xl lg:text-6xl font-normal text-gray-900 dark:text-white mb-4">
					Simple Price For All
				</h2>

				<!-- Subtitle -->
				<p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-8">
					Choose the perfect plan for your AI routing needs. Scale as you grow.
				</p>

				<!-- Monthly/Yearly Toggle -->
				<div class="flex items-center justify-center gap-2 mb-12">
					<div class="relative inline-flex bg-gray-100 dark:bg-slate-800 rounded-2xl p-1 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)]">
						<!-- Top highlight -->
						<div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-lg"></div>
						<!-- Bottom shadow border -->
						<div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
						<button @click="isYearly = false" :class="[
							'px-6 py-2 rounded-2xl font-medium text-sm transition-all relative z-10',
							!isYearly
								? 'bg-gray-900 dark:bg-gray-700 text-white shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_4px_-1px_rgba(0,0,0,0.4),0_1px_2px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_2px_4px_-1px_rgba(0,0,0,0.6),0_1px_2px_-1px_rgba(0,0,0,0.45)]'
								: 'text-gray-700 dark:text-gray-300'
						]">
							Monthly
						</button>
						<button @click="isYearly = true" :class="[
							'px-6 py-2 rounded-2xl font-medium text-sm transition-all relative z-10',
							isYearly
								? 'bg-gray-900 dark:bg-gray-700 text-white shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_4px_-1px_rgba(0,0,0,0.4),0_1px_2px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_2px_4px_-1px_rgba(0,0,0,0.6),0_1px_2px_-1px_rgba(0,0,0,0.45)]'
								: 'text-gray-700 dark:text-gray-300'
						]">
							Yearly
						</button>
						<!-- 30% off badge positioned to the right of Yearly -->
						<span
							class="absolute -right-16 top-1/2 -translate-y-1/2 bg-gray-900 dark:bg-gray-700 text-white text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_4px_-1px_rgba(0,0,0,0.4),0_1px_2px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_2px_4px_-1px_rgba(0,0,0,0.6),0_1px_2px_-1px_rgba(0,0,0,0.45)]">
							30% off
						</span>
					</div>
				</div>
			</div>

			<!-- Pricing Cards -->
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
				<!-- Pro Card (Popular) -->
				<div
					class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
					<!-- Top highlight overlay - light from above -->
					<div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
					<!-- Bottom shadow border -->
					<div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300/50 to-transparent dark:via-slate-600/50"></div>
					<!-- Popular Badge -->
					<div class="absolute top-6 right-6 z-10">
						<span
							class="inline-flex items-center gap-1 bg-gray-900 dark:bg-gray-700 text-white text-xs font-semibold px-3 py-1 rounded-full">
							<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
								<path
									d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
							</svg>
							Popular
						</span>
					</div>

					<div class="relative mb-6">
						<h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Pro</h3>
						<div class="mb-3">
							<span class="text-5xl font-bold text-gray-900 dark:text-white">{{ getPrice('pro') }}</span>
							<span class="text-gray-500 dark:text-gray-400 ml-2 text-lg">/month</span>
						</div>
						<p class="text-gray-600 dark:text-gray-400 text-sm">
							For power users and teams who need advanced AI routing kkk
						</p>
					</div>

					<a
						:href="`/billing/checkout?plan=pro`"
						class="relative w-full mb-6 px-6 py-2 bg-gray-900 dark:bg-gray-700 text-white rounded-lg font-bold text-sm flex items-center justify-center gap-2 hover:bg-gray-800 dark:hover:bg-gray-600 transition-colors border border-gray-800/50 dark:border-gray-600/50 shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_2px_5px_-1px_rgba(0,0,0,0.5),0_1px_4px_-1px_rgba(0,0,0,0.4)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_2px_5px_-1px_rgba(0,0,0,0.75),0_1px_4px_-1px_rgba(0,0,0,0.6)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.1)_inset,0_-1px_0_0_rgba(0,0,0,0.4)_inset,0_3px_6px_-2px_rgba(0,0,0,0.55),0_2px_5px_-1px_rgba(0,0,0,0.45)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.05)_inset,0_-1px_0_0_rgba(0,0,0,0.5)_inset,0_3px_6px_-2px_rgba(0,0,0,0.85),0_2px_5px_-1px_rgba(0,0,0,0.65)]">
						<!-- Top highlight -->
						<div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/20 to-transparent opacity-40 dark:opacity-10 rounded-t-lg"></div>
						<span class="relative">Get Started</span>
						<svg class="w-4 h-4 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M13 7l5 5m0 0l-5 5m5-5H6" />
						</svg>
					</a>

					<ul class="space-y-3 border-t-2 border-dotted border-gray-300 dark:border-slate-700/50 pt-4">
						<li v-for="feature in plans.pro.features" :key="feature"
							class="flex items-start text-gray-600 dark:text-gray-400 text-sm">
							<svg class="w-5 h-5 text-gray-900 dark:text-gray-300 mr-3 flex-shrink-0 mt-0.5" fill="none"
								stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M5 13l4 4L19 7" />
							</svg>
							{{ feature }}
						</li>
					</ul>
				</div>

				<!-- Business Card -->
				<div
					class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
					<!-- Top highlight overlay - light from above -->
					<div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
					<!-- Bottom shadow border -->
					<div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300/50 to-transparent dark:via-slate-600/50"></div>
					<div class="relative mb-6">
						<h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Business</h3>
						<div class="mb-3">
							<span class="text-5xl font-bold text-gray-900 dark:text-white">{{ getPrice('business')
								}}</span>
							<span class="text-gray-500 dark:text-gray-400 ml-2 text-lg">/month</span>
						</div>
						<p class="text-gray-600 dark:text-gray-400 text-sm">
							For growing teams that need collaboration and advanced analytics
						</p>
					</div>

					<a
						:href="`/billing/checkout?plan=business`"
						class="relative w-full mb-6 px-6 py-2 bg-gray-100 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg font-bold text-sm flex items-center justify-center gap-2 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors border border-gray-200/50 dark:border-slate-600/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.15)_inset,0_2px_5px_-1px_rgba(0,0,0,0.4),0_1px_4px_-1px_rgba(0,0,0,0.3)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.35)_inset,0_2px_5px_-1px_rgba(0,0,0,0.65),0_1px_4px_-1px_rgba(0,0,0,0.5)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.15)_inset,0_3px_6px_-2px_rgba(0,0,0,0.45),0_2px_5px_-1px_rgba(0,0,0,0.35)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.35)_inset,0_3px_6px_-2px_rgba(0,0,0,0.75),0_2px_5px_-1px_rgba(0,0,0,0.55)]">
						<!-- Top highlight -->
						<div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-lg"></div>
						<span class="relative">Get Started</span>
						<svg class="w-4 h-4 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M13 7l5 5m0 0l-5 5m5-5H6" />
						</svg>
					</a>

					<ul class="space-y-3 border-t-2 border-dotted border-gray-300 dark:border-slate-700/50 pt-4">
						<li v-for="feature in plans.business.features" :key="feature"
							class="flex items-start text-gray-600 dark:text-gray-400 text-sm">
							<svg class="w-5 h-5 text-gray-900 dark:text-gray-300 mr-3 flex-shrink-0 mt-0.5" fill="none"
								stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M5 13l4 4L19 7" />
							</svg>
							{{ feature }}
						</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const isYearly = ref(false)

const plans = {
	pro: {
		monthly: 19,
		yearly: 13, // 30% off
		features: [
			'500k tokens per month',
			'Unlimited chats',
			'Full model roster (GPT-4o, Claude 3.5 Sonnet, Gemini Pro)',
			'Advanced smart routing',
			'File uploads',
			'Saved prompts library',
			'Priority queue processing',
			'Priority support'
		]
	},
	business: {
		monthly: 79,
		yearly: 55, // 30% off
		features: [
			'2M tokens per month (pooled)',
			'5 team seats included',
			'All AI models',
			'Team workspaces',
			'Shared memory & context',
			'Advanced usage analytics',
			'Role-based access control',
			'Dedicated support'
		]
	}
}

const getPrice = (plan: 'pro' | 'business'): string => {
	const price = isYearly.value ? plans[plan].yearly : plans[plan].monthly
	return `$${price.toLocaleString()}`
}
</script>
