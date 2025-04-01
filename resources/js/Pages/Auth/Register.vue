<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    company_name: '',
    role: '',
    website: '',
    password: '',
    password_confirmation: '',
    terms: false,
    newsletter: true,
});

const roles = [
    { id: 'freelancer', name: 'Freelancer' },
    { id: 'agency', name: 'Agency' },
    { id: 'company', name: 'Company' },
];

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Start Your Free Trial - Planify" />

    <div class="min-h-screen flex flex-col sm:flex-row bg-gray-50">
        <!-- Left Side - Value Proposition -->
        <div class="w-full sm:w-2/5 bg-[#2563EB] p-8 sm:p-12 flex flex-col justify-center text-white">
            <div class="max-w-md mx-auto">
                <Link href="/" class="text-2xl font-bold text-white mb-12 block">
                    Planify
                </Link>
                
                <h1 class="text-3xl font-bold mb-6">Start streamlining your web projects today</h1>
                <p class="text-blue-100 mb-8">Join thousands of developers and agencies who are saving time and winning more clients with our smart planning tools.</p>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-3">
                        <svg class="h-6 w-6 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Smart project templates and cost estimation</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="h-6 w-6 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Professional proposal templates</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="h-6 w-6 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>14-day free trial, no credit card required</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full sm:w-3/5 p-8 sm:p-12 flex items-center justify-center">
            <div class="w-full max-w-md">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Create your account</h2>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="name" value="Full Name" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Work Email" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="company_name" value="Company/Business Name" />
                        <TextInput
                            id="company_name"
                            v-model="form.company_name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.company_name" />
                    </div>

                    <div>
                        <InputLabel for="role" value="I am a..." />
                        <select
                            id="role"
                            v-model="form.role"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                        >
                            <option value="">Select your role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                                {{ role.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>

                    <div>
                        <InputLabel for="website" value="Website (optional)" />
                        <TextInput
                            id="website"
                            v-model="form.website"
                            type="url"
                            class="mt-1 block w-full"
                            placeholder="https://"
                        />
                        <InputError class="mt-2" :message="form.errors.website" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" value="Confirm Password" />
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center">
                            <Checkbox v-model:checked="form.terms" name="terms" required />
                            <span class="ms-2 text-sm text-gray-600">
                                I agree to the <Link :href="route('terms')" class="text-[#2563EB] hover:underline">Terms of Service</Link> and <Link :href="route('privacy')" class="text-[#2563EB] hover:underline">Privacy Policy</Link>
                            </span>
                        </label>

                        <label class="flex items-center">
                            <Checkbox v-model:checked="form.newsletter" name="newsletter" />
                            <span class="ms-2 text-sm text-gray-600">
                                Send me tips, trends, and insights about web development projects
                            </span>
                        </label>
                    </div>

                    <div class="flex flex-col space-y-4">
                        <PrimaryButton class="w-full justify-center py-3 bg-[#2563EB]" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Start Your Free Trial
                        </PrimaryButton>

                        <p class="text-center text-sm text-gray-600">
                            Already have an account?
                            <Link :href="route('login')" class="text-[#2563EB] hover:underline">
                                Sign in
                            </Link>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
