<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, useForm} from '@inertiajs/vue3';
import SelectInput from "@/Components/SelectInput.vue";
import FilePicker from "@/Components/FilePicker.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import {shiftOptions, statusOptions} from "@/Compositions/Constants.js";

const props = defineProps({
    attachments: {
        type: Array,
    }
});

const form = useForm({
    shift: shiftOptions[0].value,
    description: '',
    venue: '',
    reporter: '',
    category: '',
    status: statusOptions[0].value,
    tags: [],
    attachments: [],
});

const submit = () => {
    form.post(route('reports.store'));
};

const searchTags = async (search) => {
    try {
        const response = await axios.get(route('tags.search', {search}));
        return response.data.tags;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
</script>

<template>
    <Head title="New Report"/>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="tags" value="Tags"/>

            <MultiSelectInput
                id="tags"
                v-model="form.tags"
                :search-function="searchTags"
                autocomplete="tags"
                class="mt-1 block w-full"
            />

            <InputError :message="form.errors.tags" class="mt-2"/>
        </div>
        <div class="mt-4">
            <InputLabel for="shift" value="Shift"/>

            <SelectInput
                id="shift"
                v-model="form.shift"
                :options="shiftOptions"
                autocomplete="shift"
                class="mt-1 block w-full"
            />

            <InputError :message="form.errors.shift" class="mt-2"/>
        </div>
        <div class="mt-4">
            <InputLabel for="status" value="Status"/>

            <SelectInput
                id="status"
                v-model="form.status"
                :options="statusOptions"
                autocomplete="status"
                class="mt-1 block w-full"
            />

            <InputError :message="form.errors.status" class="mt-2"/>
        </div>
        <div v-if="form.status !== 'normal'">
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

            <div class="mt-4">
                <InputLabel value="Attachments"/>
                <FilePicker v-model="form.attachments" :attachments="attachments"/>
                <InputError :message="form.errors.attachments" class="mt-2"/>
            </div>

            <div class="mt-4">
                <InputLabel for="venue" value="Venue"/>

                <TextInput
                    id="venue"
                    v-model="form.venue"
                    autocomplete="venue"
                    class="mt-1 block w-full"
                    type="text"
                />

                <InputError :message="form.errors.venue" class="mt-2"/>
            </div>

            <div class="mt-4">
                <InputLabel for="reporter" value="Reporter"/>

                <TextInput
                    id="reporter"
                    v-model="form.reporter"
                    autocomplete="reporter"
                    class="mt-1 block w-full"
                    type="text"
                />

                <InputError :message="form.errors.reporter" class="mt-2"/>
            </div>
        </div>
        <div v-if="can('create reports')" class="flex items-center justify-end mt-4">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Create Report
            </PrimaryButton>
        </div>
    </form>
</template>
