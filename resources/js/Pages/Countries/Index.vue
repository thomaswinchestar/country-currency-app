<!-- resources/js/Pages/Countries/Index.vue -->
<template>
    <AuthenticatedLayout title="Countries">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Countries</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">Countries</h2>
                            <Link :href="route('countries.create')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                                Add Country
                            </Link>
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center">
                                <div class="relative flex-grow">
                                    <input
                                        v-model="search"
                                        type="text"
                                        placeholder="Search by country name, code, capital, or region..."
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

                        <div v-if="$page.props.flash && $page.props.flash.message" class="bg-green-100 p-4 mb-4 rounded">
                            {{ $page.props.flash.message }}
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capital</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="country in countries.data" :key="country.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Link :href="route('countries.show', country.id)" class="text-indigo-600 hover:text-indigo-900">
                                        {{ country.name }}
                                    </Link>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ country.code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ country.capital || 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ country.region || 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="route('countries.edit', country.id)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        Edit
                                    </Link>
                                    <button @click="deleteCountry(country)" class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="countries.data && countries.data.length === 0">
                                <td class="px-6 py-4 whitespace-nowrap text-center" colspan="5">No countries found.</td>
                            </tr>
                            </tbody>
                        </table>

                        <Pagination :links="countries.links" class="mt-6" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    countries: Object,
    filters: Object
});

const search = ref(props.filters?.search || '');

function performSearch() {
    router.get(
        route('countries.index'),
        { search: search.value },
        { preserveState: true, replace: true }
    );
}

function clearSearch() {
    search.value = '';
    performSearch();
}

function deleteCountry(country) {
    if (confirm(`Are you sure you want to delete ${country.name}?`)) {
        router.delete(route('countries.destroy', country.id));
    }
}
</script>
