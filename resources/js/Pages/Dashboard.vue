<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const services = [
    {
        title: 'Project Templates',
        description: 'Access and customize pre-built project templates to streamline your workflow.',
        icon: 'M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3',
        action: 'Browse Templates',
        route: 'templates'
    },
    {
        title: 'Quote Generator',
        description: 'Create professional quotes with our smart pricing calculator and templates.',
        icon: 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z',
        hasMultipleActions: true,
        actions: [
            {
                label: 'View All Quotes',
                route: 'quotes.index'
            },
            {
                label: 'Create New Quote',
                route: 'quotes.create'
            }
        ]
    },
    {
        title: 'Proposal Builder',
        description: 'Build compelling proposals that win clients using our professional templates.',
        icon: 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        action: 'Create Proposal',
        route: 'proposals.create'
    },
    {
        title: 'Client Management',
        description: 'Manage your clients, track interactions, and maintain relationships effectively.',
        icon: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
        action: 'Manage Clients',
        route: 'clients'
    }
];

const quickStats = [
    {
        label: 'Total Quotes',
        value: '24',
        change: '+8 this month',
        trend: 'up',
        icon: 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.171-.879-1.171-2.303 0-3.182C10.582 7.72 11.35 7.5 12 7.5c.725 0 1.45.22 2.003.659'
    },
    {
        label: 'Conversion Rate',
        value: '68%',
        change: '+5% vs last month',
        trend: 'up',
        icon: 'M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941'
    },
    {
        label: 'Average Quote Value',
        value: '$2,450',
        change: '+$420 avg increase',
        trend: 'up',
        icon: 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.171-.879-1.171-2.303 0-3.182C10.582 7.72 11.35 7.5 12 7.5c.725 0 1.45.22 2.003.659'
    },
    {
        label: 'Active Projects',
        value: '12',
        change: '3 pending completion',
        trend: 'neutral',
        icon: 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z'
    }
];

const recentActivity = [
    {
        type: 'quote_created',
        title: 'E-commerce Website Quote Generated',
        time: '2 hours ago',
        amount: '$3,200'
    },
    {
        type: 'quote_approved',
        title: 'Portfolio Website Quote Approved',
        time: '5 hours ago',
        amount: '$1,800'
    },
    {
        type: 'project_completed',
        title: 'Blog Website Project Completed',
        time: '1 day ago',
        amount: '$2,400'
    }
];

const popularWebsiteTypes = [
    { name: 'E-commerce', percentage: 35 },
    { name: 'Portfolio', percentage: 25 },
    { name: 'Blog', percentage: 20 },
    { name: 'Corporate', percentage: 15 },
    { name: 'Landing Page', percentage: 5 }
];
</script>

<template>
    <AppLayout>
        <Head title="Dashboard" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Welcome Section with Quick Actions -->
                <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div class="mb-4 sm:mb-0">
                            <h1 class="text-2xl font-bold text-white">Welcome back!</h1>
                            <p class="mt-1 text-blue-100">Here's what's happening with your projects today.</p>
                        </div>
                        <div class="flex space-x-3">
                            <Link
                                :href="route('quotes.create')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Create Quote
                                <svg class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div v-for="stat in quickStats" :key="stat.label" 
                        class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border border-gray-100 hover:border-blue-500 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ stat.label }}</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ stat.value }}</div>
                                        <div :class="[
                                            'ml-2 flex items-baseline text-sm font-semibold',
                                            stat.trend === 'up' ? 'text-green-600' : stat.trend === 'down' ? 'text-red-600' : 'text-gray-600'
                                        ]">
                                            <svg v-if="stat.trend === 'up'" class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                            <svg v-if="stat.trend === 'down'" class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                                            </svg>
                                            <span class="sr-only">{{ stat.trend === 'up' ? 'Increased' : 'Decreased' }} by</span>
                                            {{ stat.change }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content Area -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Recent Activity -->
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h2>
                                <div class="flow-root">
                                    <ul role="list" class="-mb-8">
                                        <li v-for="(activity, index) in recentActivity" :key="index">
                                            <div class="relative pb-8">
                                                <span v-if="index !== recentActivity.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-sm text-gray-500">{{ activity.title }}</p>
                                                            <p class="text-sm text-gray-900 font-medium">{{ activity.amount }}</p>
                                                        </div>
                                                        <div class="mt-1">
                                                            <p class="text-sm text-gray-500">{{ activity.time }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-6">
                                    <Link :href="route('quotes.index')" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                        View all activity
                                        <span aria-hidden="true"> &rarr;</span>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Services Overview -->
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Our Services</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div v-for="service in services" :key="service.title" 
                                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 hover:bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="p-3 bg-blue-50 rounded-lg">
                                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" :d="service.icon" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    {{ service.title }}
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ service.description }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <template v-if="service.hasMultipleActions">
                                                <div class="space-x-3">
                                                    <Link v-for="action in service.actions" 
                                                        :key="action.route"
                                                        :href="route(action.route)"
                                                        class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500"
                                                    >
                                                        {{ action.label }}
                                                        <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </Link>
                                                </div>
                                            </template>
                                            <Link v-else
                                                :href="route(service.route)"
                                                class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500"
                                            >
                                                {{ service.action }}
                                                <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Popular Website Types -->
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Popular Website Types</h2>
                                <div class="space-y-4">
                                    <div v-for="type in popularWebsiteTypes" :key="type.name" class="relative">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-medium text-gray-600">{{ type.name }}</div>
                                            <div class="text-sm font-medium text-gray-900">{{ type.percentage }}%</div>
                                        </div>
                                        <div class="mt-2 overflow-hidden bg-gray-200 rounded-full">
                                            <div
                                                class="h-2 bg-blue-600 rounded-full"
                                                :style="{ width: `${type.percentage}%` }"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Tips -->
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Tips</h2>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900">Optimize Your Quotes</h3>
                                            <p class="mt-1 text-sm text-gray-500">Include detailed project requirements to get more accurate estimates.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900">Save Time</h3>
                                            <p class="mt-1 text-sm text-gray-500">Use our templates to create professional quotes in minutes.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
