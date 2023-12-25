<script setup>
import {onMounted, ref, watch} from 'vue';
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    modelValue: {
        required: true,
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    resetPreviews: Function,
});

const emit = defineEmits(['update:modelValue']);

const input = ref(null);
const previewImages = ref([]);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});

const handleFileChange = (event) => {
    const files = event.target.files;

    if (files.length > 0) {
        emit('update:modelValue', files)

        previewImages.value = Array.from(files).map(file => ({
            url: URL.createObjectURL(file),
            name: file.name,
        }));
    }
};

const openFilePicker = () => {
    input.value.click();
};

watch(() => props.resetPreviews, (newVal) => {
    if (newVal) {
        resetPreviews();
        props.resetPreviews(false); // Reset the prop in the parent
    }
});

const resetPreviews = () => {
    previewImages.value = [];
};
</script>

<template>
    <input
        v-show="false"
        ref="input"
        :multiple="multiple"
        type="file"
        @change="handleFileChange"
    />

    <SecondaryButton class="mt-1" type="button" @click="openFilePicker">
        Browse
    </SecondaryButton>

    <div v-if="previewImages.length > 0" class="py-4 grid grid-cols-4 gap-4">
        <template v-for="previewImage in previewImages" :key="previewImage">
            <div class="border shadow sm:rounded-lg bg-white">
                <iframe :src="previewImage.url" class="w-full min-w-full rounded-t-lg h-32"/>
                <div class="p-4 border-t">
                    <div class="flex justify-between">
                        <div class="flex-1">
                            <h2 class="text-base font-medium text-gray-900" v-text="previewImage.name"/>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
