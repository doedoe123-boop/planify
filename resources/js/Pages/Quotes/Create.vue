<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import debounce from 'lodash/debounce';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    websiteTypes: {
        type: Array,
        required: true
    },
    industries: {
        type: Array,
        required: true
    }
});

const currentStep = ref(1);
const totalSteps = 5;

const form = useForm({
    project_name: '',
    industry: '',
    project_description: '',
    business_goals: '',
    solution_overview: '',
    website_type_id: '',
    selected_features: [],
    hourly_rate: 50,
    custom_features: [],
    business_value_points: []
});

const selectedWebsiteType = computed(() => {
    return props.websiteTypes.find(type => type.id === form.website_type_id);
});

const availableFeatures = computed(() => {
    return selectedWebsiteType.value?.features || [];
});

const totalHours = computed(() => {
    const baseHours = selectedWebsiteType.value?.base_hours || 0;
    const featureHours = form.selected_features.reduce((total, featureId) => {
        const feature = availableFeatures.value.find(f => f.id === featureId);
        return total + (feature?.estimated_hours || 0);
    }, 0);
    const customHours = form.custom_features.reduce((total, feature) => total + (feature.hours || 0), 0);
    return baseHours + featureHours + customHours;
});

const totalCost = computed(() => {
    return totalHours.value * form.hourly_rate;
});

// Feature suggestions
const suggestedFeatures = ref([]);
const isLoadingSuggestions = ref(false);

// Debounced function to get feature suggestions
const getSuggestedFeatures = debounce(async () => {
    if (!form.project_description || form.project_description.length < 10) return;
    
    isLoadingSuggestions.value = true;
    
    try {
        const response = await axios.post(route('quotes.suggest_features'), {
            project_description: form.project_description,
            industry: form.industry
        });
        
        suggestedFeatures.value = response.data.suggestions;
    } catch (error) {
        console.error('Error getting suggestions:', error);
    } finally {
        isLoadingSuggestions.value = false;
    }
}, 500);

// Watch for changes in project description or industry to update suggestions
watch(() => form.project_description, getSuggestedFeatures);
watch(() => form.industry, getSuggestedFeatures);

// Apply suggested features
const applyFeatureSuggestion = (featureId) => {
    if (!form.selected_features.includes(featureId)) {
        form.selected_features.push(featureId);
    }
};

const nextStep = () => {
    if (currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const addCustomFeature = () => {
    form.custom_features.push({
        name: '',
        description: '',
        hours: 0
    });
};

const addBusinessValuePoint = () => {
    if (form.business_value_points.length < 5) {
        form.business_value_points.push('');
    }
};

const removeBusinessValuePoint = (index) => {
    form.business_value_points.splice(index, 1);
};

const submit = () => {
    form.post(route('quotes.store'), {
        onSuccess: () => {
            // Handle success
        },
    });
};

// Generate a solution overview based on form data
const generateSolutionOverview = () => {
    if (!form.project_description || !form.website_type_id) return;
    
    const websiteType = selectedWebsiteType.value.name;
    const overview = `We propose a custom ${websiteType} solution`;
    
    if (form.business_goals) {
        form.solution_overview = `${overview} designed to help you ${form.business_goals}. Based on your project requirements, we'll build a professional, user-friendly platform tailored to your specific needs.`;
    } else {
        form.solution_overview = `${overview} tailored to your project requirements. We'll build a professional, user-friendly platform designed to meet your needs effectively.`;
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Create Quote - Planify" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Progress Bar -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div v-for="step in totalSteps" :key="step" class="flex items-center">
                                <div :class="[
                                    'w-8 h-8 rounded-full flex items-center justify-center font-semibold text-sm',
                                    currentStep === step ? 'bg-blue-600 text-white' : 
                                    currentStep > step ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600'
                                ]">
                                    {{ step }}
                                </div>
                                <div v-if="step < totalSteps" class="w-16 h-1 mx-2" :class="[
                                    currentStep > step ? 'bg-green-500' : 'bg-gray-200'
                                ]"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step Content -->
                    <div class="p-6">
                        <!-- Step 1: Project Details -->
                        <div v-if="currentStep === 1">
                            <h2 class="text-lg font-semibold mb-4">Project Details</h2>
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="project_name" value="Project Name" />
                                    <TextInput
                                        id="project_name"
                                        v-model="form.project_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <InputError :message="form.errors.project_name" class="mt-2" />
                                </div>
                                
                                <div>
                                    <InputLabel for="industry" value="Industry" />
                                    <select
                                        id="industry"
                                        v-model="form.industry"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="">Select Industry</option>
                                        <option v-for="industry in industries" :key="industry" :value="industry">
                                            {{ industry }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.industry" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="project_description" value="Project Description" />
                                    <textarea
                                        id="project_description"
                                        v-model="form.project_description"
                                        rows="4"
                                        placeholder="Describe your project in detail. What problem are you trying to solve?"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    ></textarea>
                                    <InputError :message="form.errors.project_description" class="mt-2" />
                                </div>
                                
                                <div>
                                    <InputLabel for="business_goals" value="Business Goals (optional)" />
                                    <textarea
                                        id="business_goals"
                                        v-model="form.business_goals"
                                        rows="3"
                                        placeholder="What business outcomes are you hoping to achieve with this project?"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    ></textarea>
                                    <InputError :message="form.errors.business_goals" class="mt-2" />
                                </div>
                                
                                <!-- Feature Suggestions -->
                                <div v-if="suggestedFeatures.length > 0" class="mt-6 p-4 bg-blue-50 rounded-lg">
                                    <h3 class="font-medium text-blue-800">Suggested Features</h3>
                                    <p class="text-sm text-blue-600 mb-3">Based on your project description, these features might be relevant:</p>
                                    <div class="space-y-2">
                                        <div 
                                            v-for="feature in suggestedFeatures" 
                                            :key="feature.id"
                                            class="flex justify-between items-center p-2 bg-white rounded border border-blue-200"
                                        >
                                            <span>{{ feature.name }}</span>
                                            <button 
                                                type="button"
                                                @click="applyFeatureSuggestion(feature.id)"
                                                class="px-2 py-1 text-xs text-blue-700 hover:bg-blue-100 rounded"
                                            >
                                                Add to Quote
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="isLoadingSuggestions" class="mt-4 text-sm text-gray-500">
                                    Loading feature suggestions...
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Website Type -->
                        <div v-if="currentStep === 2">
                            <h2 class="text-lg font-semibold mb-4">Select Website Type</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    v-for="type in websiteTypes"
                                    :key="type.id"
                                    @click="form.website_type_id = type.id"
                                    :class="[
                                        'p-4 border rounded-lg cursor-pointer',
                                        form.website_type_id === type.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'
                                    ]"
                                >
                                    <h3 class="font-medium">{{ type.name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ type.description }}</p>
                                    <p class="text-sm text-gray-500 mt-2">Base Hours: {{ type.base_hours }}</p>
                                </div>
                            </div>
                            <InputError :message="form.errors.website_type_id" class="mt-2" />
                        </div>

                        <!-- Step 3: Features -->
                        <div v-if="currentStep === 3">
                            <h2 class="text-lg font-semibold mb-4">Select Features</h2>
                            <div v-if="availableFeatures.length" class="space-y-4">
                                <div
                                    v-for="feature in availableFeatures"
                                    :key="feature.id"
                                    class="flex items-start p-4 border rounded-lg"
                                >
                                    <div class="flex items-center h-5">
                                        <input
                                            type="checkbox"
                                            :id="'feature-' + feature.id"
                                            v-model="form.selected_features"
                                            :value="feature.id"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                    </div>
                                    <div class="ml-3 flex-grow">
                                        <label :for="'feature-' + feature.id" class="font-medium text-gray-900">
                                            {{ feature.name }}
                                            <span class="text-sm text-gray-500 ml-2">({{ feature.estimated_hours }} hours)</span>
                                        </label>
                                        <p class="text-sm text-gray-500">{{ feature.description }}</p>
                                        <p v-if="feature.business_value" class="mt-1 text-sm text-green-600">
                                            <span class="font-medium">Business Value:</span> {{ feature.business_value }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Custom Features -->
                                <div class="mt-6">
                                    <h3 class="font-medium mb-2">Custom Features</h3>
                                    <div v-for="(feature, index) in form.custom_features" :key="index" class="space-y-3 p-4 border rounded-lg mb-4">
                                        <div>
                                            <InputLabel :value="'Feature Name'" />
                                            <TextInput
                                                v-model="feature.name"
                                                type="text"
                                                class="mt-1 block w-full"
                                            />
                                        </div>
                                        <div>
                                            <InputLabel :value="'Description'" />
                                            <textarea
                                                v-model="feature.description"
                                                rows="2"
                                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            ></textarea>
                                        </div>
                                        <div>
                                            <InputLabel :value="'Estimated Hours'" />
                                            <TextInput
                                                v-model="feature.hours"
                                                type="number"
                                                class="mt-1 block w-full"
                                            />
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="addCustomFeature"
                                        class="mt-4 text-sm text-blue-600 hover:text-blue-500"
                                    >
                                        + Add Custom Feature
                                    </button>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 text-center py-4">
                                Please select a website type first
                            </div>
                        </div>
                        
                        <!-- Step 4: Business Value & Solution -->
                        <div v-if="currentStep === 4">
                            <h2 class="text-lg font-semibold mb-4">Business Value & Solution</h2>
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="solution_overview" value="Solution Overview" />
                                    <div class="flex items-center mb-2">
                                        <button 
                                            type="button" 
                                            @click="generateSolutionOverview"
                                            class="text-sm text-blue-600 hover:text-blue-500"
                                        >
                                            Generate overview
                                        </button>
                                    </div>
                                    <textarea
                                        id="solution_overview"
                                        v-model="form.solution_overview"
                                        rows="4"
                                        placeholder="Provide an overview of your proposed solution"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    ></textarea>
                                    <InputError :message="form.errors.solution_overview" class="mt-2" />
                                </div>
                                
                                <div>
                                    <InputLabel value="Business Value Points" />
                                    <p class="text-sm text-gray-500 mb-2">Highlight the business value this project will deliver (max 5 points)</p>
                                    
                                    <div v-for="(point, index) in form.business_value_points" :key="index" class="flex mb-2">
                                        <TextInput
                                            v-model="form.business_value_points[index]"
                                            type="text"
                                            class="block w-full"
                                            placeholder="e.g., Increase customer engagement by 20%"
                                        />
                                        <button 
                                            type="button" 
                                            @click="removeBusinessValuePoint(index)"
                                            class="ml-2 text-red-500 hover:text-red-700"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <button
                                        v-if="form.business_value_points.length < 5"
                                        type="button"
                                        @click="addBusinessValuePoint"
                                        class="mt-2 text-sm text-blue-600 hover:text-blue-500"
                                    >
                                        + Add Business Value Point
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Review & Pricing -->
                        <div v-if="currentStep === 5">
                            <h2 class="text-lg font-semibold mb-4">Review & Pricing</h2>
                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="hourly_rate" value="Hourly Rate ($)" />
                                    <TextInput
                                        id="hourly_rate"
                                        v-model="form.hourly_rate"
                                        type="number"
                                        class="mt-1 block w-full"
                                        required
                                    />
                                    <InputError :message="form.errors.hourly_rate" class="mt-2" />
                                </div>

                                <!-- Summary -->
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h3 class="font-medium mb-4">Project Summary</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Project Details</h4>
                                            <p class="mt-1">{{ form.project_name }}</p>
                                            <p v-if="form.industry" class="mt-1 text-sm text-gray-600">Industry: {{ form.industry }}</p>
                                            <p class="mt-1 text-sm text-gray-600">{{ form.project_description }}</p>
                                        </div>
                                        
                                        <div v-if="form.solution_overview">
                                            <h4 class="text-sm font-medium text-gray-500">Solution Overview</h4>
                                            <p class="mt-1 text-sm text-gray-600">{{ form.solution_overview }}</p>
                                        </div>
                                        
                                        <div v-if="form.business_value_points.length > 0">
                                            <h4 class="text-sm font-medium text-gray-500">Business Value</h4>
                                            <ul class="mt-1 list-disc list-inside space-y-1">
                                                <li v-for="(point, index) in form.business_value_points" :key="index" class="text-sm text-gray-600">
                                                    {{ point }}
                                                </li>
                                            </ul>
                                        </div>

                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Website Type</h4>
                                            <p class="mt-1">{{ selectedWebsiteType?.name }}</p>
                                            <p class="mt-1 text-sm text-gray-600">{{ selectedWebsiteType?.description }}</p>
                                        </div>

                                        <div v-if="form.selected_features.length">
                                            <h4 class="text-sm font-medium text-gray-500">Selected Features</h4>
                                            <ul class="mt-1 space-y-1">
                                                <li v-for="featureId in form.selected_features" :key="featureId" class="text-sm">
                                                    {{ availableFeatures.find(f => f.id === featureId)?.name }}
                                                    <span class="text-gray-500">({{ availableFeatures.find(f => f.id === featureId)?.estimated_hours }} hours)</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div v-if="form.custom_features.length">
                                            <h4 class="text-sm font-medium text-gray-500">Custom Features</h4>
                                            <ul class="mt-1 space-y-1">
                                                <li v-for="feature in form.custom_features" :key="feature.name" class="text-sm">
                                                    {{ feature.name }}
                                                    <span class="text-gray-500">({{ feature.hours }} hours)</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="pt-4 border-t border-gray-200">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Total Hours:</span>
                                                <span class="font-medium">{{ totalHours }}</span>
                                            </div>
                                            <div class="flex justify-between mt-2">
                                                <span class="text-gray-500">Hourly Rate:</span>
                                                <span class="font-medium">${{ form.hourly_rate }}/hour</span>
                                            </div>
                                            <div class="flex justify-between mt-2 text-lg font-semibold">
                                                <span class="text-gray-900">Total Cost:</span>
                                                <span class="text-blue-600">${{ totalCost }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="mt-6 flex justify-between">
                            <button
                                v-if="currentStep > 1"
                                type="button"
                                @click="prevStep"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Previous
                            </button>
                            <div v-else class="w-20"></div>

                            <div>
                                <button
                                    v-if="currentStep < totalSteps"
                                    type="button"
                                    @click="nextStep"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Next
                                </button>
                                <button
                                    v-else
                                    type="button"
                                    @click="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Create Quote
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 