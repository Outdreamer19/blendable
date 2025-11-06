<template>
  <div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <Link :href="route('welcome')" class="flex-shrink-0">
              <h1 class="text-2xl font-bold text-indigo-600">Blendable</h1>
            </Link>
          </div>
          <div class="flex items-center space-x-4">
            <Link :href="route('pricing')" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Pricing
            </Link>
            <Link :href="route('login')" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Sign In
            </Link>
            <Link :href="route('register')" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium">
              Get Started
            </Link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-red-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <div class="h-16 w-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <AlertTriangle class="h-8 w-8 text-red-600" />
          </div>
          <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
            ChatGPT is down again?
          </h1>
          <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
            Don't let service outages interrupt your workflow. Blendable provides reliable access to multiple AI models with 99.9% uptime.
          </p>
        </div>
      </div>
    </div>

    <!-- Problem Section -->
    <div class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
          <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">The Problem with Single-Model Platforms</h2>
            <div class="space-y-6">
              <div class="flex items-start">
                <X class="h-6 w-6 text-red-500 mt-1 mr-3" />
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Service Outages</h3>
                  <p class="text-gray-600">When ChatGPT goes down, your entire workflow stops. No backup, no alternatives.</p>
                </div>
              </div>
              <div class="flex items-start">
                <X class="h-6 w-6 text-red-500 mt-1 mr-3" />
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Rate Limiting</h3>
                  <p class="text-gray-600">Hit usage limits? You're stuck waiting or paying premium prices for access.</p>
                </div>
              </div>
              <div class="flex items-start">
                <X class="h-6 w-6 text-red-500 mt-1 mr-3" />
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Limited Options</h3>
                  <p class="text-gray-600">One model, one approach. No ability to choose the best tool for each specific task.</p>
                </div>
              </div>
              <div class="flex items-start">
                <X class="h-6 w-6 text-red-500 mt-1 mr-3" />
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Vendor Lock-in</h3>
                  <p class="text-gray-600">Your data, workflows, and processes are tied to a single provider's platform.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 rounded-lg p-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Recent ChatGPT Outages</h3>
            <div class="space-y-4">
              <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-700">March 15, 2024</span>
                <span class="text-red-600 font-semibold">2.5 hours</span>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-700">February 28, 2024</span>
                <span class="text-red-600 font-semibold">1.8 hours</span>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-700">February 12, 2024</span>
                <span class="text-red-600 font-semibold">3.2 hours</span>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-700">January 25, 2024</span>
                <span class="text-red-600 font-semibold">1.5 hours</span>
              </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
              * Data from status.openai.com
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Solution Section -->
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900">The Blendable Solution</h2>
          <p class="mt-4 text-xl text-gray-600">Never be stuck again with our multi-model approach</p>
        </div>
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
              <CheckCircle class="h-6 w-6 text-green-600" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Multiple Models</h3>
            <p class="text-gray-600">
              Access OpenAI, Anthropic, Google, and more. If one model is down, seamlessly switch to another.
            </p>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
              <Zap class="h-6 w-6 text-blue-600" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Auto-Routing</h3>
            <p class="text-gray-600">
              Our intelligent router automatically selects the best available model for each task.
            </p>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
              <Shield class="h-6 w-6 text-purple-600" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">99.9% Uptime</h3>
            <p class="text-gray-600">
              Enterprise-grade reliability with redundant systems and failover mechanisms.
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Comparison Section -->
    <div class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900">ChatGPT vs Blendable</h2>
        </div>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feature</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ChatGPT</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Blendable</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">AI Models</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">GPT-4 only</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">OpenAI, Anthropic, Google, +</td>
                </tr>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Uptime</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">~95%</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">99.9%</td>
                </tr>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Failover</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <X class="h-5 w-5 text-red-500 mx-auto" />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <Check class="h-5 w-5 text-green-500 mx-auto" />
                  </td>
                </tr>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Team Collaboration</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Limited</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Advanced</td>
                </tr>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Custom Integrations</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <X class="h-5 w-5 text-red-500 mx-auto" />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <Check class="h-5 w-5 text-green-500 mx-auto" />
                  </td>
                </tr>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Usage Analytics</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Basic</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Advanced</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white">
          Don't let another outage interrupt your work
        </h2>
        <p class="mt-4 text-xl text-indigo-200">
          Switch to Blendable and never worry about AI service downtime again.
        </p>
        <div class="mt-8">
          <Link :href="route('register')" class="bg-white text-indigo-600 px-8 py-3 rounded-md text-lg font-medium hover:bg-gray-100 transition duration-150 ease-in-out">
            Get Started
          </Link>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
        <div class="flex justify-center space-x-6 md:order-2">
          <Link :href="route('welcome')" class="text-gray-400 hover:text-gray-500">
            Home
          </Link>
          <Link :href="route('pricing')" class="text-gray-400 hover:text-gray-500">
            Pricing
          </Link>
          <Link :href="route('reviews')" class="text-gray-400 hover:text-gray-500">
            Reviews
          </Link>
        </div>
        <div class="mt-8 md:mt-0 md:order-1">
          <p class="text-center text-base text-gray-400">
            &copy; 2024 Blendable. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { AlertTriangle, X, CheckCircle, Zap, Shield, Check } from 'lucide-vue-next'
</script>
