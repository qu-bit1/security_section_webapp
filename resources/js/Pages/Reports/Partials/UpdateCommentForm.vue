<script setup>
import {useForm} from '@inertiajs/vue3';
import {inject, ref} from 'vue';
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Textarea from "primevue/textarea";
import Dialog from "primevue/dialog";

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
        <Dialog v-model:visible="confirmingCommentUpdate" maximizable modal header="Edit Comment" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <form @submit.prevent="submit">
                <div class="flex flex-col gap-2 mt-2">
                    <Textarea id="body" v-model="form.body" autocomplete="body" autoResize rows="5" cols="30" />
                    <InputError :message="form.errors.body"/>
                </div>

                <div class="mt-6 flex justify-end">
                    <template v-if="canEditComments()">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }"
                                       :disabled="form.processing" class="ms-3">
                            Update Comment
                        </PrimaryButton>
                    </template>
                </div>
            </form>
        </Dialog>
    </section>
</template>
