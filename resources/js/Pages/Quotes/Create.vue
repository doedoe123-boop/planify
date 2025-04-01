<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    websiteTypes: {
        type: Array,
        required: true
    }
});

const currentStep = ref(1);
const totalSteps = 4;

const form = useForm({
    project_name: '',
    project_description: '',
    website_type_id: '',
    selected_features: [],
    hourly_rate: 50,
    custom_features: [],
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

const submit = () => {
    form.post(route('quotes.store'), {
        onSuccess: () => {
            // Handle success
        },
    });
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
                                <div v-if="step < totalSteps" class="w-24 h-1 mx-2" :class="[
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
                                    <InputLabel for="project_description" value="Project Description" />
                                    <textarea
                                        id="project_description"
                                        v-model="form.project_description"
                                        rows="4"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    ></textarea>
                                    <InputError :message="form.errors.project_description" class="mt-2" />
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
                                    <div class="ml-3">
                                        <label :for="'feature-' + feature.id" class="font-medium text-gray-900">
                                            {{ feature.name }}
                                            <span class="text-sm text-gray-500 ml-2">({{ feature.estimated_hours }} hours)</span>
                                        </label>
                                        <p class="text-sm text-gray-500">{{ feature.description }}</p>
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

                        <!-- Step 4: Review & Pricing -->
                        <div v-if="currentStep === 4">
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
                                            <p class="mt-1 text-sm text-gray-600">{{ form.project_description }}</p>
                                        </div>

                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Website Type</h4>
                                            <p class="mt-1">{{ selectedWebsiteType?.name }}</p>
                                            <p class="mt-1 text-sm text-gray-600">Base Hours: {{ selectedWebsiteType?.base_hours }}</p>
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
                                type="button"
                                @click="prevStep"
                                v-if="currentStep > 1"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                Previous
                            </button>
                            <div class="ml-auto">
                                <PrimaryButton
                                    v-if="currentStep < totalSteps"
                                    @click="nextStep"
                                    :disabled="
                                        (currentStep === 1 && !form.project_name) ||
                                        (currentStep === 2 && !form.website_type_id)
                                    "
                                >
                                    Next
                                </PrimaryButton>
                                <PrimaryButton
                                    v-else
                                    @click="submit"
                                    :disabled="form.processing"
                                >
                                    Generate Quote
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 