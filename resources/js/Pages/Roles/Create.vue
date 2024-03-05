<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import CreateRoleForm from "@/Pages/Roles/Partials/CreateRoleForm.vue";

const props = defineProps({
    permissions: {
        type: Array,
    },
})
</script>

<template>
    <Head title="New Role"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Role</h2>
        </template>

        <div class="px-4 pb-4 sm:px-8 sm:pb-8">
            <CreateRoleForm :permissions="permissions" class="max-w-xl"/>
        </div>
    </AuthenticatedLayout>
</template>
