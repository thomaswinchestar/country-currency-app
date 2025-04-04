<!-- resources/js/Pages/TwoFactor/Index.vue -->
<template>
    <GuestLayout>
        <Head title="Two Factor Authentication" />

        <div class="mb-4 text-sm text-gray-600">
            <h2 class="text-lg font-medium text-gray-900 mb-2">Email Verification Required</h2>
            <p>A verification code has been sent to your email address. Please enter the 6-digit code to continue.</p>
            <p class="mt-1 text-xs">This additional security step helps protect your account.</p>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <Label for="code" value="Verification Code" />
                <TextInput
                    id="code"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.code"
                    required
                    autofocus
                    autocomplete="off"
                />
                <InputError class="mt-2" :message="form.errors.code" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Verify Code
                </PrimaryButton>

                <div class="flex flex-col space-y-2">
                    <Link :href="route('two-factor.resend')" method="post" as="button" class="text-sm text-gray-600 hover:text-gray-900">
                        Resend Code
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="text-xs text-red-600 hover:text-red-900">
                        Cancel & Logout
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

// Define props
defineProps({
    status: String,
});

// Setup form
const form = useForm({
    code: '',
});

// Submit function
const submit = () => {
    form.post(route('two-factor.store'));
};
</script>
