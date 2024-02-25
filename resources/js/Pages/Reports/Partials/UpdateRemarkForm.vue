<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    remark: {
        type: Object,
    }
})
const confirmingRemarkUpdate = ref(false);
const form = useForm({
    description: props.remark.description,
});
const confirmRemarkUpdate = () => {
    confirmingRemarkUpdate.value = true;
};

const submit = () => {
    form.put(route('remarks.update', props.remark.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            confirmingRemarkUpdate.value = false;
        },
    });
};

const closeModal = () => {
    confirmingRemarkUpdate.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <SecondaryButton @click="confirmRemarkUpdate">Edit</SecondaryButton>

        <Modal :show="confirmingRemarkUpdate" @close="closeModal">
            <form @submit.prevent="submit">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Edit Remark
                    </h2>
                    <div class="mt-4">
                        <InputLabel for="description" value="Description"/>

                        <TextInput
                            id="description"
                            v-model="form.description"
                            autocomplete="description"
                            class="mt-1 block w-full"
                            input-type="textarea"
                        />

                        <InputError :message="form.errors.description" class="mt-2"/>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                        <div v-if="can('edit own remarks|edit all remarks')">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-3">
                                Update Remark
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </form>
        </Modal>
    </section>
</template>
