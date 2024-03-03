<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import CreateReportForm from "@/Pages/Reports/Partials/CreateReportForm.vue";

const props = defineProps({
    attachments: {
        type: Array,
    },
})
</script>

<template>
    <Head title="New Report"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Report</h2>
        </template>

        <div class="p-4 sm:p-8">
                <CreateReportForm :attachments="attachments" class="max-w-xl"/>
        </div>
    </AuthenticatedLayout>
</template>
