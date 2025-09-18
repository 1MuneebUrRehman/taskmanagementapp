<script setup>
import {defineProps, ref, computed} from 'vue';
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
  users: { type: Array, default: () => [] },
});

const form = useForm({
  title: '',
  description: '',
  is_completed: false,
  // Time & scheduling
  start_date: '',
  due_date: '',
  completed_at: '',
  reminder_at: '',
  // Categorization
  priority: 'normal',
  status: 'pending',
  // Collaboration
  assigned_to: '',
  // System/meta
  archived: false,
});

// Reactive states
const currentStep = ref(1);
const totalSteps = 3;
const isAdvancedMode = ref(false);

const stepProgress = computed(() => (currentStep.value / totalSteps) * 100);

const priorityColors = {
  low: 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800',
  normal: 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800',
  high: 'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:border-orange-800',
  urgent: 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800'
};

function nextStep() {
  if (currentStep.value < totalSteps) {
    currentStep.value++;
  }
}

function prevStep() {
  if (currentStep.value > 1) {
    currentStep.value--;
  }
}

function validateCurrentStep() {
  const errors = {};

  if (currentStep.value === 1) {
    if (!form.title) errors.title = 'Title is required';
  }

  form.errors = errors;
  return Object.keys(errors).length === 0;
}

function submitForm() {
  if (!validateCurrentStep()) return;

  form.post(route('tasks.store'));
  if (form.wasSuccessful) {
    form.reset();
    currentStep.value = 1;
  }
}
</script>

<template>
  <Head title="Create Task"/>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0 px-4 lg:px-0">
        <div class="flex-1">
          <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
            Create New Task
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm lg:text-base">
            Add a new task to your workflow
          </p>
        </div>
        <div class="flex items-center space-x-3">
          <button
              @click="isAdvancedMode = !isAdvancedMode"
              class="inline-flex items-center space-x-2 px-4 py-2 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/20 dark:hover:bg-purple-800/30 text-purple-700 dark:text-purple-300 rounded-lg font-medium transition-all duration-200 text-sm"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
            </svg>
            <span>{{ isAdvancedMode ? 'Simple' : 'Advanced' }}</span>
          </button>
          <Link
              :href="route('tasks.index')"
              class="group inline-flex items-center space-x-2 px-4 lg:px-6 py-2 lg:py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105 text-sm lg:text-base"
          >
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="hidden sm:inline">Back to Tasks</span>
            <span class="sm:hidden">Back</span>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-4 lg:py-8 mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <!-- Multi-step Progress Indicator -->
      <div class="mb-6 lg:mb-8" v-if="isAdvancedMode">
        <div class="flex items-center justify-between text-xs lg:text-sm text-gray-600 dark:text-gray-400 mb-3">
          <span>Step {{ currentStep }} of {{ totalSteps }}</span>
          <span>{{ Math.round(stepProgress) }}% Complete</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
          <div
              class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-2 rounded-full transition-all duration-500 ease-out"
              :style="{ width: `${stepProgress}%` }"
          ></div>
        </div>

        <!-- Step indicators -->
        <div class="flex justify-between mt-4">
          <div v-for="step in totalSteps" :key="step" class="flex flex-col items-center">
            <div
                :class="[
                'w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold transition-all duration-300',
                step <= currentStep
                  ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white'
                  : 'bg-gray-200 dark:bg-gray-700 text-gray-400'
              ]"
            >
              {{ step }}
            </div>
            <span class="text-xs mt-1 text-gray-500 dark:text-gray-400 text-center hidden sm:block">
              {{ step === 1 ? 'Basic Info' : step === 2 ? 'Details' : 'Schedule' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Main Form Card -->
      <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-indigo-500/10 border border-white/20 dark:border-gray-700/30 overflow-hidden">
        <!-- Animated Header -->
        <div class="relative px-6 lg:px-8 py-6 lg:py-8 bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-indigo-900/20 dark:via-gray-800/50 dark:to-purple-900/20 border-b border-white/20 dark:border-gray-700/30">
          <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/5 to-purple-600/5 dark:from-indigo-400/5 dark:to-purple-400/5"></div>
          <div class="relative flex items-center space-x-4">
            <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <div>
              <h2 class="text-lg lg:text-xl font-semibold text-gray-900 dark:text-white">
                {{ isAdvancedMode ?
                  (currentStep === 1 ? 'Basic Information' :
                      currentStep === 2 ? 'Task Details' : 'Scheduling & Assignment') : 'Task Information' }}
              </h2>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ isAdvancedMode ?
                  (currentStep === 1 ? 'Enter the basic details for your task' :
                      currentStep === 2 ? 'Add categorization and status' :
                          'Set dates and assignments') : 'Fill in the details for your new task' }}
              </p>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <form @submit.prevent="submitForm" class="p-6 lg:p-8">

          <!-- Step 1: Basic Information -->
          <div v-show="!isAdvancedMode || currentStep === 1" class="space-y-6 lg:space-y-8">

            <!-- Title Field -->
            <div class="space-y-2">
              <InputLabel for="title" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span>Task Title</span>
                <span class="text-red-500 text-sm">*</span>
              </InputLabel>
              <div class="relative group">
                <TextInput
                    id="title"
                    v-model="form.title"
                    :class="{'border-red-300 dark:border-red-600': form.errors.title, 'border-gray-200 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 group-hover:border-gray-300 dark:group-hover:border-gray-500': !form.errors.title}"
                    class="w-full pl-12 pr-4 py-4 bg-white/70 dark:bg-gray-700/70 backdrop-blur-sm rounded-xl border-2 transition-all duration-200 focus:ring-4 focus:ring-indigo-500/20 dark:focus:ring-indigo-400/20"
                    placeholder="Enter a descriptive title for your task..."
                    autocomplete="title"
                    autofocus
                    required
                    type="text"
                />
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
              </div>
              <InputError :message="form.errors.title" class="text-sm text-red-600 dark:text-red-400 ml-1" />
            </div>

            <!-- Description Field -->
            <div class="space-y-2">
              <InputLabel for="description" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Description</span>
              </InputLabel>
              <div class="relative group">
                <TextArea
                    id="description"
                    v-model="form.description"
                    :class="{'border-red-300 dark:border-red-600': form.errors.description, 'border-gray-200 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 group-hover:border-gray-300 dark:group-hover:border-gray-500': !form.errors.description}"
                    class="w-full pl-12 pr-4 py-4 bg-white/70 dark:bg-gray-700/70 backdrop-blur-sm rounded-xl border-2 transition-all duration-200 focus:ring-4 focus:ring-indigo-500/20 dark:focus:ring-indigo-400/20 min-h-[120px] resize-none"
                    placeholder="Describe your task in detail..."
                    autocomplete="description"
                />
                <div class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400 mt-0.5 group-hover:text-gray-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                  </svg>
                </div>
              </div>
              <InputError :message="form.errors.description" class="text-sm text-red-600 dark:text-red-400 ml-1" />
            </div>
          </div>

          <!-- Step 2: Task Details -->
          <div v-show="isAdvancedMode && currentStep === 2" class="space-y-6 lg:space-y-8">

            <!-- Priority and Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <InputLabel for="priority" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2 mb-2">
                  <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                  </svg>
                  <span>Priority Level</span>
                </InputLabel>
                <div class="space-y-3">
                  <div v-for="priority in ['low', 'normal', 'high', 'urgent']" :key="priority" class="relative">
                    <input
                        :id="`priority-${priority}`"
                        v-model="form.priority"
                        :value="priority"
                        type="radio"
                        class="peer sr-only"
                    />
                    <label
                        :for="`priority-${priority}`"
                        :class="[
                        'flex items-center justify-between w-full p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:scale-105',
                        form.priority === priority
                          ? priorityColors[priority] + ' border-current shadow-lg transform scale-105'
                          : 'bg-white/50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'
                      ]"
                    >
                      <div class="flex items-center space-x-3">
                        <div :class="[
                          'w-4 h-4 rounded-full border-2 transition-all duration-200',
                          form.priority === priority ? 'border-current bg-current' : 'border-gray-300 dark:border-gray-600'
                        ]">
                          <div v-if="form.priority === priority" class="w-2 h-2 bg-white rounded-full mx-auto mt-0.5"></div>
                        </div>
                        <span class="font-medium capitalize">{{ priority }}</span>
                      </div>
                      <div class="text-right">
                        <span class="text-xs opacity-75">
                          {{ priority === 'urgent' ? '🔥' : priority === 'high' ? '⚡' : priority === 'normal' ? '📝' : '🟢' }}
                        </span>
                      </div>
                    </label>
                  </div>
                </div>
              </div>

              <div>
                <InputLabel for="status" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2 mb-2">
                  <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>Status</span>
                </InputLabel>
                <div class="grid grid-cols-2 gap-3">
                  <div v-for="status in ['pending', 'in_progress', 'completed', 'blocked']" :key="status" class="relative">
                    <input
                        :id="`status-${status}`"
                        v-model="form.status"
                        :value="status"
                        type="radio"
                        class="peer sr-only"
                    />
                    <label
                        :for="`status-${status}`"
                        :class="[
                        'flex items-center justify-center w-full p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:scale-105',
                        form.status === status
                          ? 'bg-indigo-100 text-indigo-800 border-indigo-500 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-500 shadow-lg transform scale-105'
                          : 'bg-white/50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'
                    ]"
                    >
                      <span class="font-medium capitalize">{{ status.replace('_', ' ') }}</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Assignment -->
            <div>
              <InputLabel for="assigned_to" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2 mb-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Assign To</span>
              </InputLabel>
              <select
                  id="assigned_to"
                  v-model="form.assigned_to"
                  class="w-full px-4 py-4 bg-white/70 dark:bg-gray-700/70 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 focus:ring-4 focus:ring-indigo-500/20"
              >
                <option value="">Assign to someone</option>
                <option v-for="user in props.users" :key="user.id" :value="user.id">{{ user.name }}</option>
              </select>
            </div>
          </div>

          <!-- Step 3: Scheduling & Assignment -->
          <div v-show="isAdvancedMode && currentStep === 3" class="space-y-6 lg:space-y-8">

            <!-- Date Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <InputLabel for="start_date" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2 mb-2">
                  <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span>Start Date</span>
                </InputLabel>
                <input
                    id="start_date"
                    v-model="form.start_date"
                    type="datetime-local"
                    class="w-full px-4 py-4 bg-white/70 dark:bg-gray-700/70 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 focus:ring-4 focus:ring-indigo-500/20"
                />
              </div>

              <div>
                <InputLabel for="due_date" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2 mb-2">
                  <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>Due Date</span>
                </InputLabel>
                <input
                    id="due_date"
                    v-model="form.due_date"
                    type="datetime-local"
                    class="w-full px-4 py-4 bg-white/70 dark:bg-gray-700/70 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 focus:ring-4 focus:ring-indigo-500/20"
                />
              </div>

              <div>
                <InputLabel for="reminder_at" class="text-base font-medium text-gray-900 dark:text-white flex items-center space-x-2 mb-2">
                  <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.829 2.829A4 4 0 019.071 9H15m0 0a4 4 0 01-4 4H5.929l-2.83 2.828M15 9a4 4 0 014-4H4.929L7.757 2.172" />
                  </svg>
                  <span>Reminder</span>
                </InputLabel>
                <input
                    id="reminder_at"
                    v-model="form.reminder_at"
                    type="datetime-local"
                    class="w-full px-4 py-4 bg-white/70 dark:bg-gray-700/70 backdrop-blur-sm rounded-xl border-2 border-gray-200 dark:border-gray-600 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 focus:ring-4 focus:ring-indigo-500/20"
                />
              </div>
            </div>

            <!-- Archive Option -->
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l4 4 4-4m0 6l-4-4-4 4" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-medium text-gray-900 dark:text-white">Archive Task</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Create this task in archived state</p>
                  </div>
                </div>
                <Switch
                    id="archived"
                    v-model="form.archived"
                    class="ml-4"
                />
              </div>
            </div>
          </div>

          <!-- Navigation & Actions -->
          <div class="flex flex-col sm:flex-row justify-between items-center pt-8 border-t border-gray-200 dark:border-gray-700 space-y-4 sm:space-y-0">
            <!-- Previous Button -->
            <button
                v-if="isAdvancedMode && currentStep > 1"
                @click="prevStep"
                type="button"
                class="group inline-flex items-center space-x-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200 hover:scale-105"
            >
              <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              <span>Previous</span>
            </button>
            <div v-else></div>

            <!-- Next/Submit Button -->
            <div class="flex items-center space-x-4">
              <button
                  v-if="isAdvancedMode && currentStep < totalSteps"
                  @click="nextStep"
                  type="button"
                  class="group inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl font-medium transition-all duration-200 hover:scale-105 shadow-lg shadow-indigo-500/25"
              >
                <span>Next Step</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </button>

              <PrimaryButton
                  v-if="!isAdvancedMode || currentStep === totalSteps"
                  :disabled="form.processing"
                  class="group relative inline-flex items-center justify-center space-x-2 px-6 lg:px-8 py-3 lg:py-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-indigo-600 hover:via-purple-600 hover:to-pink-600 text-white font-medium rounded-xl transform hover:scale-105 transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/40 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
              >
                <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>{{ form.processing ? 'Creating Task...' : 'Create Task' }}</span>
              </PrimaryButton>
            </div>
          </div>

          <!-- Success Message -->
          <Transition
              enter-active-class="transition ease-out duration-300"
              enter-from-class="opacity-0 transform scale-90"
              enter-to-class="opacity-100 transform scale-100"
              leave-active-class="transition ease-in duration-200"
              leave-from-class="opacity-100 transform scale-100"
              leave-to-class="opacity-0 transform scale-90"
          >
            <div v-if="form.recentlySuccessful" class="mt-6 flex items-center space-x-2 px-4 py-3 bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 rounded-xl border border-green-200 dark:border-green-800">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="font-medium">Task created successfully!</span>
            </div>
          </Transition>
        </form>
      </div>

      <!-- Tips & Help Card -->
      <div class="mt-6 lg:mt-8 bg-indigo-50 dark:bg-indigo-900/20 backdrop-blur-sm rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-indigo-200 dark:border-indigo-800">
        <div class="flex items-start space-x-4">
          <div class="p-3 bg-indigo-100 dark:bg-indigo-800/30 rounded-xl">
            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 01118 0z" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-200 mb-3">💡 Pro Tips for Creating Effective Tasks</h3>
            <ul class="text-sm text-indigo-800 dark:text-indigo-300 space-y-2">
              <li class="flex items-start space-x-2">
                <svg class="w-4 h-4 mt-0.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Use clear, action-oriented titles that describe what needs to be done</span>
              </li>
              <li class="flex items-start space-x-2">
                <svg class="w-4 h-4 mt-0.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Set realistic due dates and time estimates to improve planning accuracy</span>
              </li>
              <li class="flex items-start space-x-2">
                <svg class="w-4 h-4 mt-0.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Use appropriate priority levels to help organize your workflow</span>
              </li>
              <li class="flex items-start space-x-2">
                <svg class="w-4 h-4 mt-0.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Assign tasks to team members when collaboration is needed</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
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

.animate-slide-in-up {
  animation: slideInUp 0.5s ease-out;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Enhanced focus styles */
.focus\:ring-4:focus {
  --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
  --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(4px + var(--tw-ring-offset-width)) var(--tw-ring-color);
  box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
}

/* Glass morphism improvements */
.backdrop-blur-xl {
  backdrop-filter: blur(16px);
}

/* Mobile responsive improvements */
@media (max-width: 640px) {
  .backdrop-blur-xl {
    backdrop-filter: blur(12px);
  }

  /* Better touch targets */
  button, input[type="radio"] + label, input[type="checkbox"] + label {
    min-height: 44px;
    min-width: 44px;
  }
}

/* Custom scrollbar styling */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: rgba(99, 102, 241, 0.3);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(99, 102, 241, 0.5);
}

.dark ::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
}

.dark ::-webkit-scrollbar-thumb {
  background: rgba(99, 102, 241, 0.3);
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: rgba(99, 102, 241, 0.5);
}
</style>