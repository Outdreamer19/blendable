<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    MessageSquare, 
    Users, 
    Zap, 
    BarChart3, 
    Plus,
    Clock,
    TrendingUp,
    Activity
} from 'lucide-vue-next';

const props = defineProps({
    user: Object,
    currentWorkspace: Object,
    workspaces: Array,
    recentChats: Array,
    personas: Array,
    usageStats: Object,
    workspaceStats: Object,
});

const formatNumber = (num) => {
    if (!num) return '0';
    return new Intl.NumberFormat().format(num);
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString();
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :user="user" :workspaces="workspaces" :currentWorkspace="currentWorkspace">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Chats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <MessageSquare class="h-8 w-8 text-blue-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            Total Chats
                                        </dt>
                                        <dd class="text-lg font-medium text-gray-900">
                                            {{ formatNumber(workspaceStats?.total_chats) }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Messages -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <Activity class="h-8 w-8 text-green-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            Total Messages
                                        </dt>
                                        <dd class="text-lg font-medium text-gray-900">
                                            {{ formatNumber(workspaceStats?.total_messages) }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Personas -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <Users class="h-8 w-8 text-purple-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            Active Personas
                                        </dt>
                                        <dd class="text-lg font-medium text-gray-900">
                                            {{ formatNumber(workspaceStats?.total_personas) }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tokens Used -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <Zap class="h-8 w-8 text-yellow-600" />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            Tokens This Month
                                        </dt>
                                        <dd class="text-lg font-medium text-gray-900">
                                            {{ formatNumber(usageStats?.total_tokens_in + usageStats?.total_tokens_out) }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Recent Chats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Chats</h3>
                                <Link
                                    :href="route('chats.index')"
                                    class="text-sm text-blue-600 hover:text-blue-800"
                                >
                                    View All
                                </Link>
                            </div>
                            
                            <div class="space-y-4">
                                <div v-if="recentChats.length === 0" class="text-center py-8 text-gray-500">
                                    <MessageSquare class="h-12 w-12 mx-auto mb-4 text-gray-300" />
                                    <p>No chats yet. Start a new conversation!</p>
                                </div>
                                
                                <div v-for="chat in recentChats" :key="chat.id" class="border-b border-gray-200 pb-4 last:border-b-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <Link
                                                :href="route('chats.show', chat.id)"
                                                class="text-sm font-medium text-gray-900 hover:text-blue-600"
                                            >
                                                {{ chat.title || 'Untitled Chat' }}
                                            </Link>
                                            <div class="mt-1 flex items-center text-xs text-gray-500">
                                                <span v-if="chat.persona" class="mr-2">
                                                    {{ chat.persona.name }}
                                                </span>
                                                <Clock class="h-3 w-3 mr-1" />
                                                {{ formatDate(chat.last_message_at) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Personas -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Available Personas</h3>
                                <Link
                                    :href="route('personas.index')"
                                    class="text-sm text-blue-600 hover:text-blue-800"
                                >
                                    Manage
                                </Link>
                            </div>
                            
                            <div class="space-y-3">
                                <div v-if="personas.length === 0" class="text-center py-8 text-gray-500">
                                    <Users class="h-12 w-12 mx-auto mb-4 text-gray-300" />
                                    <p>No personas available. Create one to get started!</p>
                                </div>
                                
                                <div v-for="persona in personas" :key="persona.id" class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ persona.name }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ persona.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage Statistics -->
                <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Usage Statistics</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ formatNumber(usageStats?.total_tokens_in) }}
                                </div>
                                <div class="text-sm text-gray-500">Tokens In</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ formatNumber(usageStats?.total_tokens_out) }}
                                </div>
                                <div class="text-sm text-gray-500">Tokens Out</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ formatNumber(usageStats?.active_days) }}
                                </div>
                                <div class="text-sm text-gray-500">Active Days</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
