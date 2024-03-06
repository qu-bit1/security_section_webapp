<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {ref} from 'vue';
import Attachment from "@/Components/icons/Attachment.vue";
import Dialog from "primevue/dialog";

const props = defineProps({
    report: Object,
})
const viewingReportAttachments = ref(false);
const viewReportAttachments = () => {
    viewingReportAttachments.value = true;
};
</script>

<template>
    <section class="space-y-6">
        <SecondaryButton @click="viewReportAttachments">{{ report.attachments.length }} <Attachment class="ml-0.5"/></SecondaryButton>
        <Dialog v-model:visible="viewingReportAttachments" maximizable modal :header="'Attachments associated to '+report.serial_number" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <template v-for="attachment in report.attachments">
                    <div
                        class="border shadow sm:rounded-lg bg-white"
                    >
                        <img :alt="'preview of '+attachment.name" :src="attachment.path"
                             class="w-full h-32 object-cover"/>
                        <div class="p-4 border-t">
                            <div class="flex justify-between">
                                <div class="flex-1">
                                    <h2 class="text-base font-medium text-gray-900">{{ attachment.name }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </Dialog>
    </section>
</template>
