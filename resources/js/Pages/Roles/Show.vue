<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Tag from "@/Components/Tag.vue";
import DeleteRoleForm from "@/Pages/Roles/Partials/DeleteRoleForm.vue";
import Edit from "@/Components/icons/Edit.vue";

defineProps({
    role: Object,
});

const displayFields = [
    {key: 'name', label: 'Name', inline: true},
    {key: 'guard_name', label: 'Guard', inline: true},
    {key: 'permissions', label: 'Permissions', inline: false},
];

const getHeadingClass = (inline) => {
    return inline ? 'inline-block mr-2' : '';
};
</script>

<template>
    <Head :title="role.name"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-center items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Role - {{ role.name }}</h2>
                <div class="flex-1 flex justify-end">
                    <SecondaryButton :href="route('roles.edit', role.id)" class="ml-2">
                        <Edit />
                    </SecondaryButton>
                    <DeleteRoleForm :key="role.id" :role="role" class="ml-2"/>
                </div>
            </div>
        </template>

        <div
            class="relative prose max-w-none lg:max-w-full xl:max-w-none prose-img:rounded-xl prose-img:w-full mx-auto prose-a:no-underline prose-a:text-rose-600 prose-table p-4 sm:p-8 bg-white">
            <template v-for="field in displayFields" :key="field.key">
                <div class="mb-1">
                    <h3 :class="getHeadingClass(field.inline)">{{ field.label }} {{ field.inline ? ':' : '' }}</h3>
                    <template v-if="field.key === 'permissions'">
                        <div class="flex space-x-2">
                            <template v-for="permission in role[field.key]">
                                <Tag :value="permission.name"/>
                            </template>
                        </div>
                    </template>
                    <template v-else>
                        <span>{{ role[field.key] }}</span>
                    </template>
                </div>
            </template>
        </div>
    </AuthenticatedLayout>
</template>

