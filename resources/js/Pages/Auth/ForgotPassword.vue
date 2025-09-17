<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>
  <GuestLayout>
    <Head title="Forgot Password" />
    <template #title>Reset Password</template>

    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
      Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </div>

    <div
        v-if="status"
        class="mb-6 flex items-center space-x-2 px-4 py-3 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 rounded-xl border border-green-200 dark:border-green-800"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <span class="text-sm font-medium">{{ status }}</span>
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <div class="space-y-2">
        <InputLabel for="email" value="Email" class="text-sm font-medium text-gray-700 dark:text-gray-300" />

        <div class="relative">
          <TextInput
              id="email"
              type="email"
              v-model="form.email"
              class="w-full pl-12 pr-4 py-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 transition-all duration-200 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:border-blue-500 dark:focus:border-blue-400"
              required
              autofocus
              autocomplete="username"
              placeholder="Enter your email address"
          />
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
        </div>

        <InputError :message="form.errors.email" class="text-sm text-red-600 dark:text-red-400" />
      </div>

      <div class="flex justify-center">
        <PrimaryButton
            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
            :disabled="form.processing"
            class="group relative inline-flex items-center justify-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 disabled:transform-none"
        >
          <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          <span>{{ form.processing ? 'Sending...' : 'Send Reset Link' }}</span>
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>