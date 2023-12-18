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
                type="text"
                class="mt-1 block w-full"
                v-model="form.title"
                autofocus
                autocomplete="title"
            />

            <InputError class="mt-2" :message="form.errors.title"/>
        </div>

        <div class="mt-4">
            <InputLabel for="description" value="Description"/>

            <TextInput
                id="description"
                input-type="textarea"
                class="mt-1 block w-full"
                v-model="form.description"
                autocomplete="description"
            />

            <InputError class="mt-2" :message="form.errors.description"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create Tag
            </PrimaryButton>
        </div>
    </form>
</template>
