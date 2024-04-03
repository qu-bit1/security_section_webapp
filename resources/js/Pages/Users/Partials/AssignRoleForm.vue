<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dialog from "primevue/dialog";
import Setting from "@/Components/icons/Setting.vue";
import MultiSelect from "primevue/multiselect";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    user: Object,
    roles: {
        type: Array
    }
})

const confirmingUserUpdate = ref(false);
const form = useForm({
    roles: props.user.roles.map(role => role.id)
});
const confirmUserUpdate = () => {
    confirmingUserUpdate.value = true;
};

const submit = () => {
    form.put(route('users.assignRoles', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            confirmingUserUpdate.value = false;
        }
    });
};
</script>

<template>
    <section class="space-y-6">
        <SecondaryButton @click="confirmUserUpdate"><Setting/></SecondaryButton>
        <Dialog v-model:visible="confirmingUserUpdate" maximizable modal header="Assign roles" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <form @submit.prevent="submit">
                <div class="flex flex-col gap-2 mt-4">
                    <InputLabel for="user-roles" value="Roles"/>
                    <MultiSelect v-model="form.roles" id="user-roles" data-key="name" display="chip" :options="roles" filter optionLabel="name" optionValue="id" placeholder="Select roles"
                                 class="w-full"
                    />
                    <InputError :message="form.errors.roles"/>
                </div>

                <div class="mt-6 flex justify-end">
                    <div v-if="can('assign roles')">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-3">
                            Assign roles
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </Dialog>
    </section>
</template>
