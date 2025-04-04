<!-- resources/js/Pages/Currencies/Index.vue -->
<template>
    <AuthenticatedLayout title="Currencies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Currencies</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">Currencies</h2>
                            <Link
                                :href="route('currencies.create')"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                            >
                                Add Currency
                            </Link>
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center">
                                <div class="relative flex-grow">
                                    <input
                                        v-model="search"
                                        type="text"
                                        placeholder="Search by keywords, currency name, or code..."
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        @keyup.enter="performSearch"
                                    />
                                    <button 
                                        v-if="search"
                                        @click="clearSearch"
                                        class="absolute right-10 top-2.5 text-gray-500 hover:text-gray-700"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <button
                                    @click="performSearch"
                                    class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 focus:outline-none"
                                >
                                    Search
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto bg-white rounded shadow">
                            <table class="w-full whitespace-nowrap">
                                <thead>
                                <tr class="text-left font-bold">
                                    <th class="px-6 pt-5 pb-4">Currency Name</th>
                                    <th class="px-6 pt-5 pb-4">Code</th>
                                    <th class="px-6 pt-5 pb-4">Rate</th>
                                    <th class="px-6 pt-5 pb-4">Last Updated</th>
                                    <th class="px-6 pt-5 pb-4">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="currency in currencies.data" :key="currency.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                    <td class="border-t px-6 py-4">{{ currency.name }}</td>
                                    <td class="border-t px-6 py-4">{{ currency.code }}</td>
                                    <td class="border-t px-6 py-4">{{ currency.rate || 'N/A' }}</td>
                                    <td class="border-t px-6 py-4">{{ currency.last_updated || 'N/A' }}</td>
                                    <td class="border-t px-6 py-4">
                                        <Link
                                            :href="route('currencies.show', currency.id)"
                                            class="text-blue-600 hover:text-blue-900 mr-2"
                                        >
                                            View
                                        </Link>
                                        <Link
                                            :href="route('currencies.edit', currency.id)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            @click.prevent="destroy(currency.id)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="currencies.data && currencies.data.length === 0">
                                    <td class="border-t px-6 py-4" colspan="5">No currencies found.</td>
                                </tr>
                                </tbody>
                            </table>
                            <Pagination :links="currencies.links" class="mt-6" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    currencies: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

function performSearch() {
    router.get(
        route('currencies.index'),
        { search: search.value },
        { preserveState: true, replace: true }
    );
}

function clearSearch() {
    search.value = '';
    performSearch();
}

function destroy(id) {
    if (confirm('Are you sure you want to delete this currency?')) {
        router.delete(route('currencies.destroy', id));
    }
}
</script>
