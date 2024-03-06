<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, useForm} from '@inertiajs/vue3';
import InputText from "primevue/inputtext";
import MultiSelect from "primevue/multiselect";

const props = defineProps({
    role: {
        type: Object,
    },
    permissions: {
        type: Array,
    }
});

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions.map(permission => permission.id),
});

const submit = () => {
    form.put(route('roles.update', props.role.id));
};
</script>

<template>
    <Head title="New Role"/>

    <form @submit.prevent="submit">
        <div class="flex flex-col gap-2">
            <InputLabel for="role-name" value="Name"/>
            <InputText id="role-name" v-model="form.name" aria-describedby="role-name" />
            <InputError :message="form.errors.name"/>
        </div>

        <div class="flex flex-col gap-2 mt-4">
            <InputLabel for="role-permissions" value="Permissions"/>
            <MultiSelect v-model="form.permissions" id="role-permissions" data-key="name" display="chip" :options="permissions" filter optionLabel="name" optionValue="id" placeholder="Select permissions"
                         class="w-full"
            />
            <InputError :message="form.errors.permissions"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Update Role
            </PrimaryButton>
        </div>
    </form>
</template>
