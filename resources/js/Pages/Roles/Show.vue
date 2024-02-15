<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Tag from "@/Components/Tag.vue";
import DeleteRoleForm from "@/Pages/Roles/Partials/DeleteRoleForm.vue";

defineProps({
    role: Object,
});
</script>

<template>
    <Head :title="role.name"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Role - {{ role.name }}</h2>
            <div class="flex-1 flex justify-end">
                <SecondaryButton :href="route('roles.edit', role.id)" class="ml-2">
                    Edit Role
                </SecondaryButton>
                <DeleteRoleForm :key="role.id" :role="role" class="ml-2"/>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Role Details</h2>
                    <div>
                        <div class="py-4 grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
                                <div class="mt-1">
                                    <input id="name" :value="role.name"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           name="name"
                                           readonly
                                           type="text">
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700"
                                       for="guard">Guard</label>
                                <div class="mt-1">
                                    <input id="guard"
                                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                              name="guard"
                                              readonly
                                              :value="role.guard_name"
                                              type="text">
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <p class="block text-sm font-medium text-gray-700">Permissions</p>
                                <div class="mt-1">
                                    <template v-for="permission in role.permissions">
                                        <Tag :value="permission.name" />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

