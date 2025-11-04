<template>
  <MarketingLayout>
    <article class="py-20 md:py-32">
      <div class="max-w-3xl mx-auto px-4 md:px-6">
        <!-- Header -->
        <header class="mb-12">
          <Link
            href="/blog"
            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 text-sm font-medium inline-flex items-center gap-2 mb-6"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to blog
          </Link>
          <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-slate-900 dark:text-white mb-4">
            {{ post?.title || 'Blog Post' }}
          </h1>
          <div class="text-slate-600 dark:text-slate-400 text-sm">
            {{ post?.date ? formatDate(post.date) : 'No date' }}
          </div>
        </header>

        <!-- Featured Image (placeholder) -->
        <div class="aspect-video bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 rounded-2xl mb-12 flex items-center justify-center">
          <div class="text-slate-500 dark:text-slate-400">Featured image placeholder</div>
        </div>

        <!-- Content -->
        <div class="prose prose-slate dark:prose-invert prose-lg max-w-none">
          <div
            v-if="post?.content"
            class="text-slate-600 dark:text-slate-300 leading-relaxed"
            v-html="post.content"
          ></div>
          <div v-else class="text-slate-600 dark:text-slate-300 leading-relaxed space-y-4">
            <p>
              This is a placeholder blog post. In a real implementation, you would fetch the blog post content
              from your database based on the slug and render it here.
            </p>
            <p>
              You can style the content using Tailwind's prose classes or create custom styles for headings,
              paragraphs, code blocks, blockquotes, and other elements.
            </p>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mt-8 mb-4">Example Heading</h2>
            <p>
              Here's some example content to show how the blog post would look. You can use markdown or HTML
              for rich formatting.
            </p>
            <blockquote class="border-l-4 border-indigo-500 pl-4 italic text-slate-600 dark:text-slate-400">
              This is an example blockquote. It's styled with a left border and italic text.
            </blockquote>
          </div>
        </div>

        <!-- Footer -->
        <footer class="mt-16 pt-8 border-t border-slate-200 dark:border-white/10">
          <Link
            href="/blog"
            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 text-sm font-medium inline-flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to blog
          </Link>
        </footer>
      </div>
    </article>
  </MarketingLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import MarketingLayout from '@/Layouts/MarketingLayout.vue'

interface Post {
  slug: string
  title: string
  content?: string
  date?: string
}

const props = defineProps<{
  slug: string
  post?: Post
}>()

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}
</script>

