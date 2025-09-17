<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 flex flex-col items-center justify-center p-4 sm:p-6">
    <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
      <Link href="/">
        <ApplicationLogo class="h-20 w-20 fill-current text-blue-600 dark:text-blue-400" />
      </Link>
    </div>

    <div class="w-full max-w-md bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl shadow-blue-500/10 border border-white/20 dark:border-gray-700/30 overflow-hidden">
      <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-indigo-600">
        <h1 class="text-xl font-bold text-white text-center">
          <slot name="title" />
        </h1>
      </div>
      <div class="p-8">
        <slot />
      </div>
    </div>

    <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
      &copy; {{ new Date().getFullYear() }} Your App. All rights reserved.
    </div>
  </div>
</template>

<style scoped>
/* Smooth animations */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}
</style>