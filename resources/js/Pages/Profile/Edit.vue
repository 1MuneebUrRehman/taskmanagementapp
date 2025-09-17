<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
            Profile Settings
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">
            Manage your account settings and preferences
          </p>
        </div>
      </div>
    </template>

    <div class="py-8 mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <!-- Profile Information Card -->
      <div class="mb-8 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg shadow-blue-500/5 border border-white/20 dark:border-gray-700/30 overflow-hidden">
        <!-- Card Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-white/20 dark:border-gray-700/30">
          <div class="flex items-center space-x-3">
            <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Profile Information</h2>
              <p class="text-sm text-gray-600 dark:text-gray-400">Update your account's profile information and email address</p>
            </div>
          </div>
        </div>
        <!-- Form Content -->
        <div class="p-8">
          <UpdateProfileInformationForm
              :must-verify-email="mustVerifyEmail"
              :status="status"
              class="max-w-xl"
          />
        </div>
      </div>

      <!-- Update Password Card -->
      <div class="mb-8 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg shadow-blue-500/5 border border-white/20 dark:border-gray-700/30 overflow-hidden">
        <!-- Card Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-white/20 dark:border-gray-700/30">
          <div class="flex items-center space-x-3">
            <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Update Password</h2>
              <p class="text-sm text-gray-600 dark:text-gray-400">Ensure your account is using a long, random password to stay secure</p>
            </div>
          </div>
        </div>
        <!-- Form Content -->
        <div class="p-8">
          <UpdatePasswordForm class="max-w-xl" />
        </div>
      </div>

      <!-- Delete Account Card -->
      <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg shadow-red-500/5 border border-white/20 dark:border-gray-700/30 overflow-hidden">
        <!-- Card Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border-b border-white/20 dark:border-gray-700/30">
          <div class="flex items-center space-x-3">
            <div class="p-3 bg-gradient-to-r from-red-600 to-pink-600 rounded-xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Delete Account</h2>
              <p class="text-sm text-gray-600 dark:text-gray-400">Permanently delete your account</p>
            </div>
          </div>
        </div>
        <!-- Form Content -->
        <div class="p-8">
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>

      <!-- Tips Card -->
      <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 backdrop-blur-sm rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
        <div class="flex items-start space-x-3">
          <div class="p-2 bg-blue-100 dark:bg-blue-800/30 rounded-lg">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-1">🔒 Security Tips</h3>
            <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-1">
              <li>• Use a strong, unique password for your account</li>
              <li>• Enable two-factor authentication for added security</li>
              <li>• Keep your email address up to date for account recovery</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Custom animations and effects */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}
</style>