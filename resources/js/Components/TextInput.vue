<script setup>
import {computed, onMounted, ref} from 'vue';

const props = defineProps({
    modelValue: {
        required: true,
    },
    inputType: {
        type: String
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

const classes = computed(() => {
    return 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
});
</script>

<template>
    <textarea
        v-if="inputType === 'textarea'"
        :class="classes"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />

    <input
        v-else
        :class="classes"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
