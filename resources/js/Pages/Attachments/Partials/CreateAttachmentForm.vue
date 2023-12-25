<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileInput from "@/Components/FileInput.vue";

const form = useForm({
    attachments: null
});

const submit = () => {
    form.post(route('attachments.store'), {
        onFinish: () => form.reset('attachments'),
    });
};
</script>

<template>
    <Head title="Upload File"/>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="attachment" value="Select File"/>
            <FileInput v-model="form.attachments" :multiple="true" class="mt-1 block w-full"/>
            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                {{ form.progress.percentage }}%
            </progress>
            <InputError :message="form.errors.attachments"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Upload File
            </PrimaryButton>
        </div>
    </form>
</template>
