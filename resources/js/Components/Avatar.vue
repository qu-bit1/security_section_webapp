<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const props = defineProps({
    user: {
        type: Object,
        default: null,
    }
});

const user = computed(() => {
    return props.user || page.props.auth.user;
});

const initials = computed(() => {
    return user.value ? user.value.name.substring(0, 2).toUpperCase() : '';
});
</script>


<template>
  <span class="inline-flex rounded-md">
    <span
        class="inline-flex items-center px-3 py-3 border rounded-full text-sm leading-4 font-medium text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
    >
      {{ initials }}
    </span>
  </span>
</template>

