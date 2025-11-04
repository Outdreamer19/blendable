<template>
  <div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <Link :href="route('welcome')" class="flex-shrink-0">
              <h1 class="text-2xl font-bold text-indigo-600">OmniAI</h1>
            </Link>
          </div>
          <div class="flex items-center space-x-4">
            <Link :href="route('pricing')" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Pricing
            </Link>
            <Link :href="route('reviews')" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
              Reviews
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
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
            Product Updates
          </h1>
          <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
            Stay informed about the latest features, improvements, and enhancements to OmniAI.
          </p>
        </div>
      </div>
    </div>

    <!-- Updates Timeline -->
    <div class="py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-12">
          <!-- Update 1 -->
          <div class="relative">
            <div class="absolute left-4 top-4 h-4 w-4 bg-indigo-600 rounded-full"></div>
            <div class="ml-12">
              <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                  <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">New Feature</span>
                  <span class="ml-3 text-sm text-gray-500">March 15, 2024</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                  🚀 Auto-Routing Intelligence
                </h3>
                <p class="text-gray-600 mb-4">
                  Our new intelligent routing system automatically selects the optimal AI model for each task. The system analyzes your request type, complexity, and context to choose between GPT-4, Claude, Gemini, and other models for the best results.
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                  <h4 class="font-semibold text-gray-900 mb-2">What's New:</h4>
                  <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Intelligent model selection based on task type</li>
                    <li>• Performance optimization for different use cases</li>
                    <li>• Fallback mechanisms for high availability</li>
                    <li>• Usage analytics for model performance insights</li>
                  </ul>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <span>Released by Product Team</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Update 2 -->
          <div class="relative">
            <div class="absolute left-4 top-4 h-4 w-4 bg-green-600 rounded-full"></div>
            <div class="ml-12">
              <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                  <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Enhancement</span>
                  <span class="ml-3 text-sm text-gray-500">March 12, 2024</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                  🎨 Enhanced Image Generation
                </h3>
                <p class="text-gray-600 mb-4">
                  We've significantly improved our image generation capabilities with support for DALL-E 3, Stable Diffusion, and Midjourney. New features include batch generation, style presets, and advanced prompt optimization.
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                  <h4 class="font-semibold text-gray-900 mb-2">Improvements:</h4>
                  <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Support for DALL-E 3 with HD quality options</li>
                    <li>• Batch image generation for multiple variations</li>
                    <li>• Style presets for consistent branding</li>
                    <li>• Advanced prompt enhancement suggestions</li>
                  </ul>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <span>Released by Engineering Team</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Update 3 -->
          <div class="relative">
            <div class="absolute left-4 top-4 h-4 w-4 bg-purple-600 rounded-full"></div>
            <div class="ml-12">
              <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                  <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Security</span>
                  <span class="ml-3 text-sm text-gray-500">March 10, 2024</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                  🛡️ Advanced Security & Compliance
                </h3>
                <p class="text-gray-600 mb-4">
                  We've enhanced our security infrastructure with SOC 2 Type II certification, GDPR compliance improvements, and new enterprise-grade security features including SSO integration and advanced audit logging.
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                  <h4 class="font-semibold text-gray-900 mb-2">Security Updates:</h4>
                  <ul class="text-sm text-gray-600 space-y-1">
                    <li>• SOC 2 Type II certification achieved</li>
                    <li>• Enhanced GDPR compliance features</li>
                    <li>• SSO integration with major providers</li>
                    <li>• Comprehensive audit logging and monitoring</li>
                  </ul>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <span>Released by Security Team</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Update 4 -->
          <div class="relative">
            <div class="absolute left-4 top-4 h-4 w-4 bg-blue-600 rounded-full"></div>
            <div class="ml-12">
              <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                  <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Performance</span>
                  <span class="ml-3 text-sm text-gray-500">March 8, 2024</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                  ⚡ Performance Optimizations
                </h3>
                <p class="text-gray-600 mb-4">
                  We've implemented significant performance improvements across the platform, including faster response times, reduced latency, and optimized resource usage. These changes result in up to 40% faster AI responses.
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                  <h4 class="font-semibold text-gray-900 mb-2">Performance Gains:</h4>
                  <ul class="text-sm text-gray-600 space-y-1">
                    <li>• 40% faster AI response times</li>
                    <li>• Reduced latency for streaming responses</li>
                    <li>• Optimized memory usage and caching</li>
                    <li>• Improved concurrent request handling</li>
                  </ul>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <span>Released by Engineering Team</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Update 5 -->
          <div class="relative">
            <div class="absolute left-4 top-4 h-4 w-4 bg-yellow-600 rounded-full"></div>
            <div class="ml-12">
              <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                  <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">UI/UX</span>
                  <span class="ml-3 text-sm text-gray-500">March 5, 2024</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                  🎨 Redesigned Chat Interface
                </h3>
                <p class="text-gray-600 mb-4">
                  We've completely redesigned the chat interface with a focus on usability and productivity. The new design includes improved message formatting, better file handling, and enhanced mobile responsiveness.
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                  <h4 class="font-semibold text-gray-900 mb-2">UI Improvements:</h4>
                  <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Cleaner, more intuitive chat interface</li>
                    <li>• Improved message formatting and display</li>
                    <li>• Better file upload and preview capabilities</li>
                    <li>• Enhanced mobile and tablet experience</li>
                  </ul>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <span>Released by Design Team</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Update 6 -->
          <div class="relative">
            <div class="absolute left-4 top-4 h-4 w-4 bg-red-600 rounded-full"></div>
            <div class="ml-12">
              <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                  <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Integration</span>
                  <span class="ml-3 text-sm text-gray-500">March 3, 2024</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                  🔗 New API Integrations
                </h3>
                <p class="text-gray-600 mb-4">
                  We've expanded our API ecosystem with new integrations for popular productivity tools including Slack, Microsoft Teams, Notion, and Zapier. These integrations make it easier to incorporate AI into your existing workflows.
                </p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                  <h4 class="font-semibold text-gray-900 mb-2">New Integrations:</h4>
                  <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Slack bot for team collaboration</li>
                    <li>• Microsoft Teams app integration</li>
                    <li>• Notion workspace sync</li>
                    <li>• Zapier automation workflows</li>
                  </ul>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <span>Released by Integration Team</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Roadmap Preview -->
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900">What's Coming Next</h2>
          <p class="mt-4 text-xl text-gray-600">A preview of upcoming features and improvements</p>
        </div>
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
              <span class="text-indigo-600 text-2xl">🤖</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Custom Model Training</h3>
            <p class="text-gray-600 mb-4">
              Train custom AI models on your organization's data for specialized use cases and improved accuracy.
            </p>
            <div class="text-sm text-indigo-600 font-medium">Coming Q2 2024</div>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
              <span class="text-green-600 text-2xl">📱</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Mobile App</h3>
            <p class="text-gray-600 mb-4">
              Native iOS and Android apps with full feature parity and offline capabilities for on-the-go productivity.
            </p>
            <div class="text-sm text-green-600 font-medium">Coming Q2 2024</div>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
              <span class="text-purple-600 text-2xl">🌐</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Voice Interface</h3>
            <p class="text-gray-600 mb-4">
              Natural voice interactions with AI models, including real-time conversation and voice command processing.
            </p>
            <div class="text-sm text-purple-600 font-medium">Coming Q3 2024</div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white">
          Stay Updated
        </h2>
        <p class="mt-4 text-xl text-indigo-200">
          Get notified about new features, updates, and improvements as they're released.
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
            &copy; 2024 OmniAI. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>
