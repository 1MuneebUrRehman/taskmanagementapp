<script setup>
import { ref, onMounted } from 'vue';
import { currentTheme, toggleTheme } from '@/useTheme.js';

const theme = ref('light');

onMounted(() => {
  theme.value = currentTheme();
});

function onToggle() {
  theme.value = toggleTheme();
}
</script>

<template>
  <button
    type="button"
    class="inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700"
    :aria-label="theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme'"
    @click="onToggle"
  >
    <!-- Sun icon for light mode, show when dark is active to indicate action or vice versa -->
    <svg v-if="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
      <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z"/>
      <path d="M12 2.25a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0V3A.75.75 0 0 1 12 2.25Zm0 17.25a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0V20.25a.75.75 0 0 1 .75-.75Zm8.25-8.25a.75.75 0 0 1 .75.75h1.5a.75.75 0 0 1 0 1.5H21a.75.75 0 0 1-.75-.75.75.75 0 0 1 .75-.75ZM2.25 12a.75.75 0 0 1 .75-.75H4.5a.75.75 0 0 1 0 1.5H3A.75.75 0 0 1 2.25 12Zm13.03 6.22a.75.75 0 0 1 1.06 0l1.06 1.06a.75.75 0 1 1-1.06 1.06l-1.06-1.06a.75.75 0 0 1 0-1.06Zm-8.62-12.38a.75.75 0 0 1 0-1.06L7.72 3.72a.75.75 0 1 1 1.06 1.06L7.72 6.84a.75.75 0 0 1-1.06 0Zm12.38 0a.75.75 0 0 1 1.06 0l1.06-1.06a.75.75 0 1 1 1.06 1.06l-1.06 1.06a.75.75 0 0 1-1.06 0ZM4.22 16.97a.75.75 0 0 1 1.06 0l1.06 1.06a.75.75 0 0 1-1.06 1.06L4.22 18.03a.75.75 0 0 1 0-1.06Z"/>
    </svg>
    <!-- Moon icon for dark mode, show when light is active -->
    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
      <path d="M21.752 15.002A9.718 9.718 0 0 1 12 21.75 9.75 9.75 0 0 1 8.998 2.248a.75.75 0 0 1 .784 1.2A8.25 8.25 0 1 0 20.552 14.22a.75.75 0 0 1 1.2.782Z"/>
    </svg>
  </button>
</template>

