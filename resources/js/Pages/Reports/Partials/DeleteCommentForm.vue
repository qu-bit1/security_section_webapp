<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';

const props = defineProps({
    comment: {
        type: Object,
    }
})
const confirmingCommentDeletion = ref(false);
const form = useForm({});
const confirmCommentDeletion = () => {
    confirmingCommentDeletion.value = true;
};

const deleteComment = () => {
    form.delete(route('comments.destroy', props.comment.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingCommentDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <button
            @click="confirmCommentDeletion"
            class="block w-full px-4 py-2 text-start text-sm leading-5 text-rose-700 hover:bg-rose-100 focus:outline-none focus:bg-rose-100 transition duration-150 ease-in-out"
        >
            Delete
        </button>

        <Modal :show="confirmingCommentDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete comment ?
                </h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="ms-3"
                        @click="deleteComment"
                    >
                        Delete Comment
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
