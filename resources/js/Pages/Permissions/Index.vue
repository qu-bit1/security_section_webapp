<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";

defineProps({
    permissions : Object,
});
</script>


<template>
    <Head title="Permissions"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permissions</h2>
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
                                        Roles
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-skin-base divide-y divide-gray-200 text-gray-600">
                                <template v-for="(permission, index) in permissions" :key="permission.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ index+1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ permission.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ permission.guard_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ permission.roles_count }}
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
