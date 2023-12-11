<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    venue: '',
    reporter: '',
    category: '',
    status: '',
    tags: [],
    attachments: [],
});

const submit = () => {
    form.post(route('reports.store'),  {
        onFinish: () => form.reset('title', 'description'),
    });
};
</script>

<template>
        <Head title="New Report" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Title" />

                <TextInput
                    id="title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.title"
                    required
                    autofocus
                    autocomplete="title"
                />

                <InputError class="mt-2" :message="form.errors.title" />
            </div>

            <div class="mt-4">
                <InputLabel for="description" value="Description" />

                <TextInput
                    id="description"
                    type="textarea"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    required
                    autocomplete="description"
                />

                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="mt-4">
                <InputLabel for="venue" value="Venue" />

                <TextInput
                    id="venue"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.venue"
                    required
                    autocomplete="venue"
                />

                <InputError class="mt-2" :message="form.errors.venue" />
            </div>

            <div class="mt-4">
                <InputLabel for="reporter" value="Reporter" />

                <TextInput
                    id="reporter"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.reporter"
                    required
                    autocomplete="reporter"
                />

                <InputError class="mt-2" :message="form.errors.reporter" />
            </div>

            <div class="mt-4">
                <InputLabel for="category" value="Category" />

                <TextInput
                    id="category"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.category"
                    required
                    autocomplete="category"
                />

                <InputError class="mt-2" :message="form.errors.category" />
            </div>

            <div class="mt-4">
                <InputLabel for="status" value="Status" />

                <TextInput
                    id="status"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.status"
                    required
                    autocomplete="status"
                />

                <InputError class="mt-2" :message="form.errors.status" />
            </div>

            <div class="mt-4">
                <InputLabel for="tags" value="Tags" />

                <TextInput
                    id="tags"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.tags"
                    required
                    autocomplete="tags"
                />

                <InputError class="mt-2" :message="form.errors.tags" />
            </div>

            <div class="mt-4">
                <InputLabel for="attachments" value="Attachments" />

                <TextInput
                    id="attachments"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.attachments"
                    required
                    autocomplete="attachments"
                />

                <InputError class="mt-2" :message="form.errors.attachments" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create Report
                </PrimaryButton>
            </div>
        </form>
</template>
