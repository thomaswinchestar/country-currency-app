<!-- resources/js/Pages/Currencies/Edit.vue -->
<template>
    <AuthenticatedLayout title="Edit Currency">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Currency</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <Label for="name" value="Currency Name" />
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
                                <Label for="rate" value="Rate" />
                                <TextInput
                                    id="rate"
                                    type="number"
                                    step="0.0001"
                                    class="mt-1 block w-full"
                                    v-model="form.rate"
                                />
                                <InputError class="mt-2" :message="form.errors.rate" />
                            </div>

                            <div class="mb-4">
                                <Label for="decimal_places" value="Decimal Places" />
                                <TextInput
                                    id="decimal_places"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.decimal_places"
                                    min="0"
                                    max="10"
                                />
                                <InputError class="mt-2" :message="form.errors.decimal_places" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('currencies.index')"
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
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    currency: Object,
});

const form = useForm({
    name: props.currency.name,
    code: props.currency.code,
    rate: props.currency.rate || null,
    decimal_places: props.currency.decimal_places || 2,
});

function submit() {
    form.put(route('currencies.update', props.currency.id));
}
</script>
