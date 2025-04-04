<!-- resources/js/Pages/Currencies/Show.vue -->
<template>
    <AuthenticatedLayout :title="currency.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ currency.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <Link :href="route('currencies.index')" class="text-indigo-600 hover:text-indigo-900">
                                &larr; Back to Currencies
                            </Link>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Currency Details</h3>
                                <div class="mb-2">
                                    <span class="font-semibold">Currency Name:</span> {{ currency.name }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Code:</span> {{ currency.code }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Rate (USD to {{ currency.code }}):</span> {{ currency.rate || 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Decimal Places:</span> {{ currency.decimal_places || '2' }}
                                </div>
                            </div>

                            <div v-if="currency.countries && currency.countries.length > 0">
                                <h3 class="text-lg font-semibold mb-4">Countries Using This Currency</h3>
                                <ul class="list-disc list-inside">
                                    <li v-for="country in currency.countries" :key="country.id" class="mb-1">
                                        <Link :href="route('countries.show', country.id)" class="text-indigo-600 hover:text-indigo-900">
                                            {{ country.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-6 flex space-x-3">
                            <Link
                                :href="route('currencies.edit', currency.id)"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                            >
                                Edit Currency
                            </Link>
                            <button
                                @click.prevent="destroy"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            >
                                Delete Currency
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
    currency: Object
});

function destroy() {
    if (confirm('Are you sure you want to delete this currency?')) {
        router.delete(route('currencies.destroy', props.currency.id));
    }
}
</script>
