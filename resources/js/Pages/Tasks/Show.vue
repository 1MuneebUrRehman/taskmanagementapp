<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    task: Object,
});

const form = useForm({});

function deleteTask() {
    if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
        form.delete(route('tasks.destroy', { task: props.task.id }), {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <Head :title="`Task: ${props.task.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Task Details
                </h2>
                <div class="flex flex-wrap gap-2">
                    <Link :href="route('tasks.index')" as="button"
                          class="rounded-md bg-gray-200 px-4 py-2 text-center text-sm text-gray-800 hover:bg-gray-300 hover:text-gray-900 inline-block dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-gray-100">
                        Back
                    </Link>
                    <Link :href="route('tasks.edit', props.task)" as="button"
                          class="rounded-md bg-yellow-400 px-4 py-2 text-center text-sm text-white hover:bg-yellow-500">
                        Edit
                    </Link>
                    <button @click="deleteTask"
                            class="rounded-md bg-red-500 px-4 py-2 text-center text-sm text-white hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-1">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</h3>
                            <p class="text-base font-semibold text-gray-900 dark:text-gray-100 break-words">{{ props.task.title }}</p>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</h3>
                            <div>
                                <span v-if="props.task.is_completed"
                                      class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Completed
                                </span>
                                <span v-else
                                      class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Not Completed
                                </span>
                            </div>
                        </div>
                        <div class="space-y-1 sm:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h3>
                            <p class="text-base text-gray-900 dark:text-gray-100 whitespace-pre-wrap break-words">{{ props.task.description || '—' }}</p>
                        </div>
                      <div class="space-y-1">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</h3>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ props.task.created_at }}</p>
                      </div>

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
