<script setup>
import { defineProps } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "primevue/usetoast";

const props = defineProps({
    reports: {
        type: Object,
        required: false,
    },
});
</script>

<template>
    <div class="ms-3 relative">
        <Dropdown align="right" width="48">
            <template #trigger>
                <SecondaryButton>
                    <slot />
                </SecondaryButton>
            </template>

            <template #content>
                <DropdownLink anchor :href="route('reports.export', { reports: reports.map(report => report.id) })">
                    Export as ZIP
                </DropdownLink>
            </template>
        </Dropdown>
    </div>
</template>
