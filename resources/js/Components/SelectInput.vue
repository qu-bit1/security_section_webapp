<script setup>
import {computed} from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
});

const classes = computed(() => {
    return 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
});
</script>

<template>
    <select
        ref="input"
        :class="classes"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
        <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.label }}
        </option>
    </select>
</template>
