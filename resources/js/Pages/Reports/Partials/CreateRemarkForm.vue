<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    report: {
        type: Object,
    }
})
const confirmingRemarkCreation = ref(false);
const form = useForm({
    description: '',
});

const confirmRemarkCreation = () => {
    confirmingRemarkCreation.value = true;
};

const submit = () => {
    form.post(route('reports.remarks.store', props.report.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            confirmingRemarkCreation.value = false;
        },
    });
};

const closeModal = () => {
    confirmingRemarkCreation.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <PrimaryButton @click="confirmRemarkCreation">Add Remark</PrimaryButton>

        <Modal :show="confirmingRemarkCreation" @close="closeModal">
            <form @submit.prevent="submit">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Add Remark
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
                        <div v-if="can('create remarks')">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-3">
                                Create Remark
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </form>
        </Modal>
    </section>
</template>
