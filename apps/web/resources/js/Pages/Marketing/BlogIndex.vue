<template>
  <MarketingLayout>
    <div class="py-20 md:py-32">
      <div class="max-w-6xl mx-auto px-4 md:px-6">
        <!-- Header -->
        <div class="text-center mb-16">
          <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-slate-900 dark:text-white mb-6">
            Blog
          </h1>
          <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
            Insights, tutorials, and updates from the Blendable team
          </p>
        </div>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article
            v-for="post in posts"
            :key="post.slug"
            class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/[0.04] backdrop-blur overflow-hidden hover:bg-slate-50 dark:hover:bg-white/[0.06] transition-all group shadow-sm dark:shadow-none"
          >
            <div class="aspect-video bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 relative overflow-hidden">
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-slate-500 dark:text-slate-400 text-sm">Image placeholder</div>
              </div>
            </div>
            <div class="p-6">
              <div class="text-sm text-slate-600 dark:text-slate-400 mb-2">{{ formatDate(post.date) }}</div>
              <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                <Link :href="`/blog/${post.slug}`" class="hover:underline">
                  {{ post.title }}
                </Link>
              </h2>
              <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed mb-4">
                {{ post.excerpt }}
              </p>
              <Link
                :href="`/blog/${post.slug}`"
                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 text-sm font-medium inline-flex items-center gap-1"
              >
                Read more
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </Link>
            </div>
          </article>
        </div>

        <!-- Empty State -->
        <div v-if="posts.length === 0" class="text-center py-20">
          <p class="text-slate-600 dark:text-slate-400 text-lg">No blog posts yet. Check back soon!</p>
        </div>
      </div>
    </div>
  </MarketingLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import MarketingLayout from '@/Layouts/MarketingLayout.vue'

interface Post {
  slug: string
  title: string
  excerpt: string
  date: string
}

const props = defineProps<{
  posts?: Post[]
}>()

const posts = props.posts || []

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}
</script>

