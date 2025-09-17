<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const user = usePage().props.auth.user;

const form = useForm({
  name: user.name,
  email: user.email,
});
</script>

<template>
  <section class="space-y-6">
    <header>
      <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span>Profile Information</span>
      </h2>

      <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        Update your account's profile information and email address.
      </p>
    </header>

    <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6">
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
          />
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
        </div>

        <InputError :message="form.errors.email" class="text-sm text-red-600 dark:text-red-400" />
      </div>

      <!-- Email Verification Notice -->
      <div v-if="mustVerifyEmail && user.email_verified_at === null" class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-800">
        <p class="text-sm text-yellow-800 dark:text-yellow-200">
          Your email address is unverified.
          <Link
              :href="route('verification.send')"
              method="post"
              as="button"
              class="ml-1 underline hover:text-yellow-900 dark:hover:text-yellow-100 transition-colors duration-200"
          >
            Click here to re-send the verification email.
          </Link>
        </p>

        <div
            v-show="status === 'verification-link-sent'"
            class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
        >
          A new verification link has been sent to your email address.
        </div>
      </div>

      <!-- Submit Button -->
      <div class="flex items-center gap-4">
        <PrimaryButton
            :disabled="form.processing"
            class="group relative inline-flex items-center justify-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
        >
          <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <span>{{ form.processing ? 'Saving...' : 'Save Changes' }}</span>
        </PrimaryButton>

        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 transform scale-90"
            enter-to-class="opacity-100 transform scale-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 transform scale-100"
            leave-to-class="opacity-0 transform scale-90"
        >
          <div v-if="form.recentlySuccessful" class="flex items-center space-x-2 px-4 py-2 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 rounded-xl border border-green-200 dark:border-green-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-sm font-medium">Profile updated successfully!</span>
          </div>
        </Transition>
      </div>
    </form>
  </section>
</template>

<style scoped>
/* Custom focus styles */
.focus\:ring-4:focus {
  --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
  --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(4px + var(--tw-ring-offset-width)) var(--tw-ring-color);
  box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
}

/* Smooth animations */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}
</style>