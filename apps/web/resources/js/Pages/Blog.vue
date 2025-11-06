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
            Blendable Blog
          </h1>
          <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
            Insights, tutorials, and updates on AI, productivity, and the future of work.
          </p>
        </div>
      </div>
    </div>

    <!-- Featured Post -->
    <div class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="md:flex">
            <div class="md:w-1/2">
              <div class="h-64 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                <span class="text-white text-6xl">🚀</span>
              </div>
            </div>
            <div class="md:w-1/2 p-8">
              <div class="flex items-center mb-4">
                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">Featured</span>
                <span class="ml-3 text-sm text-gray-500">March 15, 2024</span>
              </div>
              <h2 class="text-2xl font-bold text-gray-900 mb-4">
                The Future of Multi-Model AI: Why One Size Doesn't Fit All
              </h2>
              <p class="text-gray-600 mb-6">
                Explore how different AI models excel at different tasks and why the future belongs to platforms that can intelligently route requests to the most appropriate model.
              </p>
              <div class="flex items-center">
                <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                  <span class="text-indigo-600 font-semibold">AI</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-gray-900">AI Research Team</div>
                  <div class="text-sm text-gray-500">5 min read</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <!-- Post 1 -->
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-48 bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
              <span class="text-white text-4xl">💡</span>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-3">
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Tutorial</span>
                <span class="ml-3 text-sm text-gray-500">March 12, 2024</span>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                Building Custom AI Personas for Your Team
              </h3>
              <p class="text-gray-600 mb-4">
                Learn how to create specialized AI assistants that understand your team's unique workflows and knowledge base.
              </p>
              <div class="flex items-center">
                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                  <span class="text-green-600 font-semibold text-sm">JS</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-gray-900">Jane Smith</div>
                  <div class="text-sm text-gray-500">8 min read</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Post 2 -->
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-48 bg-gradient-to-r from-purple-400 to-pink-500 flex items-center justify-center">
              <span class="text-white text-4xl">🔧</span>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-3">
                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Product</span>
                <span class="ml-3 text-sm text-gray-500">March 10, 2024</span>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                Introducing Auto-Routing: Let AI Choose the Best Model
              </h3>
              <p class="text-gray-600 mb-4">
                Our new intelligent routing system automatically selects the optimal AI model for each task, maximizing performance and efficiency.
              </p>
              <div class="flex items-center">
                <div class="h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                  <span class="text-purple-600 font-semibold text-sm">MR</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-gray-900">Mike Rodriguez</div>
                  <div class="text-sm text-gray-500">6 min read</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Post 3 -->
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-48 bg-gradient-to-r from-yellow-400 to-orange-500 flex items-center justify-center">
              <span class="text-white text-4xl">📊</span>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-3">
                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Analytics</span>
                <span class="ml-3 text-sm text-gray-500">March 8, 2024</span>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                AI Usage Analytics: Optimizing Your Team's Productivity
              </h3>
              <p class="text-gray-600 mb-4">
                Discover how detailed usage analytics can help you understand your team's AI consumption patterns and optimize workflows.
              </p>
              <div class="flex items-center">
                <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
                  <span class="text-yellow-600 font-semibold text-sm">AW</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-gray-900">Alex Wang</div>
                  <div class="text-sm text-gray-500">7 min read</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Post 4 -->
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-48 bg-gradient-to-r from-red-400 to-pink-500 flex items-center justify-center">
              <span class="text-white text-4xl">🛡️</span>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-3">
                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Security</span>
                <span class="ml-3 text-sm text-gray-500">March 5, 2024</span>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                Enterprise Security: Protecting Your AI Workflows
              </h3>
              <p class="text-gray-600 mb-4">
                Learn about the security measures and compliance standards that protect your organization's AI operations and data.
              </p>
              <div class="flex items-center">
                <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                  <span class="text-red-600 font-semibold text-sm">TB</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-gray-900">Tom Brown</div>
                  <div class="text-sm text-gray-500">9 min read</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Post 5 -->
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-48 bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center">
              <span class="text-white text-4xl">🎨</span>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-3">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Creative</span>
                <span class="ml-3 text-sm text-gray-500">March 3, 2024</span>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                AI-Powered Image Generation: From Concept to Creation
              </h3>
              <p class="text-gray-600 mb-4">
                Explore the creative possibilities of AI image generation and how to craft effective prompts for stunning visual results.
              </p>
              <div class="flex items-center">
                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <span class="text-blue-600 font-semibold text-sm">EL</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-gray-900">Emma Lee</div>
                  <div class="text-sm text-gray-500">10 min read</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Post 6 -->
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="h-48 bg-gradient-to-r from-teal-400 to-green-500 flex items-center justify-center">
              <span class="text-white text-4xl">🚀</span>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-3">
                <span class="bg-teal-100 text-teal-800 text-xs font-semibold px-2.5 py-0.5 rounded">Updates</span>
                <span class="ml-3 text-sm text-gray-500">March 1, 2024</span>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                Blendable v2.0: What's New and What's Coming
              </h3>
              <p class="text-gray-600 mb-4">
                A comprehensive overview of our latest features, improvements, and roadmap for the future of multi-model AI platforms.
              </p>
              <div class="flex items-center">
                <div class="h-8 w-8 bg-teal-100 rounded-full flex items-center justify-center">
                  <span class="text-teal-600 font-semibold text-sm">SD</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-gray-900">Sarah Davis</div>
                  <div class="text-sm text-gray-500">12 min read</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Newsletter Signup -->
    <div class="bg-indigo-600 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white">
          Stay Updated
        </h2>
        <p class="mt-4 text-xl text-indigo-200">
          Get the latest AI insights, product updates, and tutorials delivered to your inbox.
        </p>
        <div class="mt-8 max-w-md mx-auto">
          <div class="flex">
            <input
              type="email"
              placeholder="Enter your email"
              class="flex-1 px-4 py-3 rounded-l-md border-0 focus:ring-2 focus:ring-indigo-500"
            />
            <button class="bg-indigo-800 text-white px-6 py-3 rounded-r-md hover:bg-indigo-700 transition duration-150 ease-in-out">
              Subscribe
            </button>
          </div>
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
</script>
