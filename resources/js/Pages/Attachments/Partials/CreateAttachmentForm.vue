<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DragDropInput from "@/Components/DragDropInput.vue";
import {useToast} from "primevue/usetoast";

const form = useForm({
    attachments: null
});

const toast = useToast();

const submit = async () => {
    try {
        const response = await axios.post(route('attachments.store'), {"attachments": form.attachments}, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
        });

        form.progress = null;
        form.reset('attachments');
        form.errors = {};

        toast.add({severity: 'success', summary: 'Success', detail: response.data.message, life: 3000});
    } catch (error) {
        form.errors = error.response.data?.errors;
    }
};
</script>

<template>
    <Head title="Upload File"/>

    <form @submit.prevent="submit" enctype="multipart/form-data">
        <div>
            <DragDropInput v-model="form.attachments" class="mt-1 block w-full"/>
            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                {{ form.progress.percentage }}%
            </progress>
            <InputError :message="form.errors?.attachments"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Upload File
            </PrimaryButton>
        </div>
    </form>
</template>
