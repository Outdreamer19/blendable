<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { Check, Star, ArrowRight } from 'lucide-vue-next'
import { ref, computed } from 'vue'
import PublicNavbar from '@/Components/ui/PublicNavbar.vue'

const props = defineProps({
	plans: Array,
	currentPlan: String,
	isAuthenticated: Boolean
})

const isYearly = ref(false)

// Transform the existing plan data to include design properties
const styledPlans = computed(() => {
	return props.plans.map((plan, index) => {
		const descriptions = {
			'free': 'For individuals & new creators',
			'pro': 'For freelancers & small teams',
			'business': 'For growing teams & agencies'
		}

		const yearlyPrices = {
			'free': 0,
			'pro': 15, // 20% off from 19
			'business': 63 // 20% off from 79
		}

		const featureLabels = {
			'basic_chat': 'Basic chat functionality',
			'file_uploads': 'File uploads',
			'priority_queue': 'Priority queue',
			'saved_prompts': 'Saved prompts',
			'team_workspace': 'Team workspace',
			'shared_memory': 'Shared memory',
			'analytics': 'Analytics dashboard',
			'roles': 'Role management'
		}

		const displayFeatures = plan.features.map(feature => featureLabels[feature] || feature)

		return {
			...plan,
			description: descriptions[plan.key] || plan.name,
			yearlyPrice: yearlyPrices[plan.key] || plan.price,
			popular: plan.key === 'pro',
			cta: 'Get Started',
			ctaVariant: plan.key === 'pro' ? 'gradient' : 'outline',
			displayFeatures: displayFeatures
		}
	})
})

const testimonials = [
	{
		name: 'Emily Ray',
		role: 'UX Designer',
		content: 'This tool has completely transformed how our team collaborates. The real-time editing and seamless integrations make our process so much smoother!',
		avatar: 'ER'
	},
	{
		name: 'Sofia Delgado',
		role: 'Product Manager',
		content: 'The advanced prototyping tools have revolutionized our design workflow. We can now iterate faster than ever before.',
		avatar: 'SD'
	},
	{
		name: 'Ryan Chen',
		role: 'Creative Director',
		content: 'The collaboration features are game-changing. Our entire team can work together seamlessly, no matter where they are.',
		avatar: 'RC'
	}
]
</script>

<template>

	<Head title="Pricing — Omni-AI" />

	<div class="min-h-screen bg-white">
		<!-- Sophisticated Navbar -->
		<PublicNavbar />

		<!-- Hero Section -->
		<section class="relative px-6 py-16 mt-20">
			<div class="max-w-4xl mx-auto text-center">
				<h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 leading-tight">
					Flexible pricing plans
				</h1>

				<p class="text-lg text-slate-600 mb-8 max-w-2xl mx-auto">
					Choose a plan that grows with you. Start for free and upgrade anytime for more features and support
				</p>

				<!-- Billing Toggle -->
				<div class="flex items-center justify-center gap-4 mb-6">
					<span class="text-slate-700 font-medium">Monthly</span>
					<button @click="isYearly = !isYearly"
						class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
						:class="isYearly ? 'bg-purple-500' : 'bg-slate-300'">
						<span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
							:class="isYearly ? 'translate-x-6' : 'translate-x-1'"></span>
					</button>
					<span class="text-slate-600 font-medium">
						Yearly
						<span class="text-green-600 font-semibold">20% off</span>
					</span>
				</div>
			</div>
		</section>

		<!-- Pricing Cards -->
		<section class="px-0 pb-24">
			<div class="max-w-7xl mx-auto">
				<div class="grid md:grid-cols-3 gap-8">
					<div v-for="plan in styledPlans" :key="plan.key"
						class="relative bg-gray-200/80 rounded-2xl border border-slate-200 p-1 transition-all duration-300 hover:shadow-lg"
						:class="plan.popular ? 'border-purple-200 shadow-lg ring-1 ring-purple-100' : 'hover:border-slate-300'">
						<div class="">
							<div class="text-center bg-white p-4 rounded-2xl mb-4">
								<div class="flex justify-between">
									<div class="text-left p-2">
										<h3 class="text-xl font-bold text-slate-900 mb-2">{{ plan.name }}</h3>
									<p class="text-slate-600 mb-6 text-sm">{{ plan.description }}</p>
									</div>

									<div class="mb-8">
										<div class="flex items-baseline justify-center">
											<span class="text-5xl font-bold text-slate-900">
												${{ isYearly ? plan.yearlyPrice : plan.price }}
											</span>
											<span class="text-slate-500 ml-1 text-sm">/month</span>
										</div>
										<div v-if="isYearly && plan.price > 0"
											class="text-xs text-green-600 font-medium mt-1">
											Save ${{ (plan.price - plan.yearlyPrice) * 12 }} per year
										</div>
									</div>
								</div>
								<Link 
									:href="isAuthenticated ? '/billing' : '/register'"
									class="w-full py-3 px-6 rounded-lg font-semibold transition-all duration-200 mb-6 inline-block text-center"
									:class="plan.ctaVariant === 'gradient'
										? 'bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white hover:from-purple-600 hover:to-purple-700 shadow-md'
										: 'bg-slate-600 text-slate-100 hover:bg-slate-200 border border-slate-200'">
									{{ plan.cta }}
								</Link>
							</div>
							<div class="text-left p-4">
								<h4 class="text-sm font-semibold text-slate-600 mb-4">Included features:</h4>
								<ul class="space-y-3">
									<li v-for="feature in plan.displayFeatures" :key="feature"
										class="flex items-start gap-4">
										<div class="w-1.5 h-1.5 bg-slate-900 rounded-full mt-2 flex-shrink-0"></div>
										<span class="text-slate-900 text-sm leading-tight font-medium">{{ feature
										}}</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Testimonials Section -->
		<section class="px-6 py-20 bg-slate-50">
			<div class="max-w-6xl mx-auto">
				<div class="text-center mb-12">
					<h2 class="text-3xl font-bold text-slate-900 mb-4">Loved by designers & teams</h2>
				</div>

				<div class="grid md:grid-cols-3 gap-6">
					<div v-for="testimonial in testimonials" :key="testimonial.name"
						class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
						<div class="flex items-center gap-3 mb-4">
							<div
								class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
								{{ testimonial.avatar }}
							</div>
							<div>
								<h4 class="font-semibold text-slate-900 text-sm">{{ testimonial.name }}</h4>
								<p class="text-slate-600 text-xs">{{ testimonial.role }}</p>
							</div>
						</div>
						<p class="text-slate-600 text-sm italic">"{{ testimonial.content }}"</p>
					</div>
				</div>
			</div>
		</section>

		<!-- CTA Section -->
		<section class="px-6 py-24">
			<div class="max-w-4xl mx-auto text-center">
				<h2 class="text-4xl font-bold text-slate-900 mb-6">
					Take your creative workflow to the next level
				</h2>
				<p class="text-xl text-slate-600 mb-8">
					Supercharge your workflow with powerful design tools and effortless collaboration—perfect for
					freelancers and teams.
				</p>
				<div class="flex flex-col sm:flex-row gap-4 justify-center">
					<Link 
						:href="isAuthenticated ? '/billing' : '/register'"
						class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white px-8 py-4 rounded-xl font-semibold hover:opacity-90 transition-all duration-200 shadow-lg shadow-blue-500/25">
						Get Started
						<ArrowRight class="w-5 h-5" />
					</Link>
					<Link href="/contact"
						class="inline-flex items-center gap-2 bg-slate-100 text-slate-900 px-8 py-4 rounded-xl font-semibold hover:bg-slate-200 transition-all duration-200 border border-slate-200">
						Contact Sales
					</Link>
				</div>
			</div>
		</section>

		<!-- Footer -->
		<footer class="bg-slate-900 text-white py-16">
			<div class="max-w-6xl mx-auto px-6">
				<div class="grid md:grid-cols-4 gap-8">
					<div>
						<h3 class="text-lg font-semibold mb-4">Quick Links</h3>
						<ul class="space-y-2">
							<li>
								<Link href="/" class="text-slate-300 hover:text-white transition-colors">Home</Link>
							</li>
							<li>
								<Link href="/features" class="text-slate-300 hover:text-white transition-colors">
								Features</Link>
							</li>
							<li>
								<Link href="/pricing" class="text-slate-300 hover:text-white transition-colors">Pricing
								</Link>
							</li>
							<li>
								<Link href="/contact" class="text-slate-300 hover:text-white transition-colors">Contact
								</Link>
							</li>
						</ul>
					</div>
					<div>
						<h3 class="text-lg font-semibold mb-4">Company</h3>
						<ul class="space-y-2">
							<li>
								<Link href="/about" class="text-slate-300 hover:text-white transition-colors">About us
								</Link>
							</li>
							<li>
								<Link href="/blog" class="text-slate-300 hover:text-white transition-colors">Blog</Link>
							</li>
							<li>
								<Link href="/careers" class="text-slate-300 hover:text-white transition-colors">Careers
								</Link>
							</li>
						</ul>
					</div>
					<div>
						<h3 class="text-lg font-semibold mb-4">Support</h3>
						<ul class="space-y-2">
							<li>
								<Link href="/help" class="text-slate-300 hover:text-white transition-colors">Help Center
								</Link>
							</li>
							<li>
								<Link href="/docs" class="text-slate-300 hover:text-white transition-colors">
								Documentation</Link>
							</li>
							<li>
								<Link href="/status" class="text-slate-300 hover:text-white transition-colors">Status
								</Link>
							</li>
						</ul>
					</div>
					<div>
						<h3 class="text-lg font-semibold mb-4">Legal</h3>
						<ul class="space-y-2">
							<li>
								<Link href="/privacy" class="text-slate-300 hover:text-white transition-colors">Privacy
								Policy</Link>
							</li>
							<li>
								<Link href="/terms" class="text-slate-300 hover:text-white transition-colors">Terms of
								Service</Link>
							</li>
							<li>
								<Link href="/cookies" class="text-slate-300 hover:text-white transition-colors">Cookie
								Policy</Link>
							</li>
						</ul>
					</div>
				</div>
				<div class="border-t border-slate-800 mt-12 pt-8 text-center">
					<p class="text-slate-400">© 2024 Omni-AI. All rights reserved.</p>
				</div>
			</div>
		</footer>
	</div>
</template>