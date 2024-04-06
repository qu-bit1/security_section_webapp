<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import CreateReportForm from "@/Pages/Reports/Partials/CreateReportForm.vue";

const props = defineProps({
    tags: Object,
    params : Object
})
</script>

<template>
    <Head title="New Report"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Create Report</h2>
        </template>

        <div class="px-4 pb-4 sm:px-8 sm:pb-8">
            <CreateReportForm :tags="tags" :params="params" class="max-w-xl"/>
        </div>
    </AuthenticatedLayout>
</template>
