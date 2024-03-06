<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {ref, watch} from "vue";
import {router, useForm} from '@inertiajs/vue3'
import InputError from "@/Components/InputError.vue";
import DragDropInput from "@/Components/DragDropInput.vue";
import Dialog from "primevue/dialog";
import TabMenu from 'primevue/tabmenu';


const props = defineProps({
    attachments: Object,
    modelValue: {
        type: Array,
    },
});
const emit = defineEmits(['update:modelValue']);
const attachingToReport = ref(false);
const selectedFiles = ref([...props.modelValue]);
const currentTab = ref(0);
const form = useForm({
    attachments: null
});

watch(() => props.modelValue, (value) => {
    selectedFiles.value = [...value];
});

const closeModal = () => {
    attachingToReport.value = false;
    selectedFiles.value = [];
};

const attachToReport = () => {
    attachingToReport.value = true;
};

const uploadFile = () => {
    form.post(route('attachments.store'), {
        onSuccess: () => {
            form.reset('attachment');
            router.reload({only: ['attachments']})
            changeTab('insert')
        }
    });
};

const insertAttachments = () => {
    if (selectedFiles.value.length > 0) {
        const uniqueSelectedFiles = new Set(selectedFiles.value);
        emit("update:modelValue", Array.from(uniqueSelectedFiles));
    }
    closeModal();
};

const tabs = ref([
    { label: 'Upload', icon: 'pi pi-upload' },
    { label: 'Insert', icon: 'pi pi-chart-line' },
]);

const toggleFileSelection = (attachmentId) => {
    const index = selectedFiles.value.indexOf(attachmentId);
    if (index === -1) {
        selectedFiles.value.push(attachmentId);
    } else {
        selectedFiles.value.splice(index, 1);
    }
};

</script>
<template>
    <SecondaryButton class="mt-1" type="button" @click="attachToReport">
        {{ modelValue.length ? `${modelValue.length} files selected` : 'Attach Files' }}
    </SecondaryButton>
    <Dialog v-model:visible="attachingToReport" maximizable modal header="Insert Files" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <TabMenu v-model:activeIndex="currentTab" :model="tabs" />
        <!--        upload tab content-->
        <form v-if="currentTab === 0" @submit.prevent="uploadFile">
            <div class="py-6">
                <DragDropInput v-model="form.attachments" class="mt-1 block w-full"/>
                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{ form.progress.percentage }}%
                </progress>
                <InputError :message="form.errors.attachments" class="mt-2"/>
            </div>
            <div class="flex justify-end">
                <DangerButton
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4"
                >
                    Upload File
                </DangerButton>
            </div>
        </form>
        <!--        insert tab content-->
        <div v-if="currentTab === 1">
            <div class="p-6 grid grid-cols-3 gap-4 overflow-y-auto">
                <template v-for="attachment in attachments" :key="attachment.id">
                    <div
                        :class="{'border-blue-500': selectedFiles.includes(attachment.id) }"
                        class="border rounded shadow cursor-pointer"
                        @click="toggleFileSelection(attachment.id)"
                    >
                        <img :src="attachment.path" alt="" class="w-full h-32 object-cover"/>
                        <div class="p-4">
                            <div class="flex justify-between">
                                <div class="flex-1">
                                    <h2 class="text-base font-medium text-gray-900">{{ attachment.name }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div v-if="selectedFiles.length > 0" class="flex justify-end sticky bottom-0 bg-white">
                <div class="flex-1">
                    <p class="text-gray-700">{{ selectedFiles.length }} files selected</p>
                </div>
                <DangerButton
                    class="ms-3"
                    type="button"
                    @click="insertAttachments"
                >
                    Attach Files
                </DangerButton>
            </div>
        </div>
    </Dialog>
</template>

