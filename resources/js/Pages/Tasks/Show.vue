<script setup>
import { defineProps, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
  task: Object,
});

const form = useForm({});

const confirmingTaskDeletion = ref(false);
const openDeleteModal = () => { confirmingTaskDeletion.value = true; };
const closeModal = () => { confirmingTaskDeletion.value = false; };

function deleteTask() {
  form.delete(route('tasks.destroy', { task: props.task.id }), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
  });
}
</script>

<template>
  <Head :title="`Task: ${props.task.title}`" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
            Task Details
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">
            Review and manage the selected task
          </p>
        </div>
        <div class="flex flex-wrap gap-3">
          <Link
              :href="route('tasks.index')"
              class="group inline-flex items-center space-x-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105"
          >
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Tasks</span>
          </Link>
          <Link
              :href="route('tasks.edit', props.task)"
              class="group inline-flex items-center space-x-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium transition-all duration-200 hover:scale-105 shadow-lg shadow-amber-500/25"
          >
            <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>Edit Task</span>
          </Link>
          <button
              @click="openDeleteModal"
              class="group inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl font-medium transition-all duration-200 hover:scale-105 shadow-lg shadow-red-500/25"
          >
            <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>Delete Task</span>
          </button>
        </div>
      </div>
    </template>

    <div class="py-8 mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <!-- Main Task Card -->
      <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg shadow-blue-500/5 border border-white/20 dark:border-gray-700/30 overflow-hidden">
        <!-- Card Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-white/20 dark:border-gray-700/30">
          <div class="flex items-center space-x-3">
            <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Task Information</h2>
              <p class="text-sm text-gray-600 dark:text-gray-400">Details and metadata for this task</p>
            </div>
          </div>
        </div>

        <!-- Task Details -->
        <div class="p-8 space-y-8">
          <!-- Title Field -->
          <div class="space-y-2">
            <div class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              <span>Task Title</span>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
              <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ props.task.title }}</p>
            </div>
          </div>

          <!-- Status Field -->
          <div class="space-y-2">
            <div class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Task Status</span>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                            <span v-if="props.task.is_completed" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400 border border-green-200 dark:border-green-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Completed
                            </span>
              <span v-else class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400 border border-orange-200 dark:border-orange-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                In Progress
                            </span>
            </div>
          </div>

          <!-- Description Field -->
          <div class="space-y-2">
            <div class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <span>Description</span>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
              <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ props.task.description || 'No description provided' }}</p>
            </div>
          </div>

          <!-- Metadata -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-8 border-t border-gray-200 dark:border-gray-700">
            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Created At</span>
              </div>
              <div class="text-sm text-gray-600 dark:text-gray-400">{{ props.task.created_at }}</div>
            </div>

            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Last Updated</span>
              </div>
              <div class="text-sm text-gray-600 dark:text-gray-400">{{ props.task.updated_at }}</div>
            </div>

            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white">Start Date</div>
              <div class="text-sm text-gray-600 dark:text-gray-400">{{ props.task.start_date || '-' }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white">Due Date</div>
              <div class="text-sm text-gray-600 dark:text-gray-400">{{ props.task.due_date || '-' }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white">Reminder At</div>
              <div class="text-sm text-gray-600 dark:text-gray-400">{{ props.task.reminder_at || '-' }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white">Completed At</div>
              <div class="text-sm text-gray-600 dark:text-gray-400">{{ props.task.completed_at || '-' }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white">Priority</div>
              <div class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ props.task.priority }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white">Status</div>
              <div class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ props.task.status }}</div>
            </div>
            <div class="space-y-2">
            </div>
            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white">Assigned To</div>
              <div class="text-sm text-gray-600 dark:text-gray-400">{{ props.task.assigned_to || '-' }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-base font-medium text-gray-900 dark:text-white">Archived</div>
              <div class="text-sm text-gray-600 dark:text-gray-400">{{ props.task.archived ? 'Yes' : 'No' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
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