<script setup>
defineProps({
    file: { type: Object, required: true },
    tag: { type: String, default: 'div' },
})
defineEmits(['remove'])
</script>

<template>
    <component :is="tag" class="relative">
        <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none" aria-label="Remove" type="button" @click="$emit('remove', file)">
            <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
        <img :src="file.url" :alt="file.file.name" :title="file.file.name"/>
    </component>
</template>
