<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Edit from "@/Components/icons/Edit.vue";
import Textarea from "primevue/textarea";

const props = defineProps({
    remark: {
        type: Object,
    }
})
const confirmingRemarkUpdate = ref(false);
const form = useForm({
    body: props.remark.body,
});
const confirmRemarkUpdate = () => {
    confirmingRemarkUpdate.value = true;
};

const submit = () => {
    form.put(route('remarks.update', props.remark.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            confirmingRemarkUpdate.value = false;
        },
    });
};

const closeModal = () => {
    confirmingRemarkUpdate.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <SecondaryButton @click="confirmRemarkUpdate"><Edit/></SecondaryButton>

        <Modal :show="confirmingRemarkUpdate" @close="closeModal">
            <form @submit.prevent="submit">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Edit Remark
                    </h2>

                    <div class="flex flex-col gap-2 mt-4">
                        <Textarea id="body" v-model="form.body" autocomplete="body" autoResize rows="5" cols="30" />
                        <InputError :message="form.errors.body"/>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                        <div v-if="can('edit own remarks|edit all remarks')">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-3">
                                Update Remark
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </form>
        </Modal>
    </section>
</template>
