<!-- resources/js/Pages/Countries/Create.vue -->
<template>
    <AuthenticatedLayout title="Create Country">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Country</h2>
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
                                <Label for="code" value="Code (3 letters)" />
                                <TextInput
                                    id="code"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.code"
                                    required
                                    maxlength="3"
                                />
                                <InputError class="mt-2" :message="form.errors.code" />
                            </div>

                            <div class="mb-4">
                                <Label for="capital" value="Capital" />
                                <TextInput
                                    id="capital"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.capital"
                                />
                                <InputError class="mt-2" :message="form.errors.capital" />
                            </div>

                            <div class="mb-4">
                                <Label for="region" value="Region" />
                                <TextInput
                                    id="region"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.region"
                                />
                                <InputError class="mt-2" :message="form.errors.region" />
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
                                    :href="route('countries.index')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 mr-3"
                                >
                                    Cancel
                                </Link>

                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Create
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

const form = useForm({
    name: '',
    code: '',
    capital: '',
    region: '',
    latitude: '',
    longitude: '',
});

function submit() {
    form.post(route('countries.store'));
}
</script>
