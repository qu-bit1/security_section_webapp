<script setup>
import {computed} from 'vue';

defineProps({
    value: {
        type: String,
        required: true,
    },
    remove: {
        type: Function,
        required: false,
    },
})

const classes = computed(() => {
    return 'inline-flex items-center py-1 px-2 mb-2 mx-1 text-xs leading-4 font-bold tracking-wide bg-rose-100 text-rose-600 border border-rose-400 rounded-lg shadow-sm hover:border-rose-600';
});

</script>

<template>
    <div :class="classes">
        <span>
            {{ value }}
        </span>
        <button v-if="remove" type="button" class="flex-shrink-0 ml-2.5 inline-flex" @click="remove(value)">
            <span class="sr-only">Remove</span>
            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                <path stroke-linecap="round" d="M1 1l6 6m0-6L1 7"/>
            </svg>
        </button>
    </div>
</template>
