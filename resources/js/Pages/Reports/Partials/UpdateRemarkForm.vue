<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Edit from "@/Components/icons/Edit.vue";
import Textarea from "primevue/textarea";
import Dialog from "primevue/dialog";

const props = defineProps({
    remark: {
        type: Object,
    }
})
const confirmingRemarkUpdate = ref(false);
const form = useForm({
    body: props.remark.body,
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
</script>

<template>
    <section class="space-y-6">
        <SecondaryButton @click="confirmRemarkUpdate"><Edit/></SecondaryButton>
        <Dialog v-model:visible="confirmingRemarkUpdate" maximizable modal header="Edit Remark" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <form @submit.prevent="submit">
                <div class="flex flex-col gap-2 mt-2">
                    <Textarea id="body" v-model="form.body" autocomplete="body" autoResize rows="5" cols="30" />
                    <InputError :message="form.errors.body"/>
                </div>

                <div class="mt-6 flex justify-end">
                    <div v-if="can('edit own remarks|edit all remarks')">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-3">
                            Update Remark
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </Dialog>
    </section>
</template>
