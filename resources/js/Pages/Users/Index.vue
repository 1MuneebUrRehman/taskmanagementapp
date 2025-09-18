<script setup>
import {defineProps, ref} from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  users: Object,
  filters: { type: Object, default: () => ({}) }
});

const search = ref(props.filters?.search ?? '');

function applyFilters() {
  const params = {};
  if (search.value) params.search = search.value;
  router.get(route('users.index'), params, {preserveState: true, replace: true});
}
</script>

<template>
  <Head title="Users" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-violet-600 to-fuchsia-600 bg-clip-text text-transparent">
            User Management
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">
            Super Admin can view and manage all users
          </p>
        </div>
        <Link :href="route('tasks.index')" class="group inline-flex items-center space-x-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105">
          <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Back to Tasks</span>
        </Link>
      </div>
    </template>

    <div class="py-8 mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 dark:border-gray-700/30">
        <div class="p-6 border-b border-white/20 dark:border-gray-700/30">
          <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="relative md:w-1/2">
              <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search name, email, role..." class="w-full px-4 py-2 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:outline-none" />
              <button @click="applyFilters" class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1 rounded-lg bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white text-sm">Search</button>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3" />
              </tr>
            </thead>
            <tbody class="bg-white/50 dark:bg-gray-900/20 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="u in users.data" :key="u.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ u.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ u.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="u.role === 'super-admin' ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'" class="px-3 py-1 rounded-full text-xs font-semibold">{{ u.role || 'user' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                  <!-- Placeholder for future manage actions -->
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="p-4">
          <div class="flex justify-center">
            <Pagination :links="users.links" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
