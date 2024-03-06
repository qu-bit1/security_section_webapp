<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import Delete from "@/Components/icons/Delete.vue";
import Dialog from "primevue/dialog";

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
</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirmRemarkDeletion"><Delete/></DangerButton>

        <Dialog v-model:visible="confirmingRemarkDeletion" modal header="Are you sure you want to delete remark ?" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="flex justify-end">
                <DangerButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    class="ms-3"
                    @click="deleteRemark"
                >
                    Delete Remark
                </DangerButton>
            </div>
        </Dialog>
    </section>
</template>
