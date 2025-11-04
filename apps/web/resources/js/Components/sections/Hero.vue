<template>
	<section class="relative py-0 md:py-14 overflow-hidden bg-white dark:bg-slate-900">
		<!-- Background Pattern Overlay - Very Visible -->
		<div class="absolute inset-0 -z-20 pointer-events-none">
			<!-- Dot pattern - much more visible -->
			<div class="absolute inset-0 pattern-dots" :style="{
				backgroundImage: 'radial-gradient(circle at 4px 4px, rgba(99, 102, 241, 0.8) 3px, transparent 0)',
				backgroundSize: '35px 35px'
			}"></div>
			<!-- Grid pattern (light mode only) - much more visible -->
			<div class="absolute inset-0 dark:hidden pattern-grid" :style="{
				backgroundImage: 'linear-gradient(rgba(99, 102, 241, 0.4) 2px, transparent 2px), linear-gradient(90deg, rgba(99, 102, 241, 0.4) 2px, transparent 2px)',
				backgroundSize: '50px 50px'
			}"></div>
			<!-- Hexagon pattern (dark mode) - more visible -->
			<div class="absolute inset-0 hidden dark:block pattern-hex" :style="hexPatternStyle"></div>
		</div>

		<!-- Gradient Mesh Background - Less prominent so patterns show -->
		<div class="absolute inset-0 z-50 pointer-events-none">
			<div
				class="absolute inset-0 bg-gradient-to-br from-indigo-200/15 via-transparent to-blue-100/15 opacity-70 dark:opacity-0">
			</div>
			<div
				class="absolute inset-0 bg-gradient-to-tr from-transparent via-blue-200/15 to-cyan-100/15 opacity-70 dark:opacity-10">
			</div>
			<div
				class="absolute inset-0 bg-gradient-to-bl from-indigo-100/15 via-transparent to-blue-100/15 opacity-70 dark:opacity-0">
			</div>
			<!-- Additional color layers for richness -->
			<div class="absolute inset-0 bg-gradient-to-r from-emerald-500/8 via-transparent to-amber-100/8"></div>
		</div>

		<!-- Animated Gradient Blobs -->
		<div class="absolute inset-0 -z-10 overflow-hidden">
			<!-- Large gradient orbs -->
			<div
				class="absolute top-0 left-0 w-[600px] h-[600px] bg-gradient-to-br from-indigo-500/30 via-blue-500/20 to-transparent rounded-full blur-3xl animate-pulse-slow">
			</div>
			<div class="absolute bottom-0 right-0 w-[700px] h-[700px] bg-gradient-to-tl from-cyan-500/30 via-blue-500/20 to-transparent rounded-full blur-3xl animate-pulse-slow"
				style="animation-delay: 1s;"></div>
			<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-r from-cyan-400/25 via-indigo-400/20 to-blue-500/25 rounded-full blur-3xl animate-pulse-slow"
				style="animation-delay: 2s;"></div>

			<!-- Medium gradient blobs -->
			<div
				class="absolute top-1/4 right-1/4 w-96 h-96 bg-gradient-to-r from-emerald-400/20 via-teal-400/15 to-cyan-400/20 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-float">
			</div>
			<div class="absolute bottom-1/3 left-1/3 w-80 h-80 bg-gradient-to-r from-amber-400/20 via-orange-400/15 to-red-400/20 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-screen animate-float"
				style="animation-delay: 1.5s; animation-duration: 8s;"></div>

			<!-- Small accent dots with more colors -->
			<div class="absolute top-20 left-20 w-3 h-3 bg-indigo-500/50 rounded-full blur-sm animate-pulse"
				style="animation-delay: 0s;"></div>
			<div class="absolute top-40 right-32 w-4 h-4 bg-blue-500/50 rounded-full blur-sm animate-pulse"
				style="animation-delay: 1s;"></div>
			<div class="absolute bottom-32 left-1/4 w-3 h-3 bg-cyan-500/50 rounded-full blur-sm animate-pulse"
				style="animation-delay: 2s;"></div>
			<div class="absolute bottom-20 right-20 w-4 h-4 bg-cyan-500/50 rounded-full blur-sm animate-pulse"
				style="animation-delay: 0.5s;"></div>
			<div class="absolute top-1/3 right-1/4 w-2 h-2 bg-emerald-500/50 rounded-full blur-sm animate-pulse"
				style="animation-delay: 1.5s;"></div>
			<div class="absolute bottom-1/3 left-1/5 w-2 h-2 bg-amber-500/50 rounded-full blur-sm animate-pulse"
				style="animation-delay: 2.5s;"></div>

			<!-- Glowing orbs -->
			<div
				class="absolute top-1/2 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/30 to-cyan-500/30 rounded-full blur-2xl animate-float">
			</div>
			<div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-indigo-500/30 to-blue-500/30 rounded-full blur-2xl animate-float"
				style="animation-delay: 2s;"></div>
		</div>

		<!-- Geometric Shapes & Lines -->
		<div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
			<!-- Diagonal gradient lines - more visible -->
			<div
				class="absolute top-0 left-1/4 w-0.5 h-full bg-gradient-to-b from-transparent via-indigo-500/40 to-transparent transform -skew-y-12">
			</div>
			<div
				class="absolute top-0 right-1/4 w-0.5 h-full bg-gradient-to-b from-transparent via-blue-500/40 to-transparent transform skew-y-12">
			</div>
			<div
				class="absolute top-0 left-1/2 w-0.5 h-full bg-gradient-to-b from-transparent via-cyan-500/30 to-transparent">
			</div>

			<!-- Horizontal accent lines - more visible -->
			<div
				class="absolute top-1/4 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-indigo-500/30 to-transparent">
			</div>
			<div
				class="absolute bottom-1/4 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-blue-500/30 to-transparent">
			</div>

			<!-- Curved gradient arcs - more visible -->
			<svg class="absolute inset-0 w-full h-full opacity-50 dark:opacity-30 pointer-events-none"
				xmlns="http://www.w3.org/2000/svg">
				<defs>
					<linearGradient id="arcGradient1" x1="0%" y1="0%" x2="100%" y2="0%">
						<stop offset="0%" style="stop-color:rgb(99, 102, 241);stop-opacity:0.5" />
						<stop offset="100%" style="stop-color:rgb(59, 130, 246);stop-opacity:0.2" />
					</linearGradient>
					<linearGradient id="arcGradient2" x1="0%" y1="0%" x2="100%" y2="0%">
						<stop offset="0%" style="stop-color:rgb(59, 130, 246);stop-opacity:0.5" />
						<stop offset="100%" style="stop-color:rgb(6, 182, 212);stop-opacity:0.2" />
					</linearGradient>
				</defs>
				<path d="M 0,200 Q 300,100 600,200 T 1200,200" stroke="url(#arcGradient1)" stroke-width="2.5"
					fill="none" />
				<path d="M 0,400 Q 300,300 600,400 T 1200,400" stroke="url(#arcGradient2)" stroke-width="2.5"
					fill="none" />
			</svg>

			<!-- Diagonal hatch pattern - more visible -->
			<svg class="absolute inset-0 w-full h-full opacity-60 dark:opacity-40 pointer-events-none"
				xmlns="http://www.w3.org/2000/svg">
				<defs>
					<pattern id="diagonalHatch" width="50" height="50" patternTransform="rotate(45 0 0)"
						patternUnits="userSpaceOnUse">
						<line x1="0" y1="0" x2="0" y2="50" stroke="rgb(99, 102, 241)" stroke-width="2" opacity="0.6" />
					</pattern>
				</defs>
				<rect width="100%" height="100%" fill="url(#diagonalHatch)" />
			</svg>
		</div>

		<!-- Colorful accent rings - more visible -->
		<div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
			<div
				class="absolute top-1/4 left-1/3 w-64 h-64 rounded-full border-2 border-indigo-500/40 dark:border-indigo-400/50 animate-pulse-slow">
			</div>
			<div class="absolute bottom-1/4 right-1/3 w-72 h-72 rounded-full border-2 border-blue-500/40 dark:border-blue-400/50 animate-pulse-slow"
				style="animation-delay: 1s;"></div>
			<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 rounded-full border-2 border-cyan-500/35 dark:border-cyan-400/45 animate-pulse-slow"
				style="animation-delay: 2s;"></div>
		</div>

		<div class="max-w-6xl mx-auto px-4 md:px-6">
			<div class="flex flex-col items-center justify-center text-center">
				<!-- Text Content -->
				<div class="mb-12 md:mb-16">
					<h1
						class="text-4xl md:text-6xl lg:text-7xl font-normal tracking-tight text-slate-900 dark:text-white mb-6 leading-tight">
						Blend models. <br> Route every task to the right AI.
					</h1>
					<p
						class="text-xl font-light md:text-2xl text-slate-600 dark:text-slate-300 mb-10 max-w-3xl mx-auto leading-relaxed">
						Blendable chooses the best model for each request. Cutting costs, boosting quality, no prompt
						changes.
					</p>

					<!-- <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
						<MarketingButton :href="registerRoute" as="a" variant="primary">
							Start Free
						</MarketingButton>
						<MarketingButton href="#how-it-works" as="a" variant="ghost">
							See how it works
						</MarketingButton>
					</div> -->
				</div>

				<!-- Animated Circle - Below H1 -->
				<div class=" flex items-center justify-center">
					<div class="main">
						<div class="big-circle">
							<div class="icon-block">
								<div
									class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
									GPT
								</div>
							</div>
							<div class="icon-block">
								<div
									class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
									CL
								</div>
							</div>
							<div class="icon-block">
								<div
									class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
									GM
								</div>
							</div>
							<div class="icon-block">
								<div
									class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
									AI
								</div>
							</div>
						</div>
						<div class="circle">
							<div class="icon-block">
								<div
									class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
									LLM
								</div>
							</div>
							<div class="icon-block">
								<div
									class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
									API
								</div>
							</div>
							<div class="icon-block">
								<div
									class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
									AI
								</div>
							</div>
							<div class="icon-block">
								<div
									class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
									ML
								</div>
							</div>
						</div>
						<div class="center-logo">
							<div
								class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500 flex items-center justify-center shadow-2xl">
								<div
									class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
									<span class="text-2xl md:text-3xl font-bold text-white">B</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import MarketingButton from '@/Components/ui/MarketingButton.vue'

const page = usePage()

const registerRoute = computed(() => {
	return page.props.auth?.user ? '/dashboard' : '/register'
})

// Hexagon pattern SVG - more visible
const hexSvg = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="#9C92AC" fill-opacity="0.8"><path d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/></g></g></svg>'

const hexPatternStyle = computed(() => ({
	backgroundImage: `url('data:image/svg+xml;charset=utf-8,${encodeURIComponent(hexSvg)}')`
}))
</script>
