<!-- resources/js/Pages/Countries/Show.vue -->
<template>
    <AuthenticatedLayout :title="country.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ country.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <Link :href="route('countries.index')" class="text-indigo-600 hover:text-indigo-900">
                                &larr; Back to Countries
                            </Link>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Country Details</h3>
                                <div class="mb-2">
                                    <span class="font-semibold">Code:</span> {{ country.code }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Capital:</span> {{ country.capital || 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Region:</span> {{ country.region || 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Coordinates:</span>
                                    {{ country.latitude ? `${country.latitude}, ${country.longitude}` : 'N/A' }}
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-4">Cities</h3>
                                <div v-if="country.cities.length === 0" class="text-gray-500">
                                    No cities available for this country.
                                </div>
                                <ul v-else class="list-disc list-inside">
                                    <li v-for="city in country.cities" :key="city.id" class="mb-1">
                                        {{ city.name }} <span v-if="city.state" class="text-gray-500">({{ city.state }})</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    country: Object
});

function deleteCountry() {
    if (confirm(`Are you sure you want to delete ${props.country.name}?`)) {
        router.delete(route('countries.destroy', props.country.id));
    }
}
</script>
