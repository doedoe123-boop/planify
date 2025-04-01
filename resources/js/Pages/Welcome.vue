<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Planify - Website Planning & Quotation Builder" />
    <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-black min-h-screen">
        <div class="relative">
            <!-- Header/Navigation -->
            <header class="relative z-10 px-6 py-4 sm:px-8 lg:px-12">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center">
                        <Link href="/" class="text-2xl font-bold text-[#2563EB] dark:text-white">
                            Planify
                        </Link>
                    </div>

                    <div v-if="canLogin" class="flex items-center gap-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                            >
                                Sign In
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-[#2563EB] px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 transition"
                            >
                                Start Free Trial
                            </Link>
                        </template>
                    </div>
                    </nav>
                </header>

            <!-- Hero Section -->
            <div class="relative px-6 lg:px-8">
                <div class="mx-auto max-w-7xl pt-16 sm:pt-24">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                            Transform Your Web Projects with Smart Planning
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                            The all-in-one platform for developers, freelancers, and agencies to plan websites, generate accurate quotes, and create winning proposals.
                        </p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-[#2563EB] px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-blue-600 transition"
                            >
                                Get Started for Free
                            </Link>
                            <a href="#features" class="text-base font-semibold leading-7 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                Learn more <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features" class="mx-auto max-w-7xl px-6 lg:px-8 py-24">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="rounded-xl bg-white p-8 shadow-lg ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-500/10">
                            <svg class="h-6 w-6 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V19.5a2.25 2.25 0 002.25 2.25h.75"></path>
                            </svg>
                        </div>
                        <h2 class="mt-6 text-lg font-semibold text-gray-900 dark:text-white">Smart Project Planning</h2>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                            Create detailed website plans with our intelligent templates. Break down projects into phases, tasks, and milestones.
                        </p>
                            </div>

                    <!-- Feature 2 -->
                    <div class="rounded-xl bg-white p-8 shadow-lg ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-500/10">
                            <svg class="h-6 w-6 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"></path>
                            </svg>
                        </div>
                        <h2 class="mt-6 text-lg font-semibold text-gray-900 dark:text-white">Accurate Cost Estimation</h2>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                            Generate precise quotes based on project scope, features, and complexity. Never undercharge for your work again.
                        </p>
                                    </div>

                    <!-- Feature 3 -->
                    <div class="rounded-xl bg-white p-8 shadow-lg ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-500/10">
                            <svg class="h-6 w-6 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                            </svg>
                        </div>
                        <h2 class="mt-6 text-lg font-semibold text-gray-900 dark:text-white">Professional Proposals</h2>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                            Create stunning proposals with our customizable templates. Win more clients with professional, detailed documentation.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Why Choose Us Section -->
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-24 bg-gray-50 dark:bg-gray-900">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Why Choose Planify?</h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                        We understand the challenges of web development projects. Our platform is built by developers, for developers, to streamline your workflow and increase profitability.
                                        </p>
                                    </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#2563EB]">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Speed Up Your Workflow</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">Our AI-powered templates and tools help you create project plans and quotes in minutes, not hours.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#2563EB]">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.171-.879-1.172-2.303 0-3.182C10.536 7.719 11.768 7.5 12 7.5c.725 0 1.45.22 2.003.659" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Maximize Profitability</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">Our detailed cost estimation ensures you price projects correctly and maintain healthy profit margins.</p>
                            </div>
                            </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#2563EB]">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Win More Clients</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">Professional proposals and clear project plans help you stand out and win more business.</p>
                            </div>
                        </div>
                            </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">What You Get</h3>
                        <ul class="space-y-5">
                            <li class="flex gap-3">
                                <svg class="h-6 w-6 flex-none text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">Smart project templates based on industry best practices</span>
                            </li>
                            <li class="flex gap-3">
                                <svg class="h-6 w-6 flex-none text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">Accurate cost estimation based on real market rates</span>
                            </li>
                            <li class="flex gap-3">
                                <svg class="h-6 w-6 flex-none text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">Professional proposal templates that convert</span>
                            </li>
                            <li class="flex gap-3">
                                <svg class="h-6 w-6 flex-none text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">Project timeline and milestone planning tools</span>
                            </li>
                            <li class="flex gap-3">
                                <svg class="h-6 w-6 flex-none text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">Customizable workflow and collaboration features</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- How It Works Section -->
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-24">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">How Planify Works</h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                        Get started in minutes with our simple but powerful platform
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="relative">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#2563EB] text-white text-xl font-bold mb-6">1</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Create Your Project</h3>
                        <p class="text-gray-600 dark:text-gray-300">Choose from our library of project templates or start from scratch. Input your project requirements and specifications.</p>
                    </div>

                    <div class="relative">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#2563EB] text-white text-xl font-bold mb-6">2</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Generate Quote</h3>
                        <p class="text-gray-600 dark:text-gray-300">Our smart estimation engine calculates accurate costs based on your inputs and current market rates.</p>
                    </div>

                    <div class="relative">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#2563EB] text-white text-xl font-bold mb-6">3</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Send Proposal</h3>
                        <p class="text-gray-600 dark:text-gray-300">Generate a professional proposal with your branding, send it to your client, and track when they view it.</p>
                    </div>
                </div>
            </div>

            <!-- Testimonials Section -->
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-24 bg-gray-50 dark:bg-gray-900">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Trusted by Developers</h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                        See what other developers and agencies are saying about Planify
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-12 w-12 rounded-full bg-[#2563EB]/10 flex items-center justify-center">
                                <span class="text-xl font-semibold text-[#2563EB]">JD</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">John Doe</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Freelance Developer</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">"Planify has transformed how I handle client projects. The automated quote generation alone has saved me hours of work."</p>
                            </div>

                    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-12 w-12 rounded-full bg-[#2563EB]/10 flex items-center justify-center">
                                <span class="text-xl font-semibold text-[#2563EB]">AS</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Alice Smith</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Agency Owner</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">"The professional proposals and clear project plans have helped us win more clients and manage projects more effectively."</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-12 w-12 rounded-full bg-[#2563EB]/10 flex items-center justify-center">
                                <span class="text-xl font-semibold text-[#2563EB]">MJ</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">Mike Johnson</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Web Development Team Lead</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">"Finally, a tool that understands the complexities of web development projects. The templates and cost estimation are spot on."</p>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-24">
                <div class="rounded-2xl bg-[#2563EB] px-6 py-16 sm:p-16">
                    <div class="mx-auto max-w-2xl text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-white">Ready to streamline your web projects?</h2>
                        <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-blue-100">
                            Join thousands of developers and agencies who are saving time and winning more clients with Planify.
                        </p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-white px-6 py-3 text-base font-semibold text-[#2563EB] shadow-sm hover:bg-gray-100 transition"
                            >
                                Start Your Free Trial
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mx-auto max-w-7xl px-6 lg:px-8 py-12">
                <div class="border-t border-gray-200 dark:border-gray-800 pt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ new Date().getFullYear() }} Planify. All rights reserved.
                </div>
            </footer>
        </div>
    </div>
</template>
