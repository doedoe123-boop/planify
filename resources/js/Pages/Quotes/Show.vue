<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    quote: {
        type: Object,
        required: true
    }
});

const totalHours = computed(() => {
    let hours = props.quote.website_type.base_hours;
    
    // Add hours from selected features
    if (props.quote.selected_features) {
        hours += props.quote.selected_features.reduce((sum, feature) => sum + feature.estimated_hours, 0);
    }
    
    // Add hours from custom features
    if (props.quote.custom_features) {
        hours += props.quote.custom_features.reduce((sum, feature) => sum + feature.hours, 0);
    }
    
    return hours;
});

const totalCost = computed(() => {
    return totalHours.value * props.quote.hourly_rate;
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};
</script>

<template>
    <AppLayout>
        <Head>
            <title>Quote Details</title>
        </Head>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Quote Details
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Quote Header -->
                    <div class="border-b pb-6 mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">{{ quote.project_name }}</h3>
                        <p class="mt-2 text-gray-600">{{ quote.project_description }}</p>
                        <div class="mt-4 flex items-center text-sm text-gray-500">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Created on {{ new Date(quote.created_at).toLocaleDateString() }}
                        </div>
                    </div>

                    <!-- Website Type -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Website Type</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="font-medium text-gray-900">{{ quote.website_type.name }}</h5>
                            <p class="mt-1 text-gray-600">{{ quote.website_type.description }}</p>
                            <p class="mt-2 text-sm text-gray-500">Base Hours: {{ quote.website_type.base_hours }}</p>
                        </div>
                    </div>

                    <!-- Selected Features -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Selected Features</h4>
                        <div class="space-y-4">
                            <div v-for="feature in quote.selected_features" :key="feature.id" class="bg-gray-50 rounded-lg p-4">
                                <h5 class="font-medium text-gray-900">{{ feature.name }}</h5>
                                <p class="mt-1 text-gray-600">{{ feature.description }}</p>
                                <p class="mt-2 text-sm text-gray-500">Estimated Hours: {{ feature.estimated_hours }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Features -->
                    <div v-if="quote.custom_features && quote.custom_features.length > 0" class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Custom Features</h4>
                        <div class="space-y-4">
                            <div v-for="(feature, index) in quote.custom_features" :key="index" class="bg-gray-50 rounded-lg p-4">
                                <h5 class="font-medium text-gray-900">{{ feature.name }}</h5>
                                <p class="mt-2 text-sm text-gray-500">Estimated Hours: {{ feature.hours }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Summary -->
                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Hourly Rate</span>
                            <span class="font-medium text-gray-900">{{ formatCurrency(quote.hourly_rate) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Total Hours</span>
                            <span class="font-medium text-gray-900">{{ totalHours }}</span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-semibold">
                            <span class="text-gray-900">Total Cost</span>
                            <span class="text-[#2563EB]">{{ formatCurrency(totalCost) }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <Link :href="route('quotes.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back to Quotes
                        </Link>
                        <button class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#2563EB] hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 