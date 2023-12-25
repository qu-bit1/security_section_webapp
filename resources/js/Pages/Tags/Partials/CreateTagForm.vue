<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, useForm} from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
});

const submit = () => {
    form.post(route('tags.store'));
};
</script>

<template>
    <Head title="New Tag"/>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="name" value="Title"/>

            <TextInput
                id="title"
                v-model="form.title"
                autocomplete="title"
                autofocus
                class="mt-1 block w-full"
                type="text"
            />

            <InputError :message="form.errors.title" class="mt-2"/>
        </div>

        <div class="mt-4">
            <InputLabel for="description" value="Description"/>

            <TextInput
                id="description"
                v-model="form.description"
                autocomplete="description"
                class="mt-1 block w-full"
                input-type="textarea"
            />

            <InputError :message="form.errors.description" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Create Tag
            </PrimaryButton>
        </div>
    </form>
</template>
