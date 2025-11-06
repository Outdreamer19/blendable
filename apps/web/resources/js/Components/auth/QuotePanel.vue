<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const props = withDefaults(defineProps<{
  heading: string
  quote?: string
  author?: string
  role?: string
}>(), {})

// Testimonials data
const testimonials = ref([
  {
    quote: "Blendable has completely transformed how I approach my work. The AI assistance is incredibly intuitive and saves me hours every day.",
    author: "Sarah Chen",
    role: "Product Manager at Stripe"
  },
  {
    quote: "The quality of AI responses is outstanding. It feels like having a senior developer pair programming with me 24/7.",
    author: "Marcus Rodriguez",
    role: "Senior Software Engineer at Netflix"
  },
  {
    quote: "I've tried many AI tools, but Blendable stands out. The context understanding and code generation are remarkably accurate.",
    author: "Emily Watson",
    role: "Tech Lead at Airbnb"
  },
  {
    quote: "The productivity boost is real. What used to take me days now takes hours. It's like having a superpower.",
    author: "David Kim",
    role: "Full Stack Developer at Shopify"
  },
  {
    quote: "Blendable has become an essential part of my workflow. The suggestions are always relevant and help me write better code.",
    author: "Lisa Thompson",
    role: "Frontend Developer at Figma"
  },
  {
    quote: "The AI understands context so well. It's like having a brilliant colleague who never gets tired and always has the right answer.",
    author: "Alex Johnson",
    role: "DevOps Engineer at AWS"
  }
])

const currentIndex = ref(0)
let autoRotateInterval: NodeJS.Timeout | null = null

const nextTestimonial = () => {
  currentIndex.value = (currentIndex.value + 1) % testimonials.value.length
}

const prevTestimonial = () => {
  currentIndex.value = currentIndex.value === 0 ? testimonials.value.length - 1 : currentIndex.value - 1
}

const startAutoRotate = () => {
  autoRotateInterval = setInterval(nextTestimonial, 5000) // Change every 5 seconds
}

const stopAutoRotate = () => {
  if (autoRotateInterval) {
    clearInterval(autoRotateInterval)
    autoRotateInterval = null
  }
}

onMounted(() => {
  startAutoRotate()
})

onUnmounted(() => {
  stopAutoRotate()
})
</script>

<template>
  <aside class="relative overflow-hidden rounded-[28px] border border-slate-800/70 bg-white/5 backdrop-blur-xl shadow-2xl shadow-black/40">
    <!-- clipped top-right corner -->
    <div class="absolute -top-0.5 -right-0.5 w-12 h-12 bg-slate-950 rounded-bl-2xl"></div>

    <div class="absolute -right-16 bottom-0 h-[420px] w-[420px] pointer-events-none">
      <svg viewBox="0 0 100 100" class="h-full w-full opacity-25 motion-safe:animate-[spin_40s_linear_infinite]">
        <g stroke="url(#g)" stroke-width="1" fill="none">
          <defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#22d3ee"/><stop offset="100%" stop-color="#6366f1"/></linearGradient></defs>
          <g transform="translate(50,50)">
            <line v-for="i in 12" :key="i" :x1="0" :y1="0" :x2="0" y2="-44" :transform="`rotate(${(i-1)*30})`" />
          </g>
        </g>
      </svg>
    </div>

    <div class="relative p-6 md:p-8 lg:p-10">
      <h2 class="text-2xl md:text-3xl font-semibold mb-6 leading-tight">{{ heading }}</h2>

      <!-- Testimonial content with smooth transitions -->
      <div class="relative min-h-[120px]">
        <div 
          v-for="(testimonial, index) in testimonials" 
          :key="index"
          class="absolute inset-0 transition-opacity duration-500 ease-in-out"
          :class="index === currentIndex ? 'opacity-100' : 'opacity-0'"
        >
          <p class="text-slate-300 italic text-lg max-w-prose">
            "{{ testimonial.quote }}"
          </p>

          <div class="mt-8">
            <p class="font-medium">{{ testimonial.author }}</p>
            <p class="text-slate-400 text-sm">{{ testimonial.role }}</p>
          </div>
        </div>
      </div>

      <!-- Navigation controls -->
      <div class="mt-10 flex items-center gap-3">
        <button 
          type="button" 
          aria-label="Previous testimonial" 
          @click="prevTestimonial"
          @mouseenter="stopAutoRotate"
          @mouseleave="startAutoRotate"
          class="h-9 w-9 grid place-items-center rounded-lg bg-rose-500/90 text-white hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-rose-400/70 ring-offset-2 ring-offset-slate-950 transition-all duration-200"
        >
          ←
        </button>
        <button 
          type="button" 
          aria-label="Next testimonial" 
          @click="nextTestimonial"
          @mouseenter="stopAutoRotate"
          @mouseleave="startAutoRotate"
          class="h-9 w-9 grid place-items-center rounded-lg bg-emerald-500/90 text-white hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-emerald-400/70 ring-offset-2 ring-offset-slate-950 transition-all duration-200"
        >
          →
        </button>
        
        <!-- Progress indicators -->
        <div class="flex items-center gap-1 ml-4">
          <div 
            v-for="(_, index) in testimonials" 
            :key="index"
            class="h-2 w-2 rounded-full transition-all duration-300"
            :class="index === currentIndex ? 'bg-slate-300' : 'bg-slate-600'"
          ></div>
        </div>
      </div>
    </div>
  </aside>
</template>
