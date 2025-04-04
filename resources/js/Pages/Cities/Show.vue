<!-- resources/js/Pages/Cities/Show.vue -->
<template>
    <AuthenticatedLayout :title="city.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ city.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <Link :href="route('cities.index')" class="text-indigo-600 hover:text-indigo-900">
                                &larr; Back to Cities
                            </Link>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">City Details</h3>
                                <div class="mb-2">
                                    <span class="font-semibold">Name:</span> {{ city.name }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Country:</span>
                                    <Link :href="route('countries.show', city.country.id)" class="text-indigo-600 hover:text-indigo-900">
                                        {{ city.country.name }}
                                    </Link>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Coordinates:</span>
                                    {{ city.latitude ? `${city.latitude}, ${city.longitude}` : 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Population:</span>
                                    {{ city.population ? city.population.toLocaleString() : 'N/A' }}
                                </div>
                            </div>

                            <div v-if="city.attractions && city.attractions.length > 0">
                                <h3 class="text-lg font-semibold mb-4">Attractions</h3>
                                <ul class="list-disc list-inside">
                                    <li v-for="attraction in city.attractions" :key="attraction.id" class="mb-1">
                                        {{ attraction.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-6 flex space-x-3">
                            <Link
                                :href="route('cities.edit', city.id)"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                            >
                                Edit City
                            </Link>
                            <button
                                @click.prevent="destroy"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            >
                                Delete City
                            </button>
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
    city: Object
});

function destroy() {
    if (confirm('Are you sure you want to delete this city?')) {
        router.delete(route('cities.destroy', props.city.id));
    }
}
</script>
