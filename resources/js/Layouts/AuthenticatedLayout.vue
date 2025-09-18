<script setup>
import {ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import {Link} from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950">
    <!-- Modern Glassmorphism Navigation -->
    <nav class="sticky top-0 z-50 backdrop-blur-xl bg-white/70 dark:bg-gray-900/70 border-b border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 justify-between items-center">
          <div class="flex items-center space-x-8">
            <!-- Enhanced Logo Section -->
            <div class="flex shrink-0 items-center group">
              <Link :href="route('tasks.index')" class="flex items-center space-x-3">
                <div class="relative">
                  <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl blur opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                  <ApplicationLogo class="relative block h-10 w-auto fill-current text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text font-bold" />
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent hidden sm:block">
                                    TaskFlow
                                </span>
              </Link>
            </div>

            <!-- Modern Navigation Links -->
            <div class="hidden md:flex space-x-1">
              <NavLink
                  :active="route().current('tasks.index')"
                  :href="route('tasks.index')"
                  class="px-4 py-2 rounded-xl font-medium transition-all duration-200 hover:bg-white/60 dark:hover:bg-gray-800/60"
                  :class="route().current('tasks.index') ?
                                    'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25' :
                                    'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400'"
              >
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <span>Tasks</span>
                </div>
              </NavLink>

              <template v-if="$page.props.auth.user.role === 'super-admin'">
                <NavLink
                    :active="route().current('users.index')"
                    :href="route('users.index')"
                    class="px-4 py-2 rounded-xl font-medium transition-all duration-200 hover:bg-white/60 dark:hover:bg-gray-800/60"
                    :class="route().current('users.index') ?
                                      'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-fuchsia-500/25' :
                                      'text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-fuchsia-400'"
                >
                  <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m0 0a4 4 0 118 0A4 4 0 017 14.13" />
                    </svg>
                    <span>Users</span>
                  </div>
                </NavLink>
              </template>
            </div>
          </div>

          <!-- Right Side Controls -->
          <div class="hidden sm:flex sm:items-center space-x-4">
            <!-- Enhanced Theme Toggle -->
            <div class="relative">
              <ThemeToggle class="p-2 rounded-xl bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm hover:bg-white/80 dark:hover:bg-gray-700/80 transition-all duration-200" />
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative">
              <Dropdown align="right" width="56">
                <template #trigger>
                  <button class="flex items-center space-x-3 px-4 py-2 rounded-xl bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm hover:bg-white/80 dark:hover:bg-gray-700/80 transition-all duration-200 group">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                      {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden lg:block">
                                            {{ $page.props.auth.user.name }}
                                        </span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-200 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                </template>

                <template #content>
                  <div class="py-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-xl shadow-xl border border-white/20 dark:border-gray-700/30">
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                      <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $page.props.auth.user.name }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">{{ $page.props.auth.user.email }}</p>
                    </div>
                    <DropdownLink :href="route('profile.edit')" class="flex items-center space-x-2 px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-700/50">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      <span>Profile</span>
                    </DropdownLink>
                    <DropdownLink :href="route('logout')" method="post" as="button" class="flex items-center space-x-2 px-4 py-3 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 w-full text-left">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                      </svg>
                      <span>Log Out</span>
                    </DropdownLink>
                  </div>
                </template>
              </Dropdown>
            </div>
          </div>

          <!-- Mobile Menu Button -->
          <div class="flex items-center sm:hidden">
            <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="p-2 rounded-xl bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm hover:bg-white/80 dark:hover:bg-gray-700/80 transition-all duration-200"
            >
              <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path
                    :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                    :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation Menu -->
      <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-t border-white/20 dark:border-gray-700/30">
        <div class="px-4 py-4 space-y-2">
          <ResponsiveNavLink
              :active="route().current('tasks.index')"
              :href="route('tasks.index')"
              class="flex items-center space-x-2 px-4 py-3 rounded-xl"
              :class="route().current('tasks.index') ?
                            'bg-gradient-to-r from-blue-600 to-indigo-600 text-white' :
                            'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800'"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Tasks</span>
          </ResponsiveNavLink>

          <template v-if="$page.props.auth.user.role === 'super-admin'">
            <ResponsiveNavLink
                :active="route().current('users.index')"
                :href="route('users.index')"
                class="flex items-center space-x-2 px-4 py-3 rounded-xl"
                :class="route().current('users.index') ?
                              'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white' :
                              'text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-gray-800'"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m0 0a4 4 0 118 0A4 4 0 017 14.13" />
              </svg>
              <span>Users</span>
            </ResponsiveNavLink>
          </template>
        </div>

        <!-- Mobile User Menu -->
        <div class="border-t border-white/20 dark:border-gray-700/30 px-4 py-4">
          <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
              {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
            </div>
            <div>
              <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ $page.props.auth.user.name }}</div>
              <div class="text-sm text-gray-500 dark:text-gray-400">{{ $page.props.auth.user.email }}</div>
            </div>
          </div>
          <div class="space-y-2">
            <ResponsiveNavLink :href="route('profile.edit')" class="flex items-center space-x-2 px-4 py-3 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-800">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span>Profile</span>
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="flex items-center space-x-2 px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 w-full text-left">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              <span>Log Out</span>
            </ResponsiveNavLink>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Header with Gradient Background -->
    <header v-if="$slots.header" class="relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-indigo-600/10 dark:from-blue-600/5 dark:to-indigo-600/5"></div>
      <div class="relative mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="backdrop-blur-sm bg-white/60 dark:bg-gray-800/60 rounded-2xl p-6 shadow-lg shadow-blue-500/5 border border-white/20 dark:border-gray-700/30">
          <slot name="header" />
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="relative">
      <slot />
    </main>
  </div>
</template>

<style scoped>
/* Custom animations */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>