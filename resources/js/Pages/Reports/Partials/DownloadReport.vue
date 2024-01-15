<script setup>
import { defineProps } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

const props = defineProps({
    report: {
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
                <!-- Check if a specific report is provided -->
                <template v-if="report">
                    <DropdownLink anchor :href="route('reports.generate', { report: report.id, format: 'pdf' })">
                        Generate PDF
                    </DropdownLink>
                    <DropdownLink anchor :href="route('reports.generate', { report: report.id, format: 'csv' })">
                        Generate CSV
                    </DropdownLink>
                </template>

                <!-- If no specific report is provided, show a generic export link with multiple formats -->
                <template v-else>
                    <DropdownLink anchor :href="route('reports.export', { format: 'csv' })">
                        Export as CSV
                    </DropdownLink>
                </template>
            </template>
        </Dropdown>
    </div>
</template>
