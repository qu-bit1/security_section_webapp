<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DeleteRoleForm from "@/Pages/Roles/Partials/DeleteRoleForm.vue";
import Edit from "@/Components/icons/Edit.vue";
import Column from "primevue/column";
import DataTable from "primevue/datatable";

defineProps({
    roles: Object
});
</script>


<template>
    <Head title="Permissions"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <div class="flex-1 flex justify-end">
                    <PrimaryButton :href="route('roles.create')" class="ml-2">
                        New Role
                    </PrimaryButton>
                </div>
            </div>
        </template>
        <div class="py-4 not-prose">
            <DataTable
                showGridlines
                :value="roles"
                dataKey="id"
                tableStyle="min-width: 50rem"
            >
                <Column header="#" style="width: 3rem">
                    <template #body="data">
                        {{ data.index + 1 }}
                    </template>
                </Column>
                <Column field="name" header="Name"></Column>
                <Column field="guard_name" header="Guard"></Column>
                <Column field="users_count" header="Users"></Column>
                <Column header="Action" style="width: 3rem">
                    <template #body="data">
                        <div class="flex flex-row">
                            <SecondaryButton
                                :href="route('roles.edit', data.data.id)"
                                class="ml-2"
                            >
                                <Edit/>
                            </SecondaryButton>
                            <DeleteRoleForm :key="data.data.id" :role="data.data" class="ml-2"/>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AuthenticatedLayout>
</template>
