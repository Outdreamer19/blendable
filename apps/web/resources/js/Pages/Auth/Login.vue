<template>
  <MarketingLayout>
    <Head title="Sign In" />
    
    <section class="min-h-screen py-12 md:py-20 bg-[#F5F5F5] dark:bg-slate-950">
      <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
          <!-- Left Side: Login Form -->
          <div class="w-full">
            <div class="text-center lg:text-left mb-8">
              <h1 class="text-4xl md:text-5xl font-normal text-gray-900 dark:text-white mb-4">
                Welcome Back
              </h1>
              <p class="text-lg text-gray-600 dark:text-gray-400">
                Sign in to continue routing AI models intelligently.
              </p>
            </div>

            <!-- Login Card -->
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 md:p-10 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)]">
              <!-- Top highlight -->
              <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
              <!-- Bottom shadow border -->
              <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
              
              <div class="relative z-10">
                <!-- Google OAuth Button -->
                <a
                  v-if="hasGoogleRoute"
                  :href="route('auth.google.redirect')"
                  class="relative w-full mb-6 px-6 py-3 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg font-medium text-sm flex items-center justify-center gap-3 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors border border-gray-200/50 dark:border-slate-600/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_2px_4px_-1px_rgba(0,0,0,0.15),0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_2px_4px_-1px_rgba(0,0,0,0.3),0_1px_2px_-1px_rgba(0,0,0,0.2)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_3px_5px_-1px_rgba(0,0,0,0.2),0_2px_3px_-1px_rgba(0,0,0,0.15)] dark:hover:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_3px_5px_-1px_rgba(0,0,0,0.4),0_2px_3px_-1px_rgba(0,0,0,0.3)]"
                >
                  <!-- Top highlight -->
                  <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-b from-white/80 to-transparent opacity-60 dark:opacity-10 rounded-t-lg"></div>
                  <svg viewBox="0 0 48 48" class="w-5 h-5 relative">
                    <path
                      fill="#FFC107"
                      d="M43.6 20.5H42V20H24v8h11.3C33.7 31.7 29.3 35 24 35c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 5.1 29.6 3 24 3 12.4 3 3 12.4 3 24s9.4 21 21 21c10.5 0 19.3-7.6 21-17.5v-7z"
                    />
                    <path
                      fill="#FF3D00"
                      d="M6.3 14.7l6.6 4.8C14.4 16.2 18.8 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 5.1 29.6 3 24 3 16 3 9.2 7.6 6.3 14.7z"
                    />
                    <path
                      fill="#4CAF50"
                      d="M24 45c5.2 0 10-2 13.6-5.4l-6.3-5.3C29 35.4 26.7 36 24 36c-5.2 0-9.6-3.4-11.3-8.1l-6.5 5C9.2 40.4 16 45 24 45z"
                    />
                    <path
                      fill="#1976D2"
                      d="M45 24c0-1.3-.1-2.7-.4-4H24v8h11.3C33.7 31.7 29.3 35 24 35v10c10.5 0 19.3-7.6 21-17.5z"
                    />
                  </svg>
                  <span class="relative">Continue with Google</span>
                </a>

                <!-- Divider (only show if Google OAuth is available) -->
                <div v-if="hasGoogleRoute" class="relative mb-6">
                  <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-slate-600"></div>
                  </div>
                  <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white dark:bg-slate-800 text-gray-500 dark:text-gray-400">or</span>
                  </div>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit">
                  <div class="space-y-5">
                    <!-- Email Field -->
                    <div>
                      <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                      </label>
                      <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="email"
                        class="relative w-full px-4 py-3 bg-white dark:bg-slate-700 border border-gray-200/50 dark:border-slate-600/50 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_1px_2px_-1px_rgba(0,0,0,0.2)]"
                      />
                      <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password Field -->
                    <div>
                      <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                          Password
                        </label>
                        <Link
                          :href="route('password.request')"
                          class="text-sm font-medium text-gray-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300"
                        >
                          Forgot password?
                        </Link>
                      </div>
                      <div class="relative">
                        <input
                          id="password"
                          :type="showPassword ? 'text' : 'password'"
                          v-model="form.password"
                          required
                          autocomplete="current-password"
                          class="relative w-full px-4 py-3 pr-12 bg-white dark:bg-slate-700 border border-gray-200/50 dark:border-slate-600/50 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_1px_2px_-1px_rgba(0,0,0,0.1)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_1px_2px_-1px_rgba(0,0,0,0.2)]"
                        />
                        <button
                          type="button"
                          @click="showPassword = !showPassword"
                          class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                          tabindex="-1"
                        >
                          <Eye v-if="!showPassword" class="w-5 h-5" />
                          <EyeOff v-else class="w-5 h-5" />
                        </button>
                      </div>
                      <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                      <input
                        id="remember"
                        type="checkbox"
                        v-model="form.remember"
                        class="w-4 h-4 text-gray-600 bg-[#F9FAFB] border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                      />
                      <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        Remember me
                      </label>
                    </div>

                    <!-- Submit Button -->
                    <MarketingButton
                      type="submit"
                      variant="ghost"
                      :disabled="form.processing"
                      :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                      class="w-full"
                    >
                      <span v-if="form.processing">Signing in...</span>
                      <span v-else>Sign In</span>
                    </MarketingButton>

                    <!-- Register Link -->
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                      Don't have an account?
                      <Link
                        :href="route('register')"
                        class="font-medium text-indigo-60 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 underline underline-offset-4"
                      >
                        Create account
                      </Link>
                    </p>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Right Side: Testimonials Carousel -->
          <div class="hidden lg:block">
            <div class="relative">
              <!-- Testimonials Card -->
              <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-8 md:p-10 border border-gray-200/50 dark:border-slate-700/50 shadow-[0_1px_0_0_rgba(255,255,255,0.9)_inset,0_-1px_0_0_rgba(0,0,0,0.05)_inset,0_4px_4px_-2px_rgba(0,0,0,0.25),0_2px_2px_-1px_rgba(0,0,0,0.15)] dark:shadow-[0_1px_0_0_rgba(255,255,255,0.08)_inset,0_-1px_0_0_rgba(0,0,0,0.2)_inset,0_4px_4px_-2px_rgba(0,0,0,0.5),0_2px_2px_-1px_rgba(0,0,0,0.35)]">
                <!-- Top highlight -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-b from-white via-white/80 to-transparent opacity-80 dark:opacity-15 rounded-t-2xl"></div>
                <!-- Bottom shadow border -->
                <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent dark:via-slate-600/50"></div>
                
                <div class="relative z-10">
                  <h2 class="text-2xl font-normal text-gray-900 dark:text-white mb-8 text-center">
                    Trusted by Developers
                  </h2>
                  
                  <!-- Testimonials Carousel Container -->
                  <div class="relative overflow-hidden h-[400px]">
                    <div class="flex flex-col gap-6 animate-scroll-vertical">
                      <!-- First Set -->
                      <div v-for="testimonial in testimonials" :key="testimonial.id" class="flex-shrink-0">
                        <div class="relative bg-gray-50 dark:bg-slate-700/50 rounded-xl p-6 border border-gray-200/50 dark:border-slate-600/50">
                          <!-- Quote -->
                          <div class="mb-4">
                            <svg class="w-8 h-8 text-indigo-500 dark:text-indigo-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.996 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                            </svg>
                          </div>
                          <p class="text-gray-700 dark:text-gray-300 mb-4 leading-relaxed">
                            {{ testimonial.quote }}
                          </p>
                          <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-200 font-semibold text-sm">
                              {{ testimonial.avatar }}
                            </div>
                            <div>
                              <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ testimonial.author }}</div>
                              <div class="text-xs text-gray-600 dark:text-gray-400">{{ testimonial.role }}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Duplicate Set for Seamless Loop -->
                      <div v-for="testimonial in testimonials" :key="`duplicate-${testimonial.id}`" class="flex-shrink-0">
                        <div class="relative bg-gray-50 dark:bg-slate-700/50 rounded-xl p-6 border border-gray-200/50 dark:border-slate-600/50">
                          <div class="mb-4">
                            <svg class="w-8 h-8 text-indigo-500 dark:text-indigo-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.996 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                            </svg>
                          </div>
                          <p class="text-gray-700 dark:text-gray-300 mb-4 leading-relaxed">
                            {{ testimonial.quote }}
                          </p>
                          <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-200 font-semibold text-sm">
                              {{ testimonial.avatar }}
                            </div>
                            <div>
                              <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ testimonial.author }}</div>
                              <div class="text-xs text-gray-600 dark:text-gray-400">{{ testimonial.role }}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </MarketingLayout>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Eye, EyeOff } from 'lucide-vue-next'
import MarketingLayout from '@/Layouts/MarketingLayout.vue'
import InputError from '@/Components/InputError.vue'
import MarketingButton from '@/Components/ui/MarketingButton.vue'

// TypeScript declaration for Ziggy route helper (available globally at runtime)
declare const route: (name: string, params?: any) => string

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const showPassword = ref(false)

const submit = (): void => {
  form.post(route('login'))
}

// Check if Google OAuth route exists
const hasGoogleRoute = computed(() => {
  try {
    // Try to generate the route - if it doesn't exist, Ziggy will throw
    route('auth.google.redirect')
    return true
  } catch {
    return false
  }
})

const testimonials = [
  {
    id: 1,
    quote: 'Blendable\'s smart routing cut our AI costs by 40% while improving response quality. The auto-router selects the best model for each task automatically.',
    author: 'Sarah Chen',
    role: 'CTO at TechCorp',
    avatar: 'SC',
  },
  {
    id: 2,
    quote: 'We needed intelligent model routing and Blendable nailed it. Zero prompt changes required, and the analytics show exactly where we\'re saving money.',
    author: 'Marcus Johnson',
    role: 'Lead Engineer at StartupX',
    avatar: 'MJ',
  },
  {
    id: 3,
    quote: 'From cost optimization to multi-model support, Blendable transformed our AI workflow. The unified API makes switching between providers seamless.',
    author: 'Emma Williams',
    role: 'Product Manager at ScaleUp',
    avatar: 'EW',
  },
]
</script>
