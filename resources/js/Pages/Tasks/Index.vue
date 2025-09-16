<script setup>
import {defineProps, ref} from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";

const props = defineProps({
  tasks: Object
});

const form = useForm({});

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
</script>

<template>
  <Head title="Tasks"/>

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
            Task Management
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">
            Organize and track your tasks efficiently
          </p>
        </div>
        <Link
            :href="route('tasks.create')"
            class="group relative inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40"
        >
          <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Create Task</span>
        </Link>
      </div>
    </template>

    <div class="py-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tasks</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ tasks.total || 0 }}</p>
            </div>
            <div class="p-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-green-500/5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
              <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                {{ tasks.data ? tasks.data.filter(task => task.is_completed).length : 0 }}
              </p>
            </div>
            <div class="p-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-orange-500/5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
              <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                {{ tasks.data ? tasks.data.filter(task => !task.is_completed).length : 0 }}
              </p>
            </div>
            <div class="p-3 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Tasks Grid/List -->
      <div v-if="tasks.data && tasks.data.length > 0" class="space-y-4">
        <!-- Tasks Header -->
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Your Tasks</h2>
          <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <span>{{ tasks.from }}-{{ tasks.to }} of {{ tasks.total }} tasks</span>
          </div>
        </div>

        <!-- Task Cards -->
        <div class="grid gap-4 md:gap-6">
          <div
              v-for="task in tasks.data"
              :key="task.id"
              class="group bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl p-6 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 hover:transform hover:-translate-y-1"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-start space-x-4 flex-1">
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
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ task.title }}
                  </h3>

                  <!-- Status Badge -->
                  <div class="flex items-center space-x-3">
                                        <span
                                            v-if="task.is_completed"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800"
                                        >
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Completed
                                        </span>
                    <span
                        v-else
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400 border border-orange-200 dark:border-orange-800"
                    >
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            In Progress
                                        </span>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-center space-x-2 ml-4">
                <Link
                    :href="route('tasks.show', task)"
                    class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-all duration-200 group/btn"
                    title="View Task"
                >
                  <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </Link>

                <Link
                    :href="route('tasks.edit', task)"
                    class="p-2 text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all duration-200 group/btn"
                    title="Edit Task"
                >
                  <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </Link>

                <button
                    @click="confirmTaskDeletion(task.id)"
                    class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200 group/btn"
                    title="Delete Task"
                >
                  <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16">
        <div class="mx-auto w-24 h-24 bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full flex items-center justify-center mb-6">
          <svg class="w-12 h-12 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No tasks yet</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first task to stay organized.</p>
        <Link
            :href="route('tasks.create')"
            class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Create Your First Task</span>
        </Link>
      </div>

      <!-- Pagination -->
      <div v-if="tasks.data && tasks.data.length > 0" class="mt-8 flex justify-center">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl p-4 border border-white/20 dark:border-gray-700/30 shadow-lg shadow-blue-500/5">
          <pagination :links="tasks.links" />
        </div>
      </div>

      <!-- Enhanced Delete Confirmation Modal -->
      <Modal :show="confirmingTaskDeletion" @close="closeModal" max-width="md">
        <div class="relative p-8">
          <!-- Modal Header -->
          <div class="text-center mb-6">
            <div class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-4">
              <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Delete Task</h2>
            <p class="text-gray-600 dark:text-gray-400">This action cannot be undone. Are you sure you want to permanently delete this task?</p>
          </div>

          <!-- Modal Actions -->
          <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button
                @click="closeModal"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105"
            >
              Cancel
            </button>
            <button
                @click="deleteTask()"
                class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl font-medium transition-all duration-200 hover:scale-105 shadow-lg shadow-red-500/25"
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
/* Custom animations and effects */
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

.animate-slide-in-up {
  animation: slideInUp 0.5s ease-out;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

/* Custom scrollbar for better aesthetics */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: rgba(156, 163, 175, 0.5);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(156, 163, 175, 0.7);
}

/* Dark mode scrollbar */
.dark ::-webkit-scrollbar-thumb {
  background: rgba(75, 85, 99, 0.5);
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: rgba(75, 85, 99, 0.7);
}
</style>