<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import Delete from "@/Components/icons/Delete.vue";
import {useToast} from "primevue/usetoast";
import Dialog from "primevue/dialog";

const props = defineProps({
    reports: {
        type: Object,
    }
})
const confirmingReportDeletion = ref(false);
const form = useForm({});
const confirmReportDeletion = () => {
    confirmingReportDeletion.value = true;
};

const toast = useToast();

const deleteReports = () => {
    const reportIds = props.reports.map(report => report.id);
    form.delete(route('reports.massDestroy', { reports: reportIds }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            confirmingReportDeletion.value = false;
            toast.add({severity: 'success', summary: 'Success', detail: 'Reports deleted successfully', life:3000});
        },
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirmReportDeletion"><Delete/></DangerButton>
        <Dialog v-model:visible="confirmingReportDeletion" modal header=" " :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <h2 class="text-lg font-medium">
                Are you sure you want to delete report - {{ reports.length }} reports?
            </h2>
            <div class="mt-6 flex justify-end">
                <DangerButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    class="ms-3"
                    @click="deleteReports"
                >
                    Delete Reports
                </DangerButton>
            </div>
        </Dialog>
    </section>
</template>
