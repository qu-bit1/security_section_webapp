<script setup>
defineProps({
    value: {
        type: String,
    },
    required: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <label class="block">
        <p v-if="value" :class="required ? 'after:content-[\'*\'] after:ml-0.5 after:text-red-500' : ''">{{ value }}</p>
        <span v-else><slot/></span>
    </label>
</template>
