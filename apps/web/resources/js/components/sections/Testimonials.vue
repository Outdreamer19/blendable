<template>
	<section class="py-20 md:py-32 bg-[#F9FAFB] dark:bg-slate-900">
		<div class="max-w-7xl mx-auto px-4 md:px-6">
			<!-- Header Section -->
			<div class="text-center mb-16">
				<!-- CUSTOMERS Tag -->
				<div
					class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#F9FAFB] dark:bg-slate-800 rounded-full shadow-sm border border-gray-200 dark:border-slate-700 mb-4">
					<svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd"
							d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
							clip-rule="evenodd" />
					</svg>
					<span
						class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">CUSTOMERS</span>
				</div>

				<!-- Main Title -->
				<h2 class="text-4xl md:text-5xl lg:text-6xl font-normal text-gray-900 dark:text-white mb-4">
					What Our Clients Say
				</h2>

				<!-- Subtitle -->
				<p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
					Join customers who trust AI to transform their business.
				</p>
			</div>

      <!-- Main Testimonial Section (Two Large Cards) -->
      <div class="grid grid-cols-1 md:grid-cols-[3fr_2fr] gap-8 mb-16">
				<!-- Left Card: Text Testimonial -->
				<div
					class="relative dark:bg-slate-800 rounded-2xl p-8 md:p-14 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
					<!-- Top highlight -->
					<div
						class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl">
					</div>
					<!-- Bottom shadow border -->
					<div
						class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50">
					</div>

					<div class="relative z-10">
						<p class="text-lg font-medium text-center md:text-2xl text-gray-950 dark:text-white leading-relaxed mb-8">
							Blendable's <span class="text-gray-400 dark:text-gray-400">smart routing</span> cut our AI
							costs by <span class="text-gray-400 dark:text-gray-400">40%</span> while improving response
							quality—automatically selecting the best model for each task and <span
								class="text-gray-400 dark:text-gray-400">delivering results</span> we couldn't achieve
							with a single provider.
						</p>
						<div class="text-center">
							<span
								class="text-4xl md:text-6xl text-gray-900 dark:text-white font-serif leading-none">"</span>
						</div>
					</div>
				</div>

				<!-- Right Card: Visual Testimonial -->
				<div
					class="relative bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
					<!-- Top highlight -->
					<div
						class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl z-10">
					</div>
					<!-- Bottom shadow border -->
					<div
						class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50">
					</div>

					<!-- Image Placeholder -->
					<div
						class="relative w-full h-full min-h-[200] bg-[#F9FAFB] dark:bg-slate-100 flex items-center justify-center overflow-hidden rounded-2xl">
						<div
							class="absolute inset-0 bg-[#F9FAFB] dark:bg-slate-800">
						</div>
						<!-- Woman in suit illustration placeholder -->
						<div
							class="relative z-10 w-40 h-52 bg-[#F9FAFB] dark:from-slate-100 dark:to-slate-100 rounded-2xl flex items-center justify-center shadow-xl">
							<div class="text-center">
								<div
									class="w-28 h-36 bg-gradient-to-br from-amber-100 to-yellow-100 dark:from-slate-600 dark:to-slate-600 rounded-xl mb-4 mx-auto flex items-center justify-center">
									<svg class="w-16 h-16 text-amber-500 dark:text-amber-50" fill="none"
										stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
											d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
									</svg>
								</div>
							</div>
						</div>
						<!-- Blurred phone hands around -->
						<div
							class="absolute top-8 left-8 w-16 h-16 bg-gray-400/30 dark:bg-gray-600/30 rounded-lg blur-md">
						</div>
						<div
							class="absolute top-16 right-12 w-12 h-12 bg-gray-400/30 dark:bg-gray-600/30 rounded-lg blur-md">
						</div>
						<div
							class="absolute bottom-12 left-12 w-14 h-14 bg-gray-400/30 dark:bg-gray-600/30 rounded-lg blur-md">
						</div>
						<div
							class="absolute bottom-8 right-8 w-16 h-16 bg-gray-400/30 dark:bg-gray-600/30 rounded-lg blur-md">
						</div>
					</div>
				</div>
			</div>

			<!-- Individual Testimonial Section (Three Smaller Cards) -->
			<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
				<div v-for="testimonial in testimonials" :key="testimonial.author"
					class="relative bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_6px_6px_-3px_rgba(0,0,0,0.3),0_3px_3px_-2px_rgba(0,0,0,0.18)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_6px_6px_-3px_rgba(0,0,0,0.6),0_3px_3px_-2px_rgba(0,0,0,0.4)] transition-shadow">
					<!-- Top highlight -->
					<div
						class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl">
					</div>
					<!-- Bottom shadow border -->
					<div
						class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50">
					</div>

					<div class="relative z-10">
						<!-- Stars -->
						<div class="flex gap-1 mb-4">
							<svg v-for="i in 4" :key="i" class="w-5 h-5 text-gray-950 dark:text-white fill-current"
								fill="currentColor" viewBox="0 0 20 20">
								<path
									d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
							</svg>
							<svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
								viewBox="0 0 20 20">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
							</svg>
						</div>

						<!-- Quote -->
						<p class="text-gray-900 dark:text-gray-300 mb-4 leading-relaxed text-sm">
							{{ testimonial.quote }}
						</p>

						<!-- Author Info -->
						<div class="flex items-center gap-3">
							<div
								class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-200 font-semibold text-sm flex-shrink-0">
								{{ testimonial.avatar }}
							</div>
							<div>
								<div class="font-semibold text-gray-900 dark:text-white text-sm">{{ testimonial.author
									}}</div>
								<div class="text-xs text-gray-600 dark:text-gray-400">{{ testimonial.role }}</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Statistics Section -->
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
				<div class="border-r-2 border-gray-200 border-dashed dark:border-slate-700">
					<div class="text-3xl md:text-4xl font-normal text-gray-900 dark:text-white mb-2">{{ modelsCount }}+
					</div>
					<div class="text-lg text-gray-600 dark:text-gray-400">AI Models Supported</div>
				</div>
				<div class="border-r-2 border-gray-200 border-dashed dark:border-slate-700">
					<div class="text-3xl md:text-4xl font-normal text-gray-900 dark:text-white mb-2">{{
						satisfactionCount }}%</div>
					<div class="text-lg text-gray-600 dark:text-gray-400">Client Satisfaction</div>
				</div>
				<div>
					<div class="text-3xl md:text-4xl font-normal text-gray-900 dark:text-white mb-2">{{ requestsCount
						}}M+</div>
					<div class="text-lg text-gray-600 dark:text-gray-400">API Requests Processed</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const testimonials = [
	{
		quote: 'We needed intelligent model routing — and Blendable nailed it. The auto-router selects the best model for each prompt, cutting costs while maintaining quality. Zero prompt changes required.',
		author: 'Sarah Chen',
		role: 'CTO at TechCorp',
		avatar: 'SC',
	},
	{
		quote: 'Blendable helped us optimize our AI spend across GPT-4, Claude, and Gemini. The analytics dashboard shows exactly where we\'re saving money without sacrificing performance.',
		author: 'Marcus Johnson',
		role: 'Lead Engineer at StartupX',
		avatar: 'MJ',
	},
	{
		quote: 'From cost optimization to multi-model support, Blendable transformed our AI workflow. The unified API makes switching between providers seamless.',
		author: 'Emma Williams',
		role: 'Product Manager at ScaleUp',
		avatar: 'EW',
	},
]

// Stats animation
const modelsCount = ref(0)
const satisfactionCount = ref(0)
const requestsCount = ref(0)

const animateCount = (target: number, duration: number, callback: (value: number) => void): void => {
	const startTime = performance.now()
	const startValue = 1
	const endValue = target

	const animate = (currentTime: number): void => {
		const elapsed = currentTime - startTime
		const progress = Math.min(elapsed / duration, 1)

		// Easing function for smooth animation
		const easeOutQuart = 1 - Math.pow(1 - progress, 4)
		const currentValue = Math.floor(startValue + (endValue - startValue) * easeOutQuart)

		callback(currentValue)

		if (progress < 1) {
			requestAnimationFrame(animate)
		}
	}

	requestAnimationFrame(animate)
}

onMounted(() => {
	// Start animations after a short delay
	setTimeout(() => {
		animateCount(10, 2000, (value) => { modelsCount.value = value })
		animateCount(95, 2000, (value) => { satisfactionCount.value = value })
		animateCount(50, 2000, (value) => { requestsCount.value = value })
	}, 300)
})
</script>
