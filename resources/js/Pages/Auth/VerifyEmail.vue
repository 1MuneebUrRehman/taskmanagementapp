<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  status: {
    type: String,
  },
});

const form = useForm({});

const submit = () => {
  form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
  <GuestLayout>
    <Head title="Email Verification" />
    <template #title>Verify Your Email</template>

    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center">
      Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    <div
        v-if="verificationLinkSent"
        class="mb-6 flex items-center space-x-2 px-4 py-3 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 rounded-xl border border-green-200 dark:border-green-800"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <span class="text-sm font-medium">A new verification link has been sent to the email address you provided during registration.</span>
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
        <PrimaryButton
            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
            :disabled="form.processing"
            class="group relative inline-flex items-center justify-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 disabled:transform-none flex-1"
        >
          <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          <span>{{ form.processing ? 'Sending...' : 'Resend Verification Email' }}</span>
        </PrimaryButton>

        <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105 text-center flex-1"
        >
          Log Out
        </Link>
      </div>
    </form>
  </GuestLayout>
</template>