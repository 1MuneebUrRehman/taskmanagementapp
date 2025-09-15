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
            <div class="flex justify-between items-center">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Tasks
                </h2>
                <Link :href="route('tasks.create')" as="button"
                      class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Create
                </Link>
            </div>
        </template>

        <div class="py-12 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <!-- Table Container -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                <table class="min-w-full w-full whitespace-normal break-words">
                    <thead>
                    <tr class=" border-b bg-blue-50 text-left text-xs font-semibold uppercase tracking-wide text-blue-600 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-700">
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Completed</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="task in tasks.data" :key="task.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                        <td class="px-6 py-4 border-b dark:border-gray-700">
                            <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ task.title }}</p>
                        </td>
                        <td class="px-6 py-4 border-b dark:border-gray-700">
                        <span v-if="task.is_completed"
                              class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Completed
                        </span>
                            <span v-else
                                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            Not Completed
                        </span>
                        </td>
                        <td class="px-6 py-4 border-b dark:border-gray-700">
                            <div class="flex flex-wrap gap-2 sm:flex-nowrap">
                                <Link :href="route('tasks.show', task)"
                                      class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-md font-medium hover:bg-blue-600 transition duration-150">
                                    View
                                </Link>
                                <Link :href="route('tasks.edit', task)"
                                      class="inline-flex items-center px-3 py-1.5 bg-yellow-400 text-white rounded-md font-medium hover:bg-yellow-500 transition duration-150">
                                    Edit
                                </Link>
                                <button class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white rounded-md font-medium hover:bg-red-600 transition duration-150"
                                        @click="confirmTaskDeletion(task.id)">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col items-center gap-3 px-5 py-5 sm:flex-row sm:justify-between">
                <pagination :links="tasks.links"/>
            </div>

            <!-- Delete Confirmation Modal -->
            <Modal :show="confirmingTaskDeletion" @close="closeModal">
                <div class="p-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirm Deletion</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">This action cannot be undone. Are you sure you want to delete
                        this task?</p>
                    <div class="flex justify-center space-x-4">
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                                @click="closeModal">
                            Cancel
                        </button>
                        <button class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-150"
                                @click="deleteTask()">
                            Delete Task
                        </button>
                    </div>
                </div>
            </Modal>
        </div>

    </AuthenticatedLayout>
</template>
