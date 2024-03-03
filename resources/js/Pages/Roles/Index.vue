<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DeleteRoleForm from "@/Pages/Roles/Partials/DeleteRoleForm.vue";
import Edit from "@/Components/icons/Edit.vue";

defineProps({
    roles: Object
});
</script>


<template>
    <Head title="Permissions"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles</h2>
                <div class="flex-1 flex justify-end">
                    <PrimaryButton :href="route('roles.create')" class="ml-2">
                        New Role
                    </PrimaryButton>
                </div>
            </div>
        </template>
        <div class="m-auto">
            <div class="bg-skin-base overflow-hidden  sm:rounded-lg p-2">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg border bg-white">
                            <table class="min-w-full divide-y divide-gray-200 sm:rounded-lg">
                                <thead class="bg-gray-50 font-medium text-left">
                                <tr>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        #
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Name
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Guard
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Users
                                    </th>
                                    <th class="relative px-6 py-4" scope="col">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-skin-base divide-y divide-gray-200 text-gray-600">
                                <template v-for="(role, index) in roles" :key="role.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ index+1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link
                                                :href="route('roles.show', role.id)"
                                            >
                                                {{ role.name }}
                                            </Link>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ role.guard_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ role.users_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex flex-row">
                                            <SecondaryButton
                                                :href="route('roles.edit', role.id)"
                                                class="ml-2"
                                            >
                                                <Edit/>
                                            </SecondaryButton>
                                            <DeleteRoleForm :key="role.id" :role="role" class="ml-2"/>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
