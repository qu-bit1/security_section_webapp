<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    attachment: null
});

const submit = () => {
    form.post(route('attachments.store'), {
        onFinish: () => form.reset('attachment'),
    });
};
</script>


<template>
    <Head title="Upload File"/>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="attachment" value="Select File"/>

            <input
                id="attachment"
                type="file"
                class="mt-1 block w-full"
                autofocus
                autocomplete="attachment"
                @input="form.attachment = $event.target.files[0]"
            />
            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                {{ form.progress.percentage }}%
            </progress>
            <InputError class="mt-2" :message="form.errors.attachment"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Upload File
            </PrimaryButton>
        </div>
    </form>
</template>
