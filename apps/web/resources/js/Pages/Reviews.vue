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
    <div class="bg-gray-50 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
            What our customers say
          </h1>
          <p class="mt-4 text-xl text-gray-600">
            Join thousands of teams who trust OmniAI for their AI needs
          </p>
        </div>
      </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600">10,000+</div>
            <div class="text-gray-600">Active Users</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600">4.9/5</div>
            <div class="text-gray-600">Average Rating</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600">99.9%</div>
            <div class="text-gray-600">Uptime</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600">50M+</div>
            <div class="text-gray-600">Words Processed</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reviews Grid -->
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <!-- Review 1 -->
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "OmniAI has completely transformed how our team works with AI. The multi-model approach means we always get the best results, and the interface is incredibly intuitive."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                <span class="text-indigo-600 font-semibold">SM</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Sarah Mitchell</div>
                <div class="text-sm text-gray-500">Product Manager, TechCorp</div>
              </div>
            </div>
          </div>

          <!-- Review 2 -->
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "The persona system is a game-changer. We can create specialized AI assistants for different departments, and the knowledge base integration makes them incredibly smart."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-green-600 font-semibold">DJ</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">David Johnson</div>
                <div class="text-sm text-gray-500">CTO, StartupXYZ</div>
              </div>
            </div>
          </div>

          <!-- Review 3 -->
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "Finally, an AI platform that doesn't lock you into one model. The auto-routing feature is brilliant - it automatically picks the best model for each task."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                <span class="text-purple-600 font-semibold">EL</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Emily Liu</div>
                <div class="text-sm text-gray-500">Data Scientist, AI Labs</div>
              </div>
            </div>
          </div>

          <!-- Review 4 -->
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "The team collaboration features are outstanding. We can share workspaces, manage permissions, and track usage across our entire organization seamlessly."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-semibold">MR</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Michael Rodriguez</div>
                <div class="text-sm text-gray-500">VP Engineering, GlobalTech</div>
              </div>
            </div>
          </div>

          <!-- Review 5 -->
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "The image generation and document editing tools are incredibly powerful. We use them for everything from marketing materials to technical documentation."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-pink-100 rounded-full flex items-center justify-center">
                <span class="text-pink-600 font-semibold">AW</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Alexandra Wang</div>
                <div class="text-sm text-gray-500">Creative Director, DesignCo</div>
              </div>
            </div>
          </div>

          <!-- Review 6 -->
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "The pricing is transparent and fair. We know exactly what we're paying for, and the usage tracking helps us optimize our AI spend across the organization."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                <span class="text-yellow-600 font-semibold">TB</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Thomas Brown</div>
                <div class="text-sm text-gray-500">CFO, Enterprise Solutions</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white">
          Ready to join thousands of satisfied customers?
        </h2>
        <p class="mt-4 text-xl text-indigo-200">
          Start your free trial today and experience the power of multi-model AI.
        </p>
        <div class="mt-8">
          <Link :href="route('register')" class="bg-white text-indigo-600 px-8 py-3 rounded-md text-lg font-medium hover:bg-gray-100 transition duration-150 ease-in-out">
            Start Free Trial
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
          <Link :href="route('enterprise')" class="text-gray-400 hover:text-gray-500">
            Enterprise
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
import { Star } from 'lucide-vue-next'
</script>
