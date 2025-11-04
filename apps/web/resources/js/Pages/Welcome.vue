<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { MessageSquare, Users, Zap, Wrench, Star, ArrowRight, Brain, Shield, Sparkles } from 'lucide-vue-next';
import PublicNavbar from '@/Components/ui/PublicNavbar.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const page = usePage();
</script>

<template>
    <Head title="Omni-AI - Multi-Model AI Chat Platform" />
    
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <!-- Sophisticated Navbar -->
        <PublicNavbar />

        <!-- Hero Section -->
        <div class="relative px-6 py-24">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-6xl md:text-8xl font-semibold mb-8 tracking-tight bg-gradient-to-r from-slate-900 via-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Get everyday tasks done with AI.
                </h1>
                
                <p class="text-xl text-slate-600 mb-12 max-w-2xl mx-auto leading-relaxed">
                    Just start typing to begin your conversation with the world's most advanced AI models. 
                    Built into one unified platform. Now including GPT-4o, Claude 3, and Gemini.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
                    <Link
                        v-if="!page.props.auth.user"
                        :href="route('register')"
                        class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white px-8 py-4 rounded-xl hover:opacity-90 transition-all duration-200 text-lg font-medium shadow-lg shadow-blue-500/25"
                    >
                        Start Chatting
                    </Link>
                    <Link
                        v-else
                        :href="route('dashboard')"
                        class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white px-8 py-4 rounded-xl hover:opacity-90 transition-all duration-200 text-lg font-medium shadow-lg shadow-blue-500/25"
                    >
                        Go to Dashboard
                    </Link>
                    
                    <Link
                        :href="route('pricing')"
                        class="text-slate-600 hover:text-slate-900 transition-colors text-lg font-medium"
                    >
                        View Pricing
                    </Link>
                </div>

                <!-- AI Models Showcase -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 max-w-3xl mx-auto border border-slate-200 shadow-lg">
                    <h2 class="text-2xl font-semibold mb-6 text-slate-900">Protected by the strongest privacy of any AI assistant.</h2>
                    <p class="text-slate-600 mb-8">
                        Your conversations are secure and private. What you share with Omni-AI is never shared with advertisers.
                    </p>
                    <div class="flex items-center justify-center space-x-8 text-sm text-slate-500">
                        <div class="flex items-center space-x-2">
                            <Shield class="w-4 h-4" />
                            <span>End-to-end encrypted</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <Sparkles class="w-4 h-4" />
                            <span>On-device processing</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Use Cases Section -->
        <div class="px-6 py-24 bg-slate-50">
            <div class="max-w-6xl mx-auto">
                <!-- On the go -->
                <div class="mb-32">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div>
                            <h2 class="text-5xl font-semibold mb-8 tracking-tight">On the go.</h2>
                            <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                                Do it all even when your hands are full. Stay in touch, get answers, or find information. 
                                And with back-to-back requests, you can give a command, then follow up with another once it's complete.
                            </p>
                            <div class="space-y-4">
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"Summarize my meeting notes"</p>
                                </div>
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"Help me write a professional email"</p>
                                </div>
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"What's the weather like today?"</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-3xl p-8 shadow-lg border border-slate-200">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <MessageSquare class="w-8 h-8 text-white" />
                                </div>
                                <h3 class="text-2xl font-semibold mb-4 text-slate-900">Multi-Model AI</h3>
                                <p class="text-slate-600">Access GPT-4o, Claude 3, and Gemini in one unified platform</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- At work -->
                <div class="mb-32">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div class="order-2 lg:order-1">
                            <div class="bg-white rounded-3xl p-8 shadow-lg">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
                                        <Users class="w-8 h-8 text-white" />
                                    </div>
                                    <h3 class="text-2xl font-semibold mb-4">Team Collaboration</h3>
                                    <p class="text-gray-600">Work together on AI projects with shared workspaces</p>
                                </div>
                            </div>
                        </div>
                        <div class="order-1 lg:order-2">
                            <h2 class="text-5xl font-semibold mb-8 tracking-tight">At work.</h2>
                            <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                                Stay productive. Start a project, analyze data, and collaborate with your team — 
                                all while maintaining focus on what matters most.
                            </p>
                            <div class="space-y-4">
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"Analyze this spreadsheet data"</p>
                                </div>
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"Create a project timeline"</p>
                                </div>
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"Review this code for bugs"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Creating content -->
                <div class="mb-32">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div>
                            <h2 class="text-5xl font-semibold mb-8 tracking-tight">Creating content.</h2>
                            <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                                Multitask without the mess. Generate ideas, write copy, and create visuals. 
                                Without getting overwhelmed by multiple tools.
                            </p>
                            <div class="space-y-4">
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"Generate a blog post outline"</p>
                                </div>
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"Create a social media campaign"</p>
                                </div>
                                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                                    <p class="text-slate-800 font-medium">"Design a presentation slide"</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-3xl p-8 shadow-lg border border-slate-200">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <Sparkles class="w-8 h-8 text-white" />
                                </div>
                                <h3 class="text-2xl font-semibold mb-4 text-slate-900">Advanced Tools</h3>
                                <p class="text-slate-600">Web search, file analysis, image generation, and more</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="px-6 py-24 bg-white">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-5xl font-semibold mb-8 tracking-tight">
                    Go even further with Omni-AI.
                </h2>
                <p class="text-xl text-slate-600 mb-12 max-w-2xl mx-auto">
                    Use Omni-AI on all your devices. Start a conversation on your phone, 
                    continue on your computer, and finish on your tablet.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <Link
                        v-if="!page.props.auth.user"
                        :href="route('register')"
                        class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white px-8 py-4 rounded-xl hover:opacity-90 transition-all duration-200 text-lg font-medium shadow-lg shadow-blue-500/25"
                    >
                        Get Started Free
                    </Link>
                    <Link
                        v-else
                        :href="route('dashboard')"
                        class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white px-8 py-4 rounded-xl hover:opacity-90 transition-all duration-200 text-lg font-medium shadow-lg shadow-blue-500/25"
                    >
                        Go to Dashboard
                    </Link>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-slate-900 text-white py-16">
            <div class="max-w-6xl mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                                <Brain class="w-5 h-5 text-white" />
                            </div>
                            <span class="text-xl font-semibold">Omni-AI</span>
                        </div>
                        <p class="text-slate-400 text-sm">
                            The most private AI assistant. Your conversations are secure and never shared with advertisers.
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold mb-4 text-sm">Product</h3>
                        <ul class="space-y-3 text-sm text-slate-400">
                            <li><Link :href="route('pricing')" class="hover:text-white transition-colors">Pricing</Link></li>
                            <li><Link :href="route('reviews')" class="hover:text-white transition-colors">Reviews</Link></li>
                            <li><Link :href="route('enterprise')" class="hover:text-white transition-colors">Enterprise</Link></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold mb-4 text-sm">Resources</h3>
                        <ul class="space-y-3 text-sm text-slate-400">
                            <li><Link :href="route('blog')" class="hover:text-white transition-colors">Blog</Link></li>
                            <li><Link :href="route('product-updates')" class="hover:text-white transition-colors">Updates</Link></li>
                            <li><Link :href="route('chatgpt-vs-omni')" class="hover:text-white transition-colors">vs ChatGPT</Link></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold mb-4 text-sm">Support</h3>
                        <ul class="space-y-3 text-sm text-slate-400">
                            <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Status</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-slate-700 mt-12 pt-8 text-center text-slate-400 text-sm">
                    <p>&copy; 2024 Omni-AI. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>