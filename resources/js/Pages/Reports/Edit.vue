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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Report - {{ report.title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateReportForm :attachments="attachments" :report="report"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
