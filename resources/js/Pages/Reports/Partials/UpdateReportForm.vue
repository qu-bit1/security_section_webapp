<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, useForm} from '@inertiajs/vue3';
import FilePicker from "@/Components/FilePicker.vue";
import {shiftOptions, statusOptions} from "@/Compositions/Constants.js";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import MultiSelect from "primevue/multiselect";
import {ref} from "vue";
import Calender from "@/Components/icons/Calender.vue";
import {DateTime} from "luxon";
import Dropdown from "primevue/dropdown";

const props = defineProps({
    report: Object,
    tags: Object,
    params : Object,
});

const tagOptions = ref(props.tags);
const selectAll = ref(false);

const shift = ref(props.report.shift);
const shiftSplit = shift.value.toString().split(',')
const form = useForm({
    type : props.params.type,
    reported_at: DateTime.fromSQL(props.report.reported_at, {zone: 'utc'}).toJSDate(),
    shift_date: DateTime.fromSQL(shiftSplit[0], {zone: 'utc'}).toJSDate(),
    shift_range: shiftSplit[1].trim(),
    dealing_officer: props.report.dealing_officer,
    description: props.report.description,
    venue: props.report.venue,
    reporter: props.report.reporter,
    tags: props.report.tags.map(tag => tag.title),
    attachments: props.report.attachments.map(attachment => attachment.id),
});

const submit = () => {
    form.put(route('reports.update', props.report.id));
};

const isNormal = () => {
    return props.params.type === statusOptions[0].value;
};

const onFilter = async (event) => {
    try {
        const response = await axios.get(route('tags.search', {search: event.value}));
        const existingTitles = new Set(tagOptions.value.map(option => option.title));

        // Add tags which don't exist in the options
        response.data.tags.forEach(tag => {
            if (!existingTitles.has(tag.title)) {
                tagOptions.value.push(tag);
            }
        });

        if (!existingTitles.has(event.value)) {
            if (response.data.tags.length === 0) {
                // Find and update existing custom option
                const customOptionIndex = tagOptions.value.findIndex(option => option.custom && !option.selected);
                if (customOptionIndex !== -1) {
                    tagOptions.value[customOptionIndex].title = event.value;
                } else {
                    // Add new custom option at the beginning
                    tagOptions.value.unshift({ title: event.value, custom: true, selected: false });
                }
            } else {
                // Remove existing custom option if it exists
                const customOptionIndex = tagOptions.value.findIndex(option => option.custom && !option.selected);
                if (customOptionIndex !== -1) {
                    tagOptions.value.splice(customOptionIndex, 1);
                }
            }
        }

        return tagOptions.value;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
function onChange(event){
    selectAll.value = event.value.length === tagOptions.value.length;

    // if the custom option is selected then flag it as selected
    tagOptions.value.forEach(option => {
        option.selected = event.value.includes(option.title);
    });
}

const onSelectAllChange = (event) => {
    form.tags.value = event.checked ? tagOptions.value.map((item) => item.value) : [];
    selectAll.value = event.checked;
};
</script>

<template>
    <Head title="Edit Report"/>

    <form @submit.prevent="submit">
        <div class="flex flex-col gap-2" v-if="!isNormal()">
            <InputLabel for="reported_at" value="Reported At"/>
            <VueDatePicker v-model="form.reported_at" format="dd/MM/yyyy HH:mm">
                <template #input-icon>
                    <Calender class="mx-3"/>
                </template>
                <template #dp-input="{ value }">
                    <InputText
                        class="w-full pl-8"
                        :value="value"
                        autocomplete="reported_at"
                        readonly
                    />
                </template>
            </VueDatePicker>
            <InputError :message="form.errors.reported_at"/>
        </div>

        <div class="flex flex-col gap-2 mt-4" v-if="isNormal()">
            <InputLabel for="shift" value="Shift" required/>
            <div class="flex items-center gap-4 flex-col md:flex-row">
                <div class="flex-auto w-full">
                    <VueDatePicker v-model="form.shift_date" format="dd/MM/yyyy" :enable-time-picker="false">
                        <template #input-icon>
                            <Calender class="mx-3"/>
                        </template>
                        <template #dp-input="{ value }">
                            <InputText
                                class="w-full pl-8"
                                :value="value"
                                placeholder="Select date"
                                autocomplete="shift_at"
                                readonly
                            />
                        </template>
                    </VueDatePicker>
                    <InputError :message="form.errors.shift_date"/>
                </div>
                <div class="hidden md:block">
                    -
                </div>
                <div class="flex-auto w-full">
                    <Dropdown
                        v-model="form.shift_range"
                        :options="shiftOptions"
                        placeholder="Select range"
                        optionLabel="label"
                        optionValue="value"
                        class="w-full"
                        required
                    />
                    <InputError :message="form.errors.shift_range"/>
                </div>
            </div>
        </div>
        <InputError :message="form.errors.type"/>

        <template v-if="!isNormal()">
            <div class="flex flex-col gap-2 mt-4">
                <InputLabel for="tags" value="Tags"/>
                <MultiSelect
                    v-model="form.tags"
                    data-key="title"
                    display="chip"
                    :options="tagOptions"
                    filter
                    optionLabel="title"
                    optionValue="title"
                    placeholder="Select tags"
                    class="w-full"
                    @filter="onFilter"
                    @selectallChange="onSelectAllChange($event)"
                    @change="onChange($event)"
                />
                <InputError :message="form.errors.tags"/>
            </div>

            <div class="flex flex-col gap-2 mt-4">
                <InputLabel for="description" value="Description"/>
                <Textarea id="description" v-model="form.description" autocomplete="description" autoResize rows="5" cols="30" />
                <InputError :message="form.errors.description"/>
            </div>

            <div class="mt-4">
                <InputLabel value="Attachments"/>
                <FilePicker v-model="form.attachments" :attachments="report.attachments" class="mt-2"/>
                <InputError :message="form.errors.attachments" class="mt-2"/>
            </div>

            <div class="flex flex-col gap-2 mt-4">
                <InputLabel for="dealing_officer" value="Dealing Officer"/>
                <InputText
                    type="text"
                    id="dealing_officer"
                    v-model="form.dealing_officer"
                    autocomplete="dealing_officer"
                />
                <InputError :message="form.errors.dealing_officer"/>
            </div>

            <div class="flex flex-col gap-2 mt-4">
                <InputLabel for="venue" value="Venue"/>
                <InputText
                    type="text"
                    id="venue"
                    v-model="form.venue"
                    autocomplete="venue"
                />
                <InputError :message="form.errors.venue"/>
            </div>
        </template>
        <div v-if="can('edit own reports | edit all reports')" :class="'sticky bg-surface-0 bottom-0 start-0 z-50 flex items-center justify-end mt-4 py-4 ' + (!isNormal() ? 'border-t border-t-surface-200':'')">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Update Report
            </PrimaryButton>
        </div>
    </form>
</template>
