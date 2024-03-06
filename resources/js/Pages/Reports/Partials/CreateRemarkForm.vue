<script setup>
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import Add from "@/Components/icons/Add.vue";
import Textarea from "primevue/textarea";
import Dialog from 'primevue/dialog';

const props = defineProps({
    report: {
        type: Object,
    }
})
const confirmingRemarkCreation = ref(false);
const form = useForm({
    body: '',
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

</script>

<template>
    <section class="space-y-6">
        <PrimaryButton @click="confirmRemarkCreation"><Add /></PrimaryButton>
        <Dialog v-model:visible="confirmingRemarkCreation" maximizable modal header="Add Remark" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <form @submit.prevent="submit">
                <div class="flex flex-col gap-2 mt-2">
                    <Textarea id="body" v-model="form.body" autocomplete="body" autoResize rows="5" cols="30" />
                    <InputError :message="form.errors.body"/>
                </div>

                <div class="mt-6 flex justify-end">
                    <div v-if="can('create remarks')">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-3">
                            Create Remark
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </Dialog>
    </section>
</template>
