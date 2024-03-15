<script setup>
import DropZone from "@/Components/DropZone.vue";
import useFileList from "@/Compositions/FileList.js";
import FilePreview from "@/Components/FilePreview.vue";

defineProps({
    modelValue: {
        required: true,
    },
    multiple: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['update:modelValue']);

const { files, addFiles, removeFile } = useFileList({
    emitter: emit,
    addFilesEvent: ['update:modelValue'],
    removeFileEvent: ['update:modelValue'],
});

function onInputChange(e) {
    addFiles(e.target.files)
    e.target.value = null
}
</script>

<template>
    <div class="relative flex flex-col p-4 text-gray-400 border border-gray-200 rounded">
        <DropZone @files-dropped="addFiles" #default="{ dropZoneActive }">
            <label for="dragdrop-input"
                   :class="`relative flex flex-col items-center justify-center py-10 text-center text-gray-400 border-2 border-dashed rounded cursor-pointer ${dropZoneActive ? 'border-blue-500' : ' border-gray-200'}`">
                <svg class="w-6 h-6 mr-1 text-current-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span v-if="dropZoneActive">Drop your files here.</span>
                <span class="m-0" v-else>Drag your files here or click in this area.</span>
                <input type="file" id="dragdrop-input" :multiple="multiple" @change="onInputChange" class="hidden absolute inset-0 z-50 w-full h-full p-0 m-0 outline-none opacity-0 cursor-pointer"/>
            </label>
        </DropZone>
        <div v-show="files.length && modelValue">
            <div class="grid grid-cols-2 gap-4 mt-4 md:grid-cols-6">
                <FilePreview v-for="file of files" :key="file.id" :file="file" @remove="removeFile"/>
            </div>
        </div>
    </div>
</template>
