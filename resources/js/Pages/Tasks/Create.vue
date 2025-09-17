<script setup>
import {defineProps} from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Switch from "@/Components/Switch.vue";
import TextArea from "@/Components/TextArea.vue";

const props = defineProps({
  task: Object,
});

const form = useForm({
  title: '',
  description: '',
  is_completed: false,
  // Scheduling
  start_date: '',
  due_date: '',
  completed_at: '',
  reminder_at: '',
  // Categorization
  priority: 'normal',
  status: 'pending',
  project_id: '',
  // Collaboration
  assigned_to: '',
  // System/meta
  archived: false,
  position: ''
});

function submitForm() {
  if (!form.title || !form.description) {
    form.errors.title = form.title ? '' : 'Title is required';
    form.errors.description = form.description ? '' : 'Description is required';
    return;
  }

  form.post(route('tasks.store'));
  if (form.wasSuccessful) {
    form.reset(); // Reset form errors
  }
}
</script>

<template>
  <Head title="Create Task"/>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
            Create New Task
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2">
            Add a new task to your workflow
          </p>
        </div>
        <Link
            :href="route('tasks.index')"
            class="group inline-flex items-center space-x-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105"
        >
          <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Back to Tasks</span>
        </Link>
      </div>
    </template>

    <div class="py-8 mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <!-- Progress Indicator -->
      <div class="mb-8">
        <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
          <span>Create Task</span>
          <span>Step 1 of 1</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
          <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-2 rounded-full w-full transition-all duration-300"></div>
        </div>
      </div>

      <!-- Main Form Card -->
      <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg shadow-blue-500/5 border border-white/20 dark:border-gray-700/30 overflow-hidden">
        <!-- Card Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-white/20 dark:border-gray-700/30">
          <div class="flex items-center space-x-3">
            <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Task Information</h2>
              <p class="text-sm text-gray-600 dark:text-gray-400">Fill in the details for your new task</p>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <form @submit.prevent="submitForm" class="p-8 space-y-8">
          <!-- Title Field -->
          <div class="space-y-2">
            <InputLabel for="title" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              <span>Task Title</span>
              <span class="text-red-500 text-sm">*</span>
            </InputLabel>
            <div class="relative">
              <TextInput
                  id="title"
                  v-model="form.title"
                  :class="{'border-red-300 dark:border-red-600': form.errors.title, 'border-gray-200 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-400': !form.errors.title}"
                  class="w-full pl-12 pr-4 py-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border-2 transition-all duration-200 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-400/20"
                  placeholder="Enter a descriptive title for your task..."
                  autocomplete="title"
                  autofocus
                  required
                  type="text"
              />
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
            </div>
            <InputError :message="form.errors.title" class="text-sm text-red-600 dark:text-red-400 ml-1" />
          </div>

          <!-- Description Field -->
          <div class="space-y-2">
            <InputLabel for="description" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <span>Description</span>
              <span class="text-red-500 text-sm">*</span>
            </InputLabel>
            <div class="relative">
                            <TextArea
                                id="description"
                                v-model="form.description"
                                :class="{'border-red-300 dark:border-red-600': form.errors.description, 'border-gray-200 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-400': !form.errors.description}"
                                class="w-full pl-12 pr-4 py-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border-2 transition-all duration-200 focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 min-h-[120px] resize-none"
                                placeholder="Describe your task in detail..."
                                autocomplete="description"
                                required
                            />
              <div class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
              </div>
            </div>
            <InputError :message="form.errors.description" class="text-sm text-red-600 dark:text-red-400 ml-1" />
          </div>

          <!-- Completion Status -->
          <div class="space-y-4">
            <InputLabel class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Task Status</span>
            </InputLabel>
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Mark as Completed</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Set this task as already completed</p>
                  </div>
                </div>
                <Switch
                    id="completed"
                    v-model="form.is_completed"
                    class="ml-4"
                />
              </div>
            </div>
            <InputError :message="form.errors.is_completed" class="text-sm text-red-600 dark:text-red-400 ml-1" />
          </div>

          <!-- Scheduling -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <InputLabel for="start_date">Start Date</InputLabel>
              <input id="start_date" v-model="form.start_date" type="datetime-local" class="mt-1 w-full rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 px-3 py-2" />
              <InputError :message="form.errors.start_date" />
            </div>
            <div>
              <InputLabel for="due_date">Due Date</InputLabel>
              <input id="due_date" v-model="form.due_date" type="datetime-local" class="mt-1 w-full rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 px-3 py-2" />
              <InputError :message="form.errors.due_date" />
            </div>
            <div>
              <InputLabel for="reminder_at">Reminder At</InputLabel>
              <input id="reminder_at" v-model="form.reminder_at" type="datetime-local" class="mt-1 w-full rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 px-3 py-2" />
              <InputError :message="form.errors.reminder_at" />
            </div>
            <div>
              <InputLabel for="completed_at">Completed At</InputLabel>
              <input id="completed_at" v-model="form.completed_at" type="datetime-local" class="mt-1 w-full rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 px-3 py-2" />
              <InputError :message="form.errors.completed_at" />
            </div>
          </div>

          <!-- Categorization -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <InputLabel for="priority">Priority</InputLabel>
              <select id="priority" v-model="form.priority" class="mt-1 w-full rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 px-3 py-2">
                <option value="low">Low</option>
                <option value="normal">Normal</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
              </select>
              <InputError :message="form.errors.priority" />
            </div>
            <div>
              <InputLabel for="status">Status</InputLabel>
              <select id="status" v-model="form.status" class="mt-1 w-full rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 px-3 py-2">
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="blocked">Blocked</option>
              </select>
              <InputError :message="form.errors.status" />
            </div>
            <div>
              <InputLabel for="project_id">Project ID</InputLabel>
              <TextInput id="project_id" v-model="form.project_id" type="number" class="mt-1 w-full" />
              <InputError :message="form.errors.project_id" />
            </div>
          </div>

          <!-- Collaboration and Meta -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <InputLabel for="assigned_to">Assigned To (User ID)</InputLabel>
              <TextInput id="assigned_to" v-model="form.assigned_to" type="number" class="mt-1 w-full" />
              <InputError :message="form.errors.assigned_to" />
            </div>
            <div>
              <InputLabel for="position">Position</InputLabel>
              <TextInput id="position" v-model="form.position" type="number" min="0" class="mt-1 w-full" />
              <InputError :message="form.errors.position" />
            </div>
            <div class="flex items-center gap-3 pt-6">
              <Switch id="archived" v-model="form.archived" />
              <label class="text-sm text-gray-700 dark:text-gray-300" for="archived">Archived</label>
              <InputError :message="form.errors.archived" />
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-200 dark:border-gray-700">
            <PrimaryButton
                :disabled="form.processing"
                class="group relative inline-flex items-center justify-center space-x-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl transform hover:scale-105 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
              <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span>{{ form.processing ? 'Creating...' : 'Create Task' }}</span>
            </PrimaryButton>

            <Link
                :href="route('tasks.index')"
                class="inline-flex items-center justify-center space-x-2 px-8 py-4 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200 hover:scale-105"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <span>Cancel</span>
            </Link>

            <!-- Success Message -->
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
                <span class="text-sm font-medium">Task created successfully!</span>
              </div>
            </Transition>
          </div>
        </form>
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
            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-1">💡 Pro Tips</h3>
            <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-1">
              <li>• Use clear, actionable titles that describe what needs to be done</li>
              <li>• Include relevant details in the description to provide context</li>
              <li>• You can always edit the task later if you need to make changes</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
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