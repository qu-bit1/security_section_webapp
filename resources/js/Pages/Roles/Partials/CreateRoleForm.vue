<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, useForm} from '@inertiajs/vue3';
import MultiSelectInput from "@/Components/MultiSelectInput.vue";

const props = defineProps({
    permissions: {
        type: Array,
    }
});

const form = useForm({
    name: '',
    permissions: []
});

const submit = () => {
    form.post(route('roles.store'));
};


const searchPermissions = async (search) => {
    const filteredPermissions = props.permissions.filter(permission => {
        return permission.name.includes(search);
    });

    return filteredPermissions.map(permission => {
        return {
            'key': permission.id,
            'value': permission.name
        };
    });
};
</script>

<template>
    <Head title="New Report"/>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="name" value="Name"/>

            <TextInput
                id="name"
                v-model="form.name"
                autocomplete="name"
                autofocus
                class="mt-1 block w-full"
                type="text"
            />

            <InputError :message="form.errors.name" class="mt-2"/>
        </div>

        <div class="mt-4">
            <InputLabel for="permissions" value="Permissions"/>

            <MultiSelectInput
                id="permissions"
                v-model="form.permissions"
                :search-function="searchPermissions"
                autocomplete="permissions"
                class="mt-1 block w-full"
            />

            <InputError :message="form.errors.tags" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Create Role
            </PrimaryButton>
        </div>
    </form>
</template>
