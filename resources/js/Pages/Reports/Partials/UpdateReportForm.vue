<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, useForm} from '@inertiajs/vue3';
import SelectInput from "@/Components/SelectInput.vue";
import FilePicker from "@/Components/FilePicker.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";

const props = defineProps({
    report: {
        type: Object,
    },
    attachments: {
        type: Array,
    }
});

const form = useForm({
    title: props.report.title,
    description: props.report.description,
    venue: props.report.venue,
    reporter: props.report.reporter,
    category: '',
    status: props.report.status,
    tags: props.report.tags.map(tag => tag.title),
    attachments: props.report.attachments.map(attachment => attachment.id),
});

const submit = () => {
    form.put(route('reports.update', props.report.id));
};

const statusOptions = [
    {value: 'open', label: 'Open'},
    {value: 'in_progress', label: 'In Progress'},
    {value: 'resolved', label: 'Resolved'},
    {value: 'closed', label: 'Closed'},
];
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
            <InputLabel for="name" value="Title"/>

            <TextInput
                id="title"
                type="text"
                class="mt-1 block w-full"
                v-model="form.title"
                autofocus
                autocomplete="title"
            />

            <InputError class="mt-2" :message="form.errors.title"/>
        </div>

        <div class="mt-4">
            <InputLabel for="description" value="Description"/>

            <TextInput
                id="description"
                input-type="textarea"
                class="mt-1 block w-full"
                v-model="form.description"
                autocomplete="description"
            />

            <InputError class="mt-2" :message="form.errors.description"/>
        </div>

        <div class="mt-4">
            <InputLabel for="tags" value="Tags"/>

            <MultiSelectInput
                id="tags"
                class="mt-1 block w-full"
                v-model="form.tags"
                autocomplete="tags"
                :search-function="searchTags"
            />

            <InputError class="mt-2" :message="form.errors.tags"/>
        </div>

        <div class="mt-4">
            <InputLabel value="Attachments"/>
            <FilePicker :attachments="attachments" v-model="form.attachments"/>
            <InputError class="mt-2" :message="form.errors.attachments"/>
        </div>

        <div class="mt-4">
            <InputLabel for="venue" value="Venue"/>

            <TextInput
                id="venue"
                type="text"
                class="mt-1 block w-full"
                v-model="form.venue"
                autocomplete="venue"
            />

            <InputError class="mt-2" :message="form.errors.venue"/>
        </div>

        <div class="mt-4">
            <InputLabel for="reporter" value="Reporter"/>

            <TextInput
                id="reporter"
                type="text"
                class="mt-1 block w-full"
                v-model="form.reporter"
                autocomplete="reporter"
            />

            <InputError class="mt-2" :message="form.errors.reporter"/>
        </div>

        <div class="mt-4">
            <InputLabel for="status" value="Status"/>

            <SelectInput
                id="status"
                class="mt-1 block w-full"
                v-model="form.status"
                autocomplete="status"
                :options="statusOptions"
            />

            <InputError class="mt-2" :message="form.errors.status"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Update Report
            </PrimaryButton>
        </div>
    </form>
</template>
