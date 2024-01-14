<script setup>

const props = defineProps({
    showHints: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div v-if="showHints" class="list-none p-2 border rounded-lg shadow absolute w-full bg-white mt-1 z-10">
        <ul class="list-inside grid md:grid-cols-2 px-3 py-2 space-y-1">
            <li>
                <span class="font-bold">[tag]</span> - <span class="text-gray-500 italic">search within a tag</span>
            </li>
            <li>
                <span class="font-bold">"words here"</span> - <span class="text-gray-500 italic">search for an exact phrase</span>
            </li>
            <li>
                <span class="font-bold">in_progress:yes</span> - <span class="text-gray-500 italic">search within status</span>
            </li>
            <li>
                <span class="font-bold">title:words</span> - <span class="text-gray-500 italic">search the title only</span>
            </li>
        </ul>
    </div>
</template>
