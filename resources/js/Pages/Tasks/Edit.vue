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
    task: Object
});


const form = useForm({
    title: props.task.title,
    description: props.task.description,
    is_completed: props.task.is_completed,
});

function submitForm() {
    // Check if any field is empty
    if (!form.title || !form.description) {
        form.errors.title = form.title ? '' : 'Title is required'; // Simplify error handling
        form.errors.description = form.description ? '' : 'Description is required';
        return;
    }
    // Submit form
    form.patch(route('tasks.update', props.task));
    if (form.wasSuccessful) {
        form.reset();
    }
}

</script>

<template>
    <Head title="Edit Task"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Edit Task
                </h2>
            </div>
        </template>

        <div class="py-12 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <div class="overflow-hidden shadow-sm sm:rounded-lg space-y-6">
                <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header class="flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900">Edit Task</h2>
                        <Link :href="route('tasks.index')" as="button"
                              class="rounded-md bg-gray-200 px-4 py-2 text-center text-sm text-gray-800 hover:bg-gray-300 hover:text-gray-900 inline-block">
                            Back
                        </Link>
                    </header>

                    <form class="mt-6 space-y-6" @submit.prevent="submitForm">
                        <div>
                            <InputLabel for="title" required="true" value="Title"/>

                            <TextInput
                                id="title"
                                v-model="form.title"
                                :class="{'border-red-500': form.errors.title}"
                                autocomplete="title"
                                autofocus
                                class="mt-1 block w-full "
                                required
                                type="text"
                            />
                            <InputError :message="form.errors.title" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="description" required="true" value="Description"/>

                            <TextArea
                                id="description"
                                v-model="form.description"
                                autocomplete="description"
                                class="mt-1 block w-full"
                                required
                                type="text"
                            />

                            <InputError :message="form.errors.description" class="mt-2"/>
                        </div>


                        <div>
                            <InputLabel for="completed" required="true" value="Completed"/>

                            <Switch
                                id="completed"
                                v-model="form.is_completed"
                                class="mt-2"
                            />

                            <InputError :message="form.errors.is_completed" class="mt-2"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Update</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-green-600">Updated.</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

