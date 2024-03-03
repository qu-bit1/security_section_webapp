<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {inject, ref} from 'vue';
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    comment: {
        type: Object,
    }
})
const confirmingCommentUpdate = ref(false);
const form = useForm({
    body: props.comment.body,
});
const confirmCommentUpdate = () => {
    confirmingCommentUpdate.value = true;
};

const submit = () => {
    form.put(route('comments.update', props.comment.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            confirmingCommentUpdate.value = false;
        },
    });
};

const closeModal = () => {
    confirmingCommentUpdate.value = false;

    form.reset();
};

const can = inject('can')
const canEditComments = () => {
    return can('edit own comments | edit all comments');
};
</script>

<template>
    <section class="space-y-6">
        <button
            @click="confirmCommentUpdate"
            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
        >
            Edit
        </button>
        <Modal :show="confirmingCommentUpdate" @close="closeModal">
            <form @submit.prevent="submit">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Edit Comment
                    </h2>
                    <div class="mt-4">
                        <TextInput
                            id="body"
                            v-model="form.body"
                            autocomplete="body"
                            class="mt-1 block w-full"
                            input-type="textarea"
                        />

                        <InputError :message="form.errors.body" class="mt-2"/>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                        <template v-if="canEditComments()">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }"
                                           :disabled="form.processing" class="ms-3">
                                Update Comment
                            </PrimaryButton>
                        </template>
                    </div>
                </div>
            </form>
        </Modal>
    </section>
</template>
