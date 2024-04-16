<script setup>
import {ref} from 'vue';
import Attachment from "@/Components/icons/Attachment.vue";
import Dialog from "primevue/dialog";
import {attachmentPath} from "@/Compositions/Helpers.js";

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
        <span class="hover:cursor-pointer whitespace-nowrap" @click="viewReportAttachments">{{ report.attachments.length }} <Attachment class="ml-1"/></span>
        <Dialog v-model:visible="viewingReportAttachments" maximizable modal :header="'Attachments associated to '+report.serial_number" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"  v-if="report.attachments.length > 0">
                <template v-for="attachment in report.attachments">
                    <div class="border shadow-sm rounded-lg bg-white">
                        <object :data="attachmentPath(attachment.path)" class="w-full m-0 not-prose rounded-t-lg object-cover" width="100%" height="240px">
                            <div class="p-4">preview not available</div>
                        </object>
                        <div class="p-4 border-t">
                            <h2 class="text-base font-medium text-gray-900">{{ attachment.name }}</h2>
                        </div>
                    </div>
                </template>
            </div>
        </Dialog>
    </section>
</template>
