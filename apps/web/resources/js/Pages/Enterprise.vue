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
            Enterprise-grade AI platform
          </h1>
          <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
            Scale your AI operations with advanced security, compliance, and customization options designed for large organizations.
          </p>
          <div class="mt-8">
            <Link :href="route('register')" class="bg-indigo-600 text-white px-8 py-3 rounded-md text-lg font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">
              Contact Sales
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Grid -->
    <div class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <!-- Security & Compliance -->
          <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
              <Shield class="h-6 w-6 text-indigo-600" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Security & Compliance</h3>
            <ul class="space-y-3 text-gray-600">
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                SOC 2 Type II certified
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                GDPR compliant
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                End-to-end encryption
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                SSO integration
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Audit logs
              </li>
            </ul>
          </div>

          <!-- Advanced RBAC -->
          <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
              <Users class="h-6 w-6 text-green-600" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Advanced RBAC</h3>
            <ul class="space-y-3 text-gray-600">
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Role-based permissions
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Team hierarchies
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Workspace isolation
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Resource quotas
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Admin controls
              </li>
            </ul>
          </div>

          <!-- Custom Integrations -->
          <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
              <Zap class="h-6 w-6 text-purple-600" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Custom Integrations</h3>
            <ul class="space-y-3 text-gray-600">
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                REST API access
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Webhook support
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Custom models
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                White-label options
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Dedicated support
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Enterprise Stats -->
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900">Trusted by enterprise teams</h2>
        </div>
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600">500+</div>
            <div class="text-gray-600">Enterprise Customers</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600">99.99%</div>
            <div class="text-gray-600">SLA Uptime</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600">24/7</div>
            <div class="text-gray-600">Support</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600">1B+</div>
            <div class="text-gray-600">API Calls/Month</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pricing Section -->
    <div class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900">Enterprise Pricing</h2>
          <p class="mt-4 text-xl text-gray-600">Custom pricing based on your organization's needs</p>
        </div>
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
          <div class="bg-white rounded-lg shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise</h3>
            <div class="mb-6">
              <span class="text-4xl font-bold text-gray-900">$199</span>
              <span class="text-gray-600">/month per user</span>
            </div>
            <ul class="space-y-4 text-gray-600">
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Unlimited words and API calls
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Advanced security features
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                SSO and RBAC
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Dedicated support
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Custom integrations
              </li>
            </ul>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Custom</h3>
            <div class="mb-6">
              <span class="text-4xl font-bold text-gray-900">Contact</span>
              <span class="text-gray-600"> us for pricing</span>
            </div>
            <ul class="space-y-4 text-gray-600">
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                On-premise deployment
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Custom model training
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                White-label solution
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                Professional services
              </li>
              <li class="flex items-center">
                <Check class="h-5 w-5 text-green-500 mr-3" />
                SLA guarantees
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-600 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white">
          Ready to scale your AI operations?
        </h2>
        <p class="mt-4 text-xl text-indigo-200">
          Contact our enterprise team to discuss your specific needs and get a custom quote.
        </p>
        <div class="mt-8">
          <Link :href="route('register')" class="bg-white text-indigo-600 px-8 py-3 rounded-md text-lg font-medium hover:bg-gray-100 transition duration-150 ease-in-out">
            Contact Sales
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
import { Shield, Users, Zap, Check } from 'lucide-vue-next'
</script>
