<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />
    <template #title>Create Account</template>

    <form @submit.prevent="submit" class="space-y-6">
      <!-- Name Field -->
      <div class="space-y-2">
        <InputLabel for="name" value="Name" class="text-sm font-medium text-gray-700 dark:text-gray-300" />

        <div class="relative">
          <TextInput
              id="name"
              type="text"
              v-model="form.name"
              class="w-full pl-12 pr-4 py-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 transition-all duration-200 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:border-blue-500 dark:focus:border-blue-400"
              required
              autofocus
              autocomplete="name"
              placeholder="Enter your full name"
          />
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>

        <InputError :message="form.errors.name" class="text-sm text-red-600 dark:text-red-400" />
      </div>

      <!-- Email Field -->
      <div class="space-y-2">
        <InputLabel for="email" value="Email" class="text-sm font-medium text-gray-700 dark:text-gray-300" />

        <div class="relative">
          <TextInput
              id="email"
              type="email"
              v-model="form.email"
              class="w-full pl-12 pr-4 py-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 transition-all duration-200 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:border-blue-500 dark:focus:border-blue-400"
              required
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

      <!-- Password Field -->
      <div class="space-y-2">
        <InputLabel for="password" value="Password" class="text-sm font-medium text-gray-700 dark:text-gray-300" />

        <div class="relative">
          <TextInput
              id="password"
              type="password"
              v-model="form.password"
              class="w-full pl-12 pr-4 py-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 transition-all duration-200 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:border-blue-500 dark:focus:border-blue-400"
              required
              autocomplete="new-password"
              placeholder="Create a strong password"
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
              placeholder="Confirm your password"
          />
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 01118 0z" />
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
            class="group relative inline-flex items-center justify-center space-x-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 disabled:transform-none w-full justify-center"
        >
          <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
          <span>{{ form.processing ? 'Creating Account...' : 'Create Account' }}</span>
        </PrimaryButton>
      </div>

      <!-- Login Link -->
      <div class="text-center text-sm text-gray-600 dark:text-gray-400">
        Already have an account?
        <Link
            :href="route('login')"
            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors duration-200"
        >
          Sign in
        </Link>
      </div>
    </form>
  </GuestLayout>
</template>