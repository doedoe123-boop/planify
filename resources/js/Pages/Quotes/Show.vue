<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, ref } from 'vue';

const props = defineProps({
    quote: {
        type: Object,
        required: true
    },
    tasksBreakdown: {
        type: Object,
        required: true
    },
    deliverables: {
        type: Array,
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

// Task breakdown editing
const isEditingTasks = ref(false);
const expandedFeatures = ref({});

// Initialize form with task data from the breakdown
const form = useForm({
    tasks: {}
});

// Initialize task data for the form
const initTaskForm = () => {
    const tasks = {};
    
    // Iterate through task breakdown and set up initial form values
    Object.keys(props.tasksBreakdown).forEach(featureName => {
        props.tasksBreakdown[featureName].forEach(task => {
            tasks[task.id] = {
                included: task.included,
                custom_hours: task.custom_hours || null
            };
        });
    });
    
    form.tasks = tasks;
};

// Call initialization
initTaskForm();

// Toggle edit mode for tasks
const toggleTasksEdit = () => {
    isEditingTasks.value = !isEditingTasks.value;
    if (!isEditingTasks.value) {
        // Reset form when cancelling edit
        initTaskForm();
    }
};

// Toggle expanding/collapsing a feature's tasks
const toggleFeatureExpanded = (featureName) => {
    expandedFeatures.value[featureName] = !expandedFeatures.value[featureName];
};

// Check if a feature is expanded
const isFeatureExpanded = (featureName) => {
    return expandedFeatures.value[featureName] !== false; // Default to expanded
};

// Calculate total hours for a feature with current form values
const getFeatureTotalHours = (featureName) => {
    return props.tasksBreakdown[featureName].reduce((total, task) => {
        if (form.tasks[task.id]?.included) {
            const hours = form.tasks[task.id]?.custom_hours !== null
                ? form.tasks[task.id].custom_hours
                : task.estimated_hours;
            return total + parseFloat(hours);
        }
        return total;
    }, 0);
};

// Calculate overall hours based on form values
const calculatedTotalHours = computed(() => {
    let total = props.quote.website_type.base_hours;
    
    // Add hours from tasks
    Object.keys(props.tasksBreakdown).forEach(featureName => {
        total += getFeatureTotalHours(featureName);
    });
    
    // Add hours from custom features
    if (props.quote.custom_features) {
        total += props.quote.custom_features.reduce((sum, feature) => sum + feature.hours, 0);
    }
    
    return total;
});

// Calculate overall cost based on form values
const calculatedTotalCost = computed(() => {
    return calculatedTotalHours.value * props.quote.hourly_rate;
});

// Submit task updates
const submitTaskUpdates = () => {
    form.put(route('quotes.update_tasks', props.quote.id), {
        onSuccess: () => {
            isEditingTasks.value = false;
        }
    });
};

const downloadPdf = () => {
    window.location.href = route('quotes.pdf', props.quote.id);
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
                        <div v-if="quote.industry" class="mt-1 text-gray-500">Industry: {{ quote.industry }}</div>
                        <p class="mt-2 text-gray-600">{{ quote.project_description }}</p>
                        <div class="mt-4 flex items-center text-sm text-gray-500">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Created on {{ new Date(quote.created_at).toLocaleDateString() }}
                        </div>
                    </div>
                    
                    <!-- Business Value Section -->
                    <div v-if="quote.solution_overview || quote.business_goals || quote.business_value_points" class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Business Value</h4>
                        
                        <div v-if="quote.business_goals" class="bg-gray-50 rounded-lg p-4 mb-4">
                            <h5 class="font-medium text-gray-900">Business Goals</h5>
                            <p class="mt-1 text-gray-600">{{ quote.business_goals }}</p>
                        </div>
                        
                        <div v-if="quote.solution_overview" class="bg-gray-50 rounded-lg p-4 mb-4">
                            <h5 class="font-medium text-gray-900">Solution Overview</h5>
                            <p class="mt-1 text-gray-600">{{ quote.solution_overview }}</p>
                        </div>
                        
                        <div v-if="quote.business_value_points && quote.business_value_points.length > 0" class="bg-gray-50 rounded-lg p-4">
                            <h5 class="font-medium text-gray-900">Value Points</h5>
                            <ul class="mt-2 space-y-1 list-disc list-inside">
                                <li v-for="(point, index) in quote.business_value_points" :key="index" class="text-gray-600">
                                    {{ point }}
                                </li>
                            </ul>
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
                    
                    <!-- Deliverables -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Key Deliverables</h4>
                        <div class="space-y-4">
                            <div v-for="(deliverable, index) in deliverables" :key="index" class="bg-green-50 rounded-lg p-4 border border-green-100">
                                <h5 class="font-medium text-gray-900">{{ deliverable.name }}</h5>
                                <p class="mt-1 text-gray-600">{{ deliverable.description }}</p>
                            </div>
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
                                <p v-if="feature.business_value" class="mt-2 text-sm text-green-600">
                                    <span class="font-medium">Business Value:</span> {{ feature.business_value }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Features -->
                    <div v-if="quote.custom_features && quote.custom_features.length > 0" class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Custom Features</h4>
                        <div class="space-y-4">
                            <div v-for="(feature, index) in quote.custom_features" :key="index" class="bg-gray-50 rounded-lg p-4">
                                <h5 class="font-medium text-gray-900">{{ feature.name }}</h5>
                                <p v-if="feature.description" class="mt-1 text-gray-600">{{ feature.description }}</p>
                                <p class="mt-2 text-sm text-gray-500">Estimated Hours: {{ feature.hours }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Functional Breakdown -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">Functional Breakdown</h4>
                            <button 
                                @click="toggleTasksEdit" 
                                type="button" 
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                {{ isEditingTasks ? 'Cancel' : 'Edit Tasks' }}
                            </button>
                        </div>
                        
                        <form @submit.prevent="submitTaskUpdates">
                            <div class="space-y-6">
                                <div v-for="(tasks, featureName) in tasksBreakdown" :key="featureName" class="border border-gray-200 rounded-lg overflow-hidden">
                                    <!-- Feature Header -->
                                    <div 
                                        @click="toggleFeatureExpanded(featureName)"
                                        class="bg-gray-50 px-4 py-3 flex justify-between items-center cursor-pointer"
                                    >
                                        <div>
                                            <h5 class="font-medium text-gray-900">{{ featureName }}</h5>
                                            <p class="text-sm text-gray-500">{{ getFeatureTotalHours(featureName) }} hours</p>
                                        </div>
                                        <svg 
                                            class="h-5 w-5 text-gray-500 transform transition-transform" 
                                            :class="{ 'rotate-180': !isFeatureExpanded(featureName) }"
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Task List -->
                                    <div v-if="isFeatureExpanded(featureName)" class="divide-y divide-gray-200">
                                        <div 
                                            v-for="task in tasks" 
                                            :key="task.id" 
                                            class="px-4 py-3"
                                            :class="{ 
                                                'bg-gray-50': !form.tasks[task.id]?.included,
                                                'bg-green-50': task.is_deliverable && form.tasks[task.id]?.included
                                            }"
                                        >
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5 mt-1" v-if="isEditingTasks">
                                                    <input 
                                                        type="checkbox" 
                                                        :id="`task-${task.id}`" 
                                                        v-model="form.tasks[task.id].included"
                                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                                    >
                                                </div>
                                                <div :class="{ 'ml-3': isEditingTasks, 'flex-grow': true }">
                                                    <label :for="`task-${task.id}`" class="flex justify-between">
                                                        <div>
                                                            <span 
                                                                class="font-medium flex items-center" 
                                                                :class="{ 
                                                                    'text-gray-900': form.tasks[task.id]?.included, 
                                                                    'text-gray-500 line-through': !form.tasks[task.id]?.included 
                                                                }"
                                                            >
                                                                {{ task.name }}
                                                                <span v-if="task.is_deliverable" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                                    Deliverable
                                                                </span>
                                                            </span>
                                                            <p class="text-sm text-gray-500 mt-1">{{ task.description }}</p>
                                                        </div>
                                                        <div class="w-32 text-right" v-if="!isEditingTasks">
                                                            <span class="text-sm font-medium text-gray-900">
                                                                {{ task.estimated_hours }} hours
                                                            </span>
                                                        </div>
                                                        <div class="w-32 pl-4" v-else>
                                                            <input 
                                                                type="number" 
                                                                v-model="form.tasks[task.id].custom_hours"
                                                                :placeholder="task.estimated_hours"
                                                                :disabled="!form.tasks[task.id].included"
                                                                step="0.5"
                                                                min="0"
                                                                class="block w-full text-right border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm sm:text-sm"
                                                            >
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-end" v-if="isEditingTasks">
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Quote Summary -->
                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Hourly Rate</span>
                            <span class="font-medium text-gray-900">{{ formatCurrency(quote.hourly_rate) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-600">Total Hours</span>
                            <span class="font-medium text-gray-900">
                                {{ isEditingTasks ? calculatedTotalHours : totalHours }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-lg font-semibold">
                            <span class="text-gray-900">Total Cost</span>
                            <span class="text-[#2563EB]">
                                {{ isEditingTasks ? formatCurrency(calculatedTotalCost) : formatCurrency(totalCost) }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a :href="route('quotes.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back to Quotes
                        </a>
                        <button @click="downloadPdf" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#2563EB] hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 