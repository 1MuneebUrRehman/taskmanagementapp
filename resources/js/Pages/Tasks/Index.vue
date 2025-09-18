<script setup>
import {defineProps, ref, computed} from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";

const props = defineProps({
  tasks: Object,
  filters: {
    type: Object,
    default: () => ({})
  },
  users: {
    type: Array,
    default: () => []
  }
});

const form = useForm({});

// Filters state initialized from server-provided filters
const filters = ref({
  status: props.filters?.status ?? '',
  priority: props.filters?.priority ?? '',
  archived: props.filters?.archived ?? '',
  assigned_to: props.filters?.assigned_to ?? '',
  due_date: props.filters?.due_date ?? '',
  search: props.filters?.search ?? ''
});

// View mode toggle
const viewMode = ref('grid'); // grid or list

const applyFilters = () => {
  const params = {};
  if (filters.value.status) params.status = filters.value.status;
  if (filters.value.priority) params.priority = filters.value.priority;
  if (filters.value.archived !== '') params.archived = filters.value.archived;
  if (filters.value.assigned_to) params.assigned_to = filters.value.assigned_to;
  if (filters.value.due_date) params.due_date = filters.value.due_date;
  if (filters.value.search) params.search = filters.value.search;
  router.get(route('tasks.index'), params, { preserveState: true, replace: true });
};

const clearFilters = () => {
  filters.value = { status: '', priority: '', archived: '', assigned_to: '', due_date: '', search: '' };
  applyFilters();
};

const confirmingTaskDeletion = ref(false);
const taskToDelete = ref(null);
const confirmTaskDeletion = (id) => {
  confirmingTaskDeletion.value = true;
  taskToDelete.value = id;
};

const closeModal = () => {
  confirmingTaskDeletion.value = false;
  taskToDelete.value = null;
};

const deleteTask = () => {
  form.delete(route('tasks.destroy', {task: taskToDelete.value}), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
  });
};

// Computed properties for statistics
const completedTasks = computed(() => {
  return props.tasks?.data ? props.tasks.data.filter(task => task.is_completed).length : 0;
});

const pendingTasks = computed(() => {
  return props.tasks?.data ? props.tasks.data.filter(task => !task.is_completed).length : 0;
});

const urgentTasks = computed(() => {
  return props.tasks?.data ? props.tasks.data.filter(task => task.priority === 'urgent' && !task.is_completed).length : 0;
});

const overdueTasks = computed(() => {
  const now = new Date();
  return props.tasks?.data ? props.tasks.data.filter(task => {
    if (!task.due_date || task.is_completed) return false;
    return new Date(task.due_date) < now;
  }).length : 0;
});

// Helper functions
const getPriorityColor = (priority) => {
  const colors = {
    low: 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-800',
    normal: 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700/50 dark:text-gray-300 dark:border-gray-600',
    high: 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-300 dark:border-yellow-800',
    urgent: 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-300 dark:border-red-800'
  };
  return colors[priority] || colors.normal;
};

const getStatusColor = (status) => {
  const colors = {
    pending: 'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-900/20 dark:text-orange-300 dark:border-orange-800',
    in_progress: 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-800',
    completed: 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-300 dark:border-green-800',
    blocked: 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-300 dark:border-red-800'
  };
  return colors[status] || colors.pending;
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
};

const isOverdue = (dueDate, isCompleted) => {
  if (!dueDate || isCompleted) return false;
  return new Date(dueDate) < new Date();
};

const getUserName = (userId) => {
  const user = props.users.find(u => u.id === userId);
  return user ? user.name : 'Unassigned';
};
</script>

<template>
  <Head title="Tasks"/>

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
        <div class="flex-1">
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent">
            Task Management
          </h1>
          <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-2">
            Organize, track, and collaborate on your tasks efficiently
          </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
          <!-- View Toggle -->
          <div class="flex bg-white/70 dark:bg-gray-800/70 rounded-xl p-1 border border-white/20 dark:border-gray-700/30">
            <button
                @click="viewMode = 'grid'"
                :class="[
                'flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                viewMode === 'grid'
                  ? 'bg-blue-600 text-white shadow-lg'
                  : 'text-gray-600 dark:text-gray-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20'
              ]"
            >
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
              Grid
            </button>
            <button
                @click="viewMode = 'list'"
                :class="[
                'flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                viewMode === 'list'
                  ? 'bg-blue-600 text-white shadow-lg'
                  : 'text-gray-600 dark:text-gray-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20'
              ]"
            >
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
              List
            </button>
          </div>

          <Link
              :href="route('tasks.create')"
              class="group relative inline-flex items-center justify-center space-x-2 px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 w-full sm:w-auto"
          >
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Create Task</span>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-4 sm:py-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Enhanced Statistics Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
              <p class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ tasks.total || 0 }}</p>
            </div>
            <div class="p-2 sm:p-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg sm:rounded-xl">
              <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-green-500/5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
              <p class="text-xl sm:text-3xl font-bold text-green-600 dark:text-green-400">{{ completedTasks }}</p>
            </div>
            <div class="p-2 sm:p-3 bg-gradient-to-r from-green-500 to-green-600 rounded-lg sm:rounded-xl">
              <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-orange-500/5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
              <p class="text-xl sm:text-3xl font-bold text-orange-600 dark:text-orange-400">{{ pendingTasks }}</p>
            </div>
            <div class="p-2 sm:p-3 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg sm:rounded-xl">
              <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-red-500/5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Overdue</p>
              <p class="text-xl sm:text-3xl font-bold text-red-600 dark:text-red-400">{{ overdueTasks }}</p>
            </div>
            <div class="p-2 sm:p-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg sm:rounded-xl">
              <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Filters Bar -->
      <div class="mb-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5">
        <!-- Search Bar -->
        <div class="mb-4">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
                v-model="filters.search"
                @input="applyFilters"
                type="text"
                placeholder="Search tasks..."
                class="block w-full pl-10 pr-3 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-white/60 dark:bg-gray-800/60 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
        </div>

        <!-- Filter Controls -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
            <select v-model="filters.status" @change="applyFilters" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white/60 dark:bg-gray-800/60 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="blocked">Blocked</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
            <select v-model="filters.priority" @change="applyFilters" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white/60 dark:bg-gray-800/60 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Priority</option>
              <option value="low">Low</option>
              <option value="normal">Normal</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assigned To</label>
            <select v-model="filters.assigned_to" @change="applyFilters" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white/60 dark:bg-gray-800/60 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Assignees</option>
              <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date</label>
            <select v-model="filters.due_date" @change="applyFilters" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white/60 dark:bg-gray-800/60 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Dates</option>
              <option value="overdue">Overdue</option>
              <option value="today">Due Today</option>
              <option value="this_week">This Week</option>
              <option value="next_week">Next Week</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Archive</label>
            <select v-model="filters.archived" @change="applyFilters" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white/60 dark:bg-gray-800/60 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">All Tasks</option>
              <option :value="false">Active Only</option>
              <option :value="true">Archived Only</option>
            </select>
          </div>

          <div class="flex items-end">
            <button
                @click="clearFilters"
                class="w-full px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium transition-all duration-200 hover:scale-105"
            >
              Clear Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Tasks Grid/List -->
      <div v-if="tasks.data && tasks.data.length > 0" class="space-y-4">
        <!-- Tasks Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 space-y-2 sm:space-y-0">
          <h2 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-200">Your Tasks</h2>
          <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <span>{{ tasks.from }}-{{ tasks.to }} of {{ tasks.total }} tasks</span>
          </div>
        </div>

        <!-- Grid View -->
        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
          <div
              v-for="task in tasks.data"
              :key="task.id"
              class="group bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 hover:transform hover:-translate-y-1"
          >
            <!-- Task Header -->
            <div class="flex items-start justify-between mb-3">
              <div class="flex-1 min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                  {{ task.title }}
                </h3>
              </div>
              <div class="flex items-center space-x-1 ml-2">
                <span :class="['inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border', getPriorityColor(task.priority)]">
                  {{ task.priority }}
                </span>
              </div>
            </div>

            <!-- Task Description -->
            <p v-if="task.description" class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
              {{ task.description }}
            </p>

            <!-- Task Meta Info -->
            <div class="space-y-2 mb-4">
              <!-- Status -->
              <div class="flex items-center justify-between">
                <span :class="['inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border', getStatusColor(task.status)]">
                  <div v-if="task.is_completed" class="w-2 h-2 bg-current rounded-full mr-1"></div>
                  <div v-else class="w-2 h-2 bg-current rounded-full mr-1 animate-pulse"></div>
                  {{ task.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                </span>
              </div>

              <!-- Due Date -->
              <div v-if="task.due_date" class="flex items-center text-sm">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span :class="[isOverdue(task.due_date, task.is_completed) ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400']">
                  {{ formatDate(task.due_date) }}
                </span>
                <span v-if="isOverdue(task.due_date, task.is_completed)" class="ml-1 text-xs text-red-600 dark:text-red-400 font-medium">
                  (Overdue)
                </span>
              </div>

              <!-- Assignee -->
              <div v-if="task.assigned_to" class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ getUserName(task.assigned_to) }}
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-3 border-t border-gray-200/50 dark:border-gray-700/50">
              <div class="flex items-center space-x-1">
                <Link
                    :href="route('tasks.show', task)"
                    class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200"
                    title="View Task"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </Link>

                <Link
                    :href="route('tasks.edit', task)"
                    class="p-2 text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all duration-200"
                    title="Edit Task"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </Link>

                <button
                    @click="confirmTaskDeletion(task.id)"
                    class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200"
                    title="Delete Task"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>

              <!-- Progress indicator for completed tasks -->
              <div v-if="task.is_completed" class="flex items-center text-green-600 dark:text-green-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- List View -->
        <div v-else class="space-y-3">
          <div
              v-for="task in tasks.data"
              :key="task.id"
              class="group bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-xl p-4 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300"
          >
            <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-3 sm:space-y-0">
              <div class="flex items-start space-x-4 flex-1 min-w-0">
                <!-- Task Status Indicator -->
                <div class="flex-shrink-0 mt-1">
                  <div v-if="task.is_completed" class="w-6 h-6 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <div v-else class="w-6 h-6 border-2 border-orange-300 dark:border-orange-500 rounded-full flex items-center justify-center bg-orange-50 dark:bg-orange-900/20">
                    <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                  </div>
                </div>

                <!-- Task Content -->
                <div class="flex-1 min-w-0">
                  <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ task.title }}
                  </h3>

                  <p v-if="task.description" class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-1">
                    {{ task.description }}
                  </p>

                  <!-- Meta Information -->
                  <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-sm">
                    <!-- Status Badge -->
                    <span :class="['inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border', getStatusColor(task.status)]">
                      {{ task.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                    </span>

                    <!-- Priority Badge -->
                    <span :class="['inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border', getPriorityColor(task.priority)]">
                      {{ task.priority }}
                    </span>

                    <!-- Due Date -->
                    <div v-if="task.due_date" class="flex items-center text-gray-600 dark:text-gray-400">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <span :class="[isOverdue(task.due_date, task.is_completed) ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-600 dark:text-gray-400']">
                        {{ formatDate(task.due_date) }}
                      </span>
                      <span v-if="isOverdue(task.due_date, task.is_completed)" class="ml-1 text-xs text-red-600 dark:text-red-400 font-medium">
                        (Overdue)
                      </span>
                    </div>

                    <!-- Assignee -->
                    <div v-if="task.assigned_to" class="flex items-center text-gray-600 dark:text-gray-400">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      {{ getUserName(task.assigned_to) }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-center space-x-2 flex-shrink-0">
                <Link
                    :href="route('tasks.show', task)"
                    class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-all duration-200"
                    title="View Task"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </Link>

                <Link
                    :href="route('tasks.edit', task)"
                    class="p-2 text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all duration-200"
                    title="Edit Task"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </Link>

                <button
                    @click="confirmTaskDeletion(task.id)"
                    class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200"
                    title="Delete Task"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Empty State -->
      <div v-else class="text-center py-12 sm:py-16">
        <div class="mx-auto w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full flex items-center justify-center mb-6">
          <svg class="w-10 h-10 sm:w-12 sm:h-12 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">No tasks found</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm sm:text-base max-w-md mx-auto">
          <span v-if="Object.values(filters).some(filter => filter !== '')">
            No tasks match your current filters. Try adjusting your search criteria.
          </span>
          <span v-else>
            Get started by creating your first task to stay organized and productive.
          </span>
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-4">
          <Link
              :href="route('tasks.create')"
              class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25 w-full sm:w-auto justify-center"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Create Your First Task</span>
          </Link>
          <button
              v-if="Object.values(filters).some(filter => filter !== '')"
              @click="clearFilters"
              class="inline-flex items-center space-x-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105 w-full sm:w-auto justify-center"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span>Clear Filters</span>
          </button>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="tasks.data && tasks.data.length > 0" class="mt-6 sm:mt-8 flex justify-center">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-xl sm:rounded-2xl p-4 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5 w-full sm:w-auto">
          <pagination :links="tasks.links" />
        </div>
      </div>

      <!-- Enhanced Delete Confirmation Modal -->
      <Modal :show="confirmingTaskDeletion" @close="closeModal" max-width="md">
        <div class="relative p-6 sm:p-8">
          <!-- Modal Header -->
          <div class="text-center mb-6">
            <div class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-4">
              <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">Delete Task</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">This action cannot be undone. Are you sure you want to permanently delete this task?</p>
          </div>

          <!-- Modal Actions -->
          <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button
                @click="closeModal"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105 order-2 sm:order-1"
            >
              Cancel
            </button>
            <button
                @click="deleteTask()"
                class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl font-medium transition-all duration-200 hover:scale-105 shadow-lg shadow-red-500/25 order-1 sm:order-2"
            >
              Delete Task
            </button>
          </div>
        </div>
      </Modal>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Enhanced animations and effects */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

@keyframes shimmer {
  0% {
    background-position: -200px 0;
  }
  100% {
    background-position: calc(200px + 100%) 0;
  }
}

.animate-slide-in-up {
  animation: slideInUp 0.5s ease-out;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Line clamp utilities */
.line-clamp-1 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
}

.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

/* Custom focus styles */
input:focus, select:focus, textarea:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Enhanced scrollbar styling */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: rgba(156, 163, 175, 0.5);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(156, 163, 175, 0.7);
}

/* Dark mode scrollbar */
.dark ::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
}

.dark ::-webkit-scrollbar-thumb {
  background: rgba(75, 85, 99, 0.5);
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: rgba(75, 85, 99, 0.7);
}

/* Mobile-specific optimizations */
@media (max-width: 640px) {
  .backdrop-blur-xl {
    backdrop-filter: blur(12px);
  }

  /* Improve touch targets */
  button, a {
    min-height: 44px;
    min-width: 44px;
  }

  /* Better spacing on mobile */
  .space-y-4 > * + * {
    margin-top: 1rem;
  }
}

/* Improved glass morphism effect */
.bg-white\/70 {
  background-color: rgba(255, 255, 255, 0.7);
}

.dark .dark\:bg-gray-800\/70 {
  background-color: rgba(31, 41, 55, 0.7);
}

/* Enhanced border styling */
.border-white\/20 {
  border-color: rgba(255, 255, 255, 0.2);
}

.dark .dark\:border-gray-700\/30 {
  border-color: rgba(55, 65, 81, 0.3);
}

/* Loading state animation */
@keyframes loading {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
  100% {
    opacity: 1;
  }
}

.loading {
  animation: loading 1.5s ease-in-out infinite;
}
</style>