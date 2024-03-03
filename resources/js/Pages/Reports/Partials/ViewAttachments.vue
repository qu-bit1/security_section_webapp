<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {ref} from 'vue';
import Attachment from "@/Components/icons/Attachment.vue";

const props = defineProps({
    report: {
        type: Object,
    }
})
const viewingReportAttachments = ref(false);
const viewReportAttachments = () => {
    viewingReportAttachments.value = true;
};

const closeModal = () => {
    viewingReportAttachments.value = false;
};
</script>

<template>
    <section class="space-y-6">
        <SecondaryButton @click="viewReportAttachments">{{ report.attachments.length }} <Attachment class="ml-0.5"/></SecondaryButton>

        <Modal :show="viewingReportAttachments" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Attachments associated to <span class="font-bold">{{ report.title }}</span>
                </h2>
                <div class="mt-6">
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
                </div>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
