<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {ref} from 'vue';
import Tag from "@/Components/Tag.vue";

const props = defineProps({
    report: {
        type: Object,
    }
})
const viewingReportTags = ref(false);
const viewReportTags = () => {
    viewingReportTags.value = true;
};

const closeModal = () => {
    viewingReportTags.value = false;
};
</script>

<template>
    <section class="space-y-6">
        <SecondaryButton @click="viewReportTags">{{ report.tags.length }} tags</SecondaryButton>

        <Modal :show="viewingReportTags" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Tags associated to <span class="font-bold">{{ report.title }}</span>
                </h2>
                <div class="mt-6">
                    <template v-for="tag in report.tags">
                        <Tag :value="tag.title" />
                    </template>
                </div>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
