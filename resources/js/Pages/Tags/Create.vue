<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import CreateTagForm from "@/Pages/Tags/Partials/CreateTagForm.vue";
</script>

<template>
    <Head title="New Tag"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Tag</h2>
        </template>

        <div class="p-4 sm:p-8">
            <CreateTagForm class="max-w-xl"/>
        </div>
    </AuthenticatedLayout>
</template>
