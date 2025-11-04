<template>
  <MarketingLayout>
    <div class="py-20 md:py-32">
      <div class="max-w-4xl mx-auto px-4 md:px-6">
        <!-- Header -->
        <div class="text-center mb-16">
          <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-slate-900 dark:text-white mb-6">
            About Blendable
          </h1>
          <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
            The AI model router that chooses the best AI model for every task—cut costs, boost quality, zero prompt changes.
          </p>
        </div>

        <!-- Mission Section -->
        <section class="mb-16">
          <div class="bg-white dark:bg-white/[0.04] rounded-2xl border border-slate-200 dark:border-white/10 p-8 md:p-12">
            <h2 class="text-3xl font-semibold text-slate-900 dark:text-white mb-6">Our Mission</h2>
            <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
              At Blendable, we believe that different AI models excel at different tasks. Instead of forcing you to 
              choose one model and stick with it, we've built an intelligent routing system that automatically selects 
              the best model for each request.
            </p>
            <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed">
              Our platform eliminates the guesswork, reduces costs, and improves quality—all while maintaining a 
              unified conversation experience. No more switching between tools, no more prompt rewrites, just better 
              results.
            </p>
          </div>
        </section>

        <!-- What We Do Section -->
        <section class="mb-16">
          <h2 class="text-3xl font-semibold text-slate-900 dark:text-white mb-8">What We Do</h2>
          <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-white/[0.04] rounded-xl border border-slate-200 dark:border-white/10 p-6">
              <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Intelligent Routing</h3>
              <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                Our AI model router analyzes each request and automatically selects the optimal model based on task complexity, 
                cost efficiency, and quality requirements.
              </p>
            </div>

            <div class="bg-white dark:bg-white/[0.04] rounded-xl border border-slate-200 dark:border-white/10 p-6">
              <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Cost Optimization</h3>
              <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                Route simple tasks to faster, cheaper models and reserve premium models for complex requests. 
                Reduce costs by up to 60% without sacrificing quality.
              </p>
            </div>

            <div class="bg-white dark:bg-white/[0.04] rounded-xl border border-slate-200 dark:border-white/10 p-6">
              <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Unified Experience</h3>
              <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                One interface, multiple models. Switch between GPT-4, Claude, Gemini, and more seamlessly—all within 
                the same conversation context.
              </p>
            </div>

            <div class="bg-white dark:bg-white/[0.04] rounded-xl border border-slate-200 dark:border-white/10 p-6">
              <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Enterprise Security</h3>
              <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                Built with security and privacy at the core. End-to-end encryption, SOC 2 compliant, and GDPR ready. 
                Your data stays private and secure.
              </p>
            </div>
          </div>
        </section>

        <!-- Why Blendable Section -->
        <section class="mb-16">
          <h2 class="text-3xl font-semibold text-slate-900 dark:text-white mb-8">Why Blendable?</h2>
          <div class="space-y-6">
            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0 w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center mt-1">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No Prompt Changes Required</h3>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                  Write your prompts once. Our router handles model selection automatically, so you don't need to 
                  rewrite prompts for different models.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0 w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center mt-1">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Zero Context Loss</h3>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                  Switch models mid-conversation without losing context. Our system maintains conversation history 
                  across all models seamlessly.
                </p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0 w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center mt-1">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Continuous Improvement</h3>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                  Our routing algorithms learn from your usage patterns and improve over time, ensuring optimal 
                  performance and cost efficiency.
                </p>
              </div>
            </div>
          </div>
        </section>

        <!-- Team Section -->
        <section class="mb-16">
          <h2 class="text-3xl font-semibold text-slate-900 dark:text-white mb-8">Built for Teams</h2>
          <div class="bg-white dark:bg-white/[0.04] rounded-2xl border border-slate-200 dark:border-white/10 p-8 md:p-12">
            <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
              Blendable is designed for teams of all sizes. From solo developers to enterprise organizations, 
              our platform scales with you.
            </p>
            <div class="grid md:grid-cols-3 gap-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">100%</div>
                <div class="text-slate-600 dark:text-slate-300">Uptime SLA</div>
              </div>
              <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">24/7</div>
                <div class="text-slate-600 dark:text-slate-300">Support</div>
              </div>
              <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">99.9%</div>
                <div class="text-slate-600 dark:text-slate-300">Reliability</div>
              </div>
            </div>
          </div>
        </section>

        <!-- CTA Section -->
        <section class="text-center">
          <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl p-8 md:p-12 text-white">
            <h2 class="text-3xl font-semibold mb-4">Ready to Get Started?</h2>
            <p class="text-lg mb-8 opacity-90">
              Join thousands of teams using Blendable to optimize their AI workflows.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <Link
                :href="route('register')"
                class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-medium hover:bg-slate-50 transition-colors"
              >
                Start Free Trial
              </Link>
              <Link
                :href="route('contact')"
                class="bg-white/20 backdrop-blur-sm text-white px-8 py-3 rounded-lg font-medium hover:bg-white/30 transition-colors border border-white/30"
              >
                Contact Sales
              </Link>
            </div>
          </div>
        </section>
      </div>
    </div>
  </MarketingLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import MarketingLayout from '@/Layouts/MarketingLayout.vue'
</script>

