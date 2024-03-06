<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import Dialog from "primevue/dialog";

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
</script>

<template>
    <section class="space-y-6">
        <button
            @click="confirmCommentDeletion"
            class="block w-full px-4 py-2 text-start text-sm leading-5 text-rose-700 hover:bg-rose-100 focus:outline-none focus:bg-rose-100 transition duration-150 ease-in-out"
        >
            Delete
        </button>
        <Dialog v-model:visible="confirmingCommentDeletion" modal header="Are you sure you want to delete comment ?" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="flex justify-end">
                <DangerButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    class="ms-3"
                    @click="deleteComment"
                >
                    Delete Comment
                </DangerButton>
            </div>
        </Dialog>
    </section>
</template>
