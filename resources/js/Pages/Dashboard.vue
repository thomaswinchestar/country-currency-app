<!-- resources/js/Pages/Dashboard.vue -->
<template>
    <AuthenticatedLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Welcome to the Country and Currency Manager</h3>
                        <p class="mb-4">Use the links below to manage your data:</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded shadow">
                                <h4 class="font-medium text-indigo-600 mb-2">Countries</h4>
                                <p class="text-sm text-gray-600 mb-3">Manage countries and their details.</p>
                                <Link :href="route('countries.index')" class="text-indigo-600 hover:text-indigo-900">
                                    View Countries &rarr;
                                </Link>
                            </div>

                            <div class="bg-gray-50 p-4 rounded shadow">
                                <h4 class="font-medium text-indigo-600 mb-2">Cities</h4>
                                <p class="text-sm text-gray-600 mb-3">Manage cities and their details.</p>
                                <Link :href="route('cities.index')" class="text-indigo-600 hover:text-indigo-900">
                                    View Cities &rarr;
                                </Link>
                            </div>

                            <div class="bg-gray-50 p-4 rounded shadow">
                                <h4 class="font-medium text-indigo-600 mb-2">Currencies</h4>
                                <p class="text-sm text-gray-600 mb-3">Manage currencies and exchange rates.</p>
                                <Link :href="route('currencies.index')" class="text-indigo-600 hover:text-indigo-900">
                                    View Currencies &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

</script>
