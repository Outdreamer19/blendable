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
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
            ChatGPT vs Blendable
          </h1>
          <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
            See how Blendable compares to ChatGPT and why thousands of users are making the switch.
          </p>
        </div>
      </div>
    </div>

    <!-- Detailed Comparison -->
    <div class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <!-- AI Models -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">AI Models Available</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">GPT-3.5, GPT-4</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">GPT-4, Claude, Gemini, +</td>
                </tr>
                
                <!-- Model Switching -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Mid-Conversation Model Switching</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <X class="h-5 w-5 text-red-500 mx-auto" />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <Check class="h-5 w-5 text-green-500 mx-auto" />
                  </td>
                </tr>

                <!-- Auto-Routing -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Intelligent Auto-Routing</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <X class="h-5 w-5 text-red-500 mx-auto" />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <Check class="h-5 w-5 text-green-500 mx-auto" />
                  </td>
                </tr>

                <!-- Team Collaboration -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Team Workspaces</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Limited</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Advanced</td>
                </tr>

                <!-- Personas -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Custom Personas</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <X class="h-5 w-5 text-red-500 mx-auto" />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <Check class="h-5 w-5 text-green-500 mx-auto" />
                  </td>
                </tr>

                <!-- Knowledge Base -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Knowledge Base Integration</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Basic</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Advanced</td>
                </tr>

                <!-- Tools -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Built-in Tools</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Code Interpreter, Web Search</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Web Search, Files, Image Gen, +</td>
                </tr>

                <!-- Image Generation -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Image Generation</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <X class="h-5 w-5 text-red-500 mx-auto" />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <Check class="h-5 w-5 text-green-500 mx-auto" />
                  </td>
                </tr>

                <!-- Document Editor -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rich Document Editor</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Basic</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Advanced with Export</td>
                </tr>

                <!-- API Access -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">API Access</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Separate API</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Integrated</td>
                </tr>

                <!-- Usage Analytics -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Usage Analytics</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Basic</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Detailed</td>
                </tr>

                <!-- Pricing -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Pricing Transparency</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Complex</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Simple & Clear</td>
                </tr>

                <!-- Uptime -->
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Service Reliability</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">~95%</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">99.9%</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Key Advantages -->
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900">Why Choose Blendable?</h2>
        </div>
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
          <div class="space-y-8">
            <div class="flex items-start">
              <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                <Zap class="h-6 w-6 text-indigo-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Multi-Model Intelligence</h3>
                <p class="text-gray-600">Access the best AI models from different providers. Switch between GPT-4, Claude, and Gemini based on your specific needs.</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <Shield class="h-6 w-6 text-green-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Never Go Down</h3>
                <p class="text-gray-600">With multiple model providers and intelligent failover, your AI workflow never stops, even when individual services are down.</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                <Users class="h-6 w-6 text-purple-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Built for Teams</h3>
                <p class="text-gray-600">Advanced collaboration features, workspace management, and role-based access control designed for modern teams.</p>
              </div>
            </div>
          </div>
          <div class="space-y-8">
            <div class="flex items-start">
              <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <Brain class="h-6 w-6 text-blue-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Smart Personas</h3>
                <p class="text-gray-600">Create specialized AI assistants with custom knowledge bases, system prompts, and tool configurations for different use cases.</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                <BarChart3 class="h-6 w-6 text-yellow-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Usage Insights</h3>
                <p class="text-gray-600">Detailed analytics on token usage, model performance, and team productivity to optimize your AI spend and workflows.</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                <Wrench class="h-6 w-6 text-red-600" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Powerful Tools</h3>
                <p class="text-gray-600">Built-in tools for web search, file management, image generation, and prompt enhancement - all integrated seamlessly.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Testimonials -->
    <div class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900">What Our Users Say</h2>
        </div>
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "Switched from ChatGPT to Blendable and never looked back. The multi-model approach means I always get the best results for any task."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                <span class="text-indigo-600 font-semibold">SM</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Sarah Mitchell</div>
                <div class="text-sm text-gray-500">Product Manager</div>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "The team collaboration features are incredible. We can share workspaces, manage permissions, and track usage across our entire organization."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-green-600 font-semibold">DJ</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">David Johnson</div>
                <div class="text-sm text-gray-500">CTO</div>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <Star v-for="i in 5" :key="i" class="h-5 w-5 fill-current" />
              </div>
            </div>
            <p class="text-gray-600 mb-4">
              "Finally, an AI platform that doesn't lock you into one model. The auto-routing feature is brilliant - it picks the best model automatically."
            </p>
            <div class="flex items-center">
              <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                <span class="text-purple-600 font-semibold">EL</span>
              </div>
              <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">Emily Liu</div>
                <div class="text-sm text-gray-500">Data Scientist</div>
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
          Ready to make the switch?
        </h2>
        <p class="mt-4 text-xl text-indigo-200">
          Join thousands of users who have upgraded from ChatGPT to Blendable.
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
import { X, Check, Zap, Shield, Users, Brain, BarChart3, Wrench, Star } from 'lucide-vue-next'
</script>
