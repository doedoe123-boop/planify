<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    quotes: {
        type: Object,
        required: true
    }
});

const searchTerm = ref('');
const statusFilter = ref('all');

const statuses = {
    pending: { name: 'Pending', class: 'bg-yellow-50 text-yellow-800 ring-yellow-600/20' },
    approved: { name: 'Approved', class: 'bg-green-50 text-green-800 ring-green-600/20' },
    rejected: { name: 'Rejected', class: 'bg-red-50 text-red-800 ring-red-600/20' },
    default: { name: 'Unknown', class: 'bg-gray-50 text-gray-800 ring-gray-600/20' }
};

const filteredQuotes = computed(() => {
    return props.quotes.data.filter(quote => {
        const matchesSearch = searchTerm.value === '' || 
            quote.project_name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            quote.project_description.toLowerCase().includes(searchTerm.value.toLowerCase());
        
        const matchesStatus = statusFilter.value === 'all' || quote.status === statusFilter.value;
        
        return matchesSearch && matchesStatus;
    });
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

</script>

<template>
    <AppLayout>
        <Head>
            <title>Quotes</title>
        </Head>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="md:flex md:items-center md:justify-between mb-6">
                    <div class="min-w-0 flex-1">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                            Quotes
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage and track all your project quotes in one place
                        </p>
                    </div>
                    <div class="mt-4 flex md:ml-4 md:mt-0">
                        <Link
                            :href="route('quotes.create')"
                            class="ml-3 inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        >
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            New Quote
                        </Link>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row gap-4 sm:items-center">
                            <div class="flex-1">
                                <label for="search" class="sr-only">Search quotes</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="searchTerm"
                                        type="search"
                                        name="search"
                                        id="search"
                                        class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        placeholder="Search quotes..."
                                    />
                                </div>
                            </div>
                            <div class="sm:w-48">
                                <select
                                    v-model="statusFilter"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                                    <option value="all">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quotes List -->
                <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                    <div class="px-4 py-5 sm:p-6">
                        <div v-if="filteredQuotes.length > 0" class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Project</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Website Type</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Hours</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Cost</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="quote in filteredQuotes" :key="quote.id" class="hover:bg-gray-50">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-0">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ quote.project_name }}</div>
                                                        {{ quote.project_description.length > 50 ? quote.project_description.substring(0, 50) + '...' : quote.project_description }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <div class="text-gray-900">{{ quote.website_type.name }}</div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ quote.total_hours }} hours
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <div class="text-gray-900 font-medium">{{ formatCurrency(quote.total_cost) }}</div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <span :class="[
                                                    (statuses[quote.status] || statuses.default).class,
                                                    'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset'
                                                ]">
                                                    {{ (statuses[quote.status] || statuses.default).name }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ formatDate(quote.created_at) }}
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                <Link :href="route('quotes.show', quote.id)" class="text-blue-600 hover:text-blue-900">
                                                    View<span class="sr-only">, {{ quote.project_name }}</span>
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No quotes found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ searchTerm || statusFilter !== 'all' ? 'Try adjusting your search or filter to find what you\'re looking for.' : 'Get started by creating a new quote.' }}
                            </p>
                            <div class="mt-6" v-if="!searchTerm && statusFilter === 'all'">
                                <Link
                                    :href="route('quotes.create')"
                                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                >
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                    </svg>
                                    Create New Quote
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="quotes.links && quotes.links.length > 3" class="mt-6">
                    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                        <div class="flex flex-1 justify-between sm:hidden">
                            <Link v-if="quotes.prev_page_url"
                                :href="quotes.prev_page_url"
                                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Previous
                            </Link>
                            <Link v-if="quotes.next_page_url"
                                :href="quotes.next_page_url"
                                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Next
                            </Link>
                        </div>
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ quotes.from }}</span>
                                    to
                                    <span class="font-medium">{{ quotes.to }}</span>
                                    of
                                    <span class="font-medium">{{ quotes.total }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    <Link v-for="(link, index) in quotes.links"
                                        :key="index"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                        :class="{
                                            'z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600': link.active,
                                            'cursor-not-allowed': !link.url,
                                        }"
                                    />
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 