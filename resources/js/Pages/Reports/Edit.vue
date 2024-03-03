<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import UpdateReportForm from "@/Pages/Reports/Partials/UpdateReportForm.vue";

const props = defineProps({
    report: {
        type: Object,
    },
    attachments: {
        type: Array,
    },
})
</script>

<template>
    <Head title="Edit Report"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Report - {{ report.serial_number }}</h2>
        </template>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <UpdateReportForm :attachments="attachments" :report="report"/>
        </div>
    </AuthenticatedLayout>
</template>
