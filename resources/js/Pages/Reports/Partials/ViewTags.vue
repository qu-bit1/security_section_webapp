<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {ref} from 'vue';
import TagIcon from "@/Components/icons/Tag.vue"
import Tag from "primevue/tag";
import Dialog from "primevue/dialog";

const props = defineProps({
    report: {
        type: Object,
    }
})
const viewingReportTags = ref(false);
const viewReportTags = () => {
    viewingReportTags.value = true;
};
</script>

<template>
    <section class="space-y-6">
        <SecondaryButton @click="viewReportTags">{{ report.tags.length }} <TagIcon class="ml-0.5" /></SecondaryButton>
        <Dialog v-model:visible="viewingReportTags" maximizable modal :header="'Tags associated to '+report.serial_number" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <template v-for="tag in report.tags">
                <Tag :value="tag.title" class="mr-2 mb-2"/>
            </template>
        </Dialog>
    </section>
</template>
