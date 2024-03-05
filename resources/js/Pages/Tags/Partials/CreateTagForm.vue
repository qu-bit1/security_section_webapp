<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, useForm} from '@inertiajs/vue3';
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";

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
        <div class="flex flex-col gap-2">
            <InputLabel for="tag-title" value="Title"/>
            <InputText id="tag-title" v-model="form.title" aria-describedby="tag-title" />
            <InputError :message="form.errors.title"/>
        </div>
        <div class="flex flex-col gap-2 mt-4">
            <InputLabel for="tag-description" value="Description"/>
            <Textarea id="tag-description" v-model="form.description" autoResize rows="5" cols="30" />
            <InputError :message="form.errors.description"/>
        </div>
        <div class="flex items-center justify-end mt-4">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Create Tag
            </PrimaryButton>
        </div>
    </form>
</template>
