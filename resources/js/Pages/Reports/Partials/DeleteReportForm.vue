<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';

const props = defineProps({
    report: {
        type: Object,
    }
})
const confirmingReportDeletion = ref(false);
const form = useForm({});
const confirmReportDeletion = () => {
    confirmingReportDeletion.value = true;
};

const deleteReport = () => {
    form.delete(route('reports.destroy', props.report.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingReportDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirmReportDeletion">Delete</DangerButton>

        <Modal :show="confirmingReportDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete report - <span class="font-bold">{{ report.title }}</span>?
                </h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="ms-3"
                        @click="deleteReport"
                    >
                        Delete Report
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
