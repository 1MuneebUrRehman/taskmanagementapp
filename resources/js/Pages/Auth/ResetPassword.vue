<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />
    <template #title>Reset Password</template>

    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
      Create a new password for your account.
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <!-- Email Field (hidden) -->
      <input type="hidden" v-model="form.email" />

      <!-- Password Field -->
      <div class="space-y-2">
        <InputLabel for="password" value="New Password" class="text-sm font-medium text-gray-700 dark:text-gray-300" />

        <div class="relative">
          <TextInput
              id="password"
              type="password"
              v-model="form.password"
              class="w-full pl-12 pr-4 py-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 transition-all duration-200 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:border-blue-500 dark:focus:border-blue-400"
              required
              autocomplete="new-password"
              autofocus
              placeholder="Enter your new password"
          />
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
        </div>

        <InputError :message="form.errors.password" class="text-sm text-red-600 dark:text-red-400" />
      </div>

      <!-- Confirm Password Field -->
      <div class="space-y-2">
        <InputLabel for="password_confirmation" value="Confirm Password" class="text-sm font-medium text-gray-700 dark:text-gray-300" />

        <div class="relative">
          <TextInput
              id="password_confirmation"
              type="password"
              v-model="form.password_confirmation"
              class="w-full pl-12 pr-4 py-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 transition-all duration-200 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:border-blue-500 dark:focus:border-blue-400"
              required
              autocomplete="new-password"
              placeholder="Confirm your new password"
          />
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>

        <InputError :message="form.errors.password_confirmation" class="text-sm text-red-600 dark:text-red-400" />
      </div>

      <!-- Submit Button -->
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ form.processing ? 'Resetting...' : 'Reset Password' }}</span>
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>