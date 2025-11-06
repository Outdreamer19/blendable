<template>
  <MarketingLayout>
    <div class="py-20 md:py-32">
      <div class="max-w-4xl mx-auto px-4 md:px-6">
        <!-- Header -->
        <div class="text-center mb-16">
          <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-slate-900 dark:text-white mb-6">
            Get in Touch
          </h1>
          <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
            Have a question or need help? We're here to assist you. Send us a message and we'll get back to you as soon as possible.
          </p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
          <!-- Contact Information -->
          <div>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-6">Contact Information</h2>
            <div class="space-y-6">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Email</h3>
                  <a href="mailto:hello@blendable.com" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                    hello@blendable.com
                  </a>
                </div>
              </div>

              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Response Time</h3>
                  <p class="text-slate-600 dark:text-slate-300">We typically respond within 24-48 hours</p>
                </div>
              </div>

              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Support</h3>
                  <p class="text-slate-600 dark:text-slate-300">For technical support, please include your account email</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Form -->
          <div>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-6">Send us a Message</h2>
            
            <form @submit.prevent="submitForm" class="space-y-6">
              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Name <span class="text-red-500">*</span>
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                  placeholder="Your name"
                />
                <div v-if="form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                  {{ form.errors.name }}
                </div>
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Email <span class="text-red-500">*</span>
                </label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                  placeholder="your.email@example.com"
                />
                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                  {{ form.errors.email }}
                </div>
              </div>

              <!-- Subject -->
              <div>
                <label for="subject" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Subject <span class="text-red-500">*</span>
                </label>
                <input
                  id="subject"
                  v-model="form.subject"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                  placeholder="What is this regarding?"
                />
                <div v-if="form.errors.subject" class="mt-1 text-sm text-red-600 dark:text-red-400">
                  {{ form.errors.subject }}
                </div>
              </div>

              <!-- Message -->
              <div>
                <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  Message <span class="text-red-500">*</span>
                </label>
                <textarea
                  id="message"
                  v-model="form.message"
                  required
                  rows="6"
                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors resize-none"
                  placeholder="Tell us how we can help..."
                ></textarea>
                <div v-if="form.errors.message" class="mt-1 text-sm text-red-600 dark:text-red-400">
                  {{ form.errors.message }}
                </div>
              </div>

              <!-- Success Message -->
              <div v-if="showSuccess" class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <p class="text-sm font-medium text-green-800 dark:text-green-300">
                    Thank you! Your message has been sent. We'll get back to you soon.
                  </p>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white px-6 py-3 rounded-lg font-medium hover:opacity-90 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-blue-500/25"
              >
                <span v-if="form.processing">Sending...</span>
                <span v-else>Send Message</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </MarketingLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import MarketingLayout from '@/Layouts/MarketingLayout.vue'

const showSuccess = ref(false)

const form = useForm({
  name: '',
  email: '',
  subject: '',
  message: '',
})

const submitForm = () => {
  form.post(route('contact.submit'), {
    preserveScroll: true,
    onSuccess: () => {
      showSuccess.value = true
      form.reset()
      setTimeout(() => {
        showSuccess.value = false
      }, 5000)
    },
    onError: () => {
      // Errors are automatically displayed via form.errors
    },
  })
}
</script>

