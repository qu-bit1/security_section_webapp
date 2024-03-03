<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import CreateAttachmentForm from "@/Pages/Attachments/Partials/CreateAttachmentForm.vue";
</script>

<template>
    <Head title="Upload File"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Upload File</h2>
        </template>

        <div class="p-4 sm:p-8">
            <CreateAttachmentForm class="max-w-xl"/>
        </div>
    </AuthenticatedLayout>
</template>
