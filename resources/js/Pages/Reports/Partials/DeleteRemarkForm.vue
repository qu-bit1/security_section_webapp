<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';

const props = defineProps({
    remark: {
        type: Object,
    }
})
const confirmingRemarkDeletion = ref(false);
const form = useForm({});
const confirmRemarkDeletion = () => {
    confirmingRemarkDeletion.value = true;
};

const deleteRemark = () => {
    form.delete(route('remarks.destroy', props.remark.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingRemarkDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirmRemarkDeletion">Delete</DangerButton>

        <Modal :show="confirmingRemarkDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete remark ?
                </h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="ms-3"
                        @click="deleteRemark"
                    >
                        Delete Remark
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
