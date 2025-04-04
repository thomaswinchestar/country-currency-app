<!-- resources/js/Pages/Cities/Edit.vue -->
<template>
    <AuthenticatedLayout title="Edit City">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit City</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <Label for="name" value="Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mb-4">
                                <Label for="country_id" value="Country" />
                                <select
                                    id="country_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    v-model="form.country_id"
                                    required
                                >
                                    <option value="">Select a country</option>
                                    <option v-for="country in countries" :key="country.id" :value="country.id">
                                        {{ country.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.country_id" />
                            </div>

                            <div class="mb-4">
                                <Label for="latitude" value="Latitude" />
                                <TextInput
                                    id="latitude"
                                    type="number"
                                    step="0.000001"
                                    class="mt-1 block w-full"
                                    v-model="form.latitude"
                                />
                                <InputError class="mt-2" :message="form.errors.latitude" />
                            </div>

                            <div class="mb-4">
                                <Label for="longitude" value="Longitude" />
                                <TextInput
                                    id="longitude"
                                    type="number"
                                    step="0.000001"
                                    class="mt-1 block w-full"
                                    v-model="form.longitude"
                                />
                                <InputError class="mt-2" :message="form.errors.longitude" />
                            </div>



                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('cities.index')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 mr-3"
                                >
                                    Cancel
                                </Link>

                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Update
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    city: Object,
    countries: Array,
});

const form = useForm({
    name: props.city.name,
    country_id: props.city.country_id,
    latitude: props.city.latitude || '',
    longitude: props.city.longitude || '',
});

function submit() {
    form.put(route('cities.update', props.city.id));
}
</script>
