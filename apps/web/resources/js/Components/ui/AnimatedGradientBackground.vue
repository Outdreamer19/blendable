<template>
  <div class="animated-gradient-bg">
    <svg class="gradient-svg" viewBox="0 0 1000 1000" preserveAspectRatio="xMidYMid slice">
      <defs>
        <!-- Gradient 1 -->
        <radialGradient id="gradient1" cx="50%" cy="50%" r="50%">
          <stop offset="0%" :style="`stop-color:${gradient1.color1};stop-opacity:1`" />
          <stop offset="50%" :style="`stop-color:${gradient1.color2};stop-opacity:0.8`" />
          <stop offset="100%" :style="`stop-color:${gradient1.color3};stop-opacity:0.6`" />
        </radialGradient>
        
        <!-- Gradient 2 -->
        <radialGradient id="gradient2" cx="50%" cy="50%" r="50%">
          <stop offset="0%" :style="`stop-color:${gradient2.color1};stop-opacity:1`" />
          <stop offset="50%" :style="`stop-color:${gradient2.color2};stop-opacity:0.8`" />
          <stop offset="100%" :style="`stop-color:${gradient2.color3};stop-opacity:0.6`" />
        </radialGradient>
        
        <!-- Gradient 3 -->
        <radialGradient id="gradient3" cx="50%" cy="50%" r="50%">
          <stop offset="0%" :style="`stop-color:${gradient3.color1};stop-opacity:1`" />
          <stop offset="50%" :style="`stop-color:${gradient3.color2};stop-opacity:0.8`" />
          <stop offset="100%" :style="`stop-color:${gradient3.color3};stop-opacity:0.6`" />
        </radialGradient>
      </defs>
      
      <!-- Animated gradient circles with morphing -->
      <circle 
        cx="20%" 
        cy="30%" 
        :r="animation1.radius" 
        fill="url(#gradient1)"
        :transform="`translate(${animation1.x}, ${animation1.y}) scale(${animation1.scale})`"
        :opacity="animation1.opacity"
      />
      <circle 
        cx="80%" 
        cy="70%" 
        :r="animation2.radius" 
        fill="url(#gradient2)"
        :transform="`translate(${animation2.x}, ${animation2.y}) scale(${animation2.scale})`"
        :opacity="animation2.opacity"
      />
      <circle 
        cx="50%" 
        cy="80%" 
        :r="animation3.radius" 
        fill="url(#gradient3)"
        :transform="`translate(${animation3.x}, ${animation3.y}) scale(${animation3.scale})`"
        :opacity="animation3.opacity"
      />
      
      <!-- Additional floating orbs for more complexity -->
      <circle 
        cx="70%" 
        cy="20%" 
        :r="animation4.radius" 
        fill="url(#gradient1)"
        :transform="`translate(${animation4.x}, ${animation4.y}) scale(${animation4.scale})`"
        :opacity="animation4.opacity"
      />
      <circle 
        cx="30%" 
        cy="70%" 
        :r="animation5.radius" 
        fill="url(#gradient2)"
        :transform="`translate(${animation5.x}, ${animation5.y}) scale(${animation5.scale})`"
        :opacity="animation5.opacity"
      />
    </svg>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, onBeforeUnmount, watch } from 'vue'

// Refined gradient color sets that complement the slate design
const gradientSets = [
  [
    { color1: '#1e293b', color2: '#334155', color3: '#475569' }, // Slate tones
    { color1: '#0f172a', color2: '#1e293b', color3: '#334155' }, // Dark slate
    { color1: '#1e40af', color2: '#3b82f6', color3: '#60a5fa' }   // Blue accent
  ],
  [
    { color1: '#1e293b', color2: '#475569', color3: '#64748b' }, // Slate to stone
    { color1: '#0f172a', color2: '#1e293b', color3: '#334155' }, // Dark slate
    { color1: '#7c3aed', color2: '#8b5cf6', color3: '#a78bfa' }   // Purple accent
  ],
  [
    { color1: '#1e293b', color2: '#334155', color3: '#475569' }, // Slate tones
    { color1: '#0f172a', color2: '#1e293b', color3: '#334155' }, // Dark slate
    { color1: '#059669', color2: '#10b981', color3: '#34d399' }   // Emerald accent
  ],
  [
    { color1: '#1e293b', color2: '#475569', color3: '#64748b' }, // Slate to stone
    { color1: '#0f172a', color2: '#1e293b', color3: '#334155' }, // Dark slate
    { color1: '#dc2626', color2: '#ef4444', color3: '#f87171' }   // Rose accent
  ]
]

// Current gradient set
const currentGradientSet = ref(0)
const gradient1 = ref(gradientSets[0][0])
const gradient2 = ref(gradientSets[0][1])
const gradient3 = ref(gradientSets[0][2])

// Refined animation states with subtle opacity
const animation1 = ref({ x: 0, y: 0, scale: 1, radius: 300, opacity: 0.3 })
const animation2 = ref({ x: 0, y: 0, scale: 1, radius: 250, opacity: 0.25 })
const animation3 = ref({ x: 0, y: 0, scale: 1, radius: 200, opacity: 0.2 })
const animation4 = ref({ x: 0, y: 0, scale: 1, radius: 150, opacity: 0.15 })
const animation5 = ref({ x: 0, y: 0, scale: 1, radius: 180, opacity: 0.18 })

let animationId: number | null = null
let time = 0
let isAnimating = ref(false)

const animate = () => {
  if (!isAnimating.value) {
    isAnimating.value = true
  }
  
  time += 0.008 // Slightly slower for smoother animation
  
  // Animate gradient positions, scales, radius, and opacity with subtle changes
  animation1.value = {
    x: Math.sin(time * 0.3) * 40,
    y: Math.cos(time * 0.2) * 30,
    scale: 1 + Math.sin(time * 0.2) * 0.1,
    radius: 300 + Math.sin(time * 0.1) * 20,
    opacity: 0.3 + Math.sin(time * 0.3) * 0.05
  }
  
  animation2.value = {
    x: Math.cos(time * 0.25) * 35,
    y: Math.sin(time * 0.3) * 25,
    scale: 1 + Math.cos(time * 0.25) * 0.08,
    radius: 250 + Math.cos(time * 0.15) * 15,
    opacity: 0.25 + Math.cos(time * 0.2) * 0.05
  }
  
  animation3.value = {
    x: Math.sin(time * 0.2) * 45,
    y: Math.cos(time * 0.35) * 20,
    scale: 1 + Math.sin(time * 0.3) * 0.06,
    radius: 200 + Math.sin(time * 0.2) * 12,
    opacity: 0.2 + Math.sin(time * 0.25) * 0.04
  }
  
  animation4.value = {
    x: Math.cos(time * 0.3) * 30,
    y: Math.sin(time * 0.2) * 22,
    scale: 1 + Math.cos(time * 0.15) * 0.05,
    radius: 150 + Math.cos(time * 0.25) * 10,
    opacity: 0.15 + Math.cos(time * 0.35) * 0.03
  }
  
  animation5.value = {
    x: Math.sin(time * 0.35) * 28,
    y: Math.cos(time * 0.25) * 18,
    scale: 1 + Math.sin(time * 0.2) * 0.06,
    radius: 180 + Math.sin(time * 0.3) * 12,
    opacity: 0.18 + Math.sin(time * 0.15) * 0.04
  }
  
  animationId = requestAnimationFrame(animate)
}

const changeGradients = () => {
  currentGradientSet.value = (currentGradientSet.value + 1) % gradientSets.length
  const newSet = gradientSets[currentGradientSet.value]
  
  gradient1.value = newSet[0]
  gradient2.value = newSet[1]
  gradient3.value = newSet[2]
}

onMounted(() => {
  // Start animation immediately
  animate()
  
  // Change gradient colors every 15 seconds for more subtle transitions
  const gradientInterval = setInterval(changeGradients, 15000)
  
  // Cleanup function
  const cleanup = () => {
    if (animationId) {
      cancelAnimationFrame(animationId)
      animationId = null
    }
    isAnimating.value = false
    clearInterval(gradientInterval)
  }
  
  onUnmounted(cleanup)
  onBeforeUnmount(cleanup)
  
  // Ensure animation starts even if there are timing issues
  setTimeout(() => {
    if (!animationId) {
      animate()
    }
  }, 100)
  
  // Debug: Log animation state
  console.log('AnimatedGradientBackground mounted, animation started:', isAnimating.value)
})

// Watch for animation stopping and restart if needed
watch(isAnimating, (newValue, oldValue) => {
  if (oldValue && !newValue) {
    // Animation stopped, restart it
    setTimeout(() => {
      if (!isAnimating.value) {
        animate()
      }
    }, 1000)
  }
})
</script>

<style scoped>
.animated-gradient-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  overflow: hidden;
}

.gradient-svg {
  width: 100%;
  height: 100%;
  filter: blur(30px) saturate(1.2) brightness(1.1);
  will-change: transform;
}

/* Subtle overlay for better content readability */
.animated-gradient-bg::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(ellipse at center, transparent 0%, rgba(15, 23, 42, 0.02) 70%, rgba(15, 23, 42, 0.08) 100%);
  z-index: 1;
}

/* Smooth transitions for gradient changes */
circle {
  transition: fill 3s ease-in-out, opacity 2s ease-in-out;
  will-change: transform, opacity;
}

/* Ensure gradients are always visible but subtle */
.gradient-svg circle {
  opacity: 0.3;
}

/* Subtle fallback for when animation doesn't start */
.animated-gradient-bg::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(ellipse at 20% 30%, rgba(30, 41, 59, 0.15) 0%, transparent 70%),
              radial-gradient(ellipse at 80% 70%, rgba(51, 65, 85, 0.12) 0%, transparent 70%),
              radial-gradient(ellipse at 50% 80%, rgba(30, 64, 175, 0.08) 0%, transparent 70%),
              radial-gradient(ellipse at 70% 20%, rgba(71, 85, 105, 0.1) 0%, transparent 70%),
              radial-gradient(ellipse at 30% 70%, rgba(15, 23, 42, 0.2) 0%, transparent 70%);
  z-index: 0;
  opacity: 1;
}

/* Performance optimizations */
.animated-gradient-bg {
  transform: translateZ(0);
  backface-visibility: hidden;
  perspective: 1000px;
}

/* Subtle pulsing effect */
@keyframes pulse {
  0%, 100% { opacity: 0.8; }
  50% { opacity: 1; }
}

.gradient-svg {
  animation: pulse 8s ease-in-out infinite;
}
</style>
