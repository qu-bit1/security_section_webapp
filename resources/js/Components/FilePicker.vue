<script setup>
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TabButton from "@/Components/TabButton.vue";
import {ref, watch} from "vue";
import {router, useForm} from '@inertiajs/vue3'
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import FileInput from "@/Components/FileInput.vue";

const props = defineProps({
    attachments: {
        type: Array,
    },
    modelValue: {
        type: Array,
    },
});
const emit = defineEmits(['update:modelValue']);
const attachingToReport = ref(false);
const selectedFiles = ref([...props.modelValue]);
const currentTab = ref("upload");
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

const tabs = [
    {name: "Upload", slug: "upload", current: true},
    {name: "Insert", slug: "insert", current: false},
];

const changeTab = (tab) => {
    currentTab.value = tab;
};

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
    <SecondaryButton @click="attachToReport" class="mt-1" type="button">
        {{ modelValue.length ? `${modelValue.length} files selected` : 'Attach Files' }}
    </SecondaryButton>
    <Modal :show="attachingToReport" @close="closeModal">
        <div class="flex p-4 border-b items-center">
            <div class="flex-1">
                <h2 class="text-lg font-medium text-gray-900">Insert Files</h2>
            </div>
            <div class="flex-1">
                <div class="flex justify-end">
                    <SecondaryButton @click="closeModal">x</SecondaryButton>
                </div>
            </div>
        </div>

        <div class="hidden space-x-8 px-4 sm:flex h-14 bg-white border-b">
            <template v-for="(tab, index) in tabs" :key="index">
                <TabButton
                    :type="'button'"
                    :active="currentTab === tab.slug"
                    @click.prevent="changeTab(tab.slug)"
                >
                    {{ tab.name }}
                </TabButton>
            </template>
        </div>
        <!--        upload tab content-->
        <div>
            <form @submit.prevent="uploadFile" v-if="currentTab === 'upload'">
                <div class="p-6">
                    <div>
                        <InputLabel for="attachment" value="Select File"/>

                        <FileInput class="mt-1 block w-full" v-model="form.attachments" :multiple="true"/>
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                        <InputError :message="form.errors.attachments"/>
                    </div>
                </div>
                <div class="p-6 flex justify-end border-t">
                    <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
                    <DangerButton
                        class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    >
                        Upload File
                    </DangerButton>
                </div>
            </form>
        </div>
        <!--        insert tab content-->
        <div v-if="currentTab === 'insert'">
            <div class="p-6 grid grid-cols-3 gap-4 overflow-y-auto">
                <template v-for="attachment in attachments" :key="attachment.id">
                    <div
                        class="border rounded shadow cursor-pointer"
                        :class="{'border-blue-500': selectedFiles.includes(attachment.id) }"
                        @click="toggleFileSelection(attachment.id)"
                    >
                        <img :src="attachment.path" class="w-full h-32 object-cover" alt=""/>
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
<!--            <div class="px-6">-->
<!--                <Paginator :paginator="attachments"/>-->
<!--            </div>-->
            <div class="p-6 flex justify-end border-t sticky bottom-0 bg-white"
                 v-if="selectedFiles.length > 0">
                <div class="flex-1">
                    <p class="text-gray-700">{{ selectedFiles.length }} files selected</p>
                </div>
                <SecondaryButton @click="closeModal" type="button">Cancel</SecondaryButton>
                <DangerButton
                    class="ms-3"
                    @click="insertAttachments"
                    type="button"
                >
                    Attach Files
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>

