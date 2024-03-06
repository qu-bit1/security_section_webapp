<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import Delete from "@/Components/icons/Delete.vue";
import Dialog from "primevue/dialog";

const props = defineProps({
    role: {
        type: Object,
    }
})
const confirmingRoleDeletion = ref(false);
const form = useForm({});
const confirmRoleDeletion = () => {
    confirmingRoleDeletion.value = true;
};

const deleteRole = () => {
    form.delete(route('roles.destroy', props.role.id),{
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirmRoleDeletion"><Delete/></DangerButton>
        <Dialog v-model:visible="confirmingRoleDeletion" modal header=" " :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <h2 class="text-lg font-medium">
                Are you sure you want to delete the role - <span class="font-bold">{{ role.name }}</span>?
            </h2>
            <div class="mt-6 flex justify-end">
                <DangerButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    class="ms-3"
                    @click="deleteRole"
                >
                    Delete Role
                </DangerButton>
            </div>
        </Dialog>
    </section>
</template>
