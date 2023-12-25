<script setup xmlns="http://www.w3.org/1999/html">
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    cancel: {
        type: Function,
    },
    filters: Object,
})

const searchParams = ref({
    search: props.filters.search,
    venue: props.filters.venue,
    reporter: props.filters.reporter,
    tags: props.filters.tags ?? [],
    status: props.filters.status,
    attachment: props.filters.attachment,
    showFilters: true,
});

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

const submitFilter = () => {
    const cleanedSearchParams = {};
    Object.keys(searchParams.value).forEach(key => {
        if (searchParams.value[key] !== null && searchParams.value[key] !== '' && !(Array.isArray(searchParams.value[key]) && searchParams.value[key].length === 0)) {
            cleanedSearchParams[key] = searchParams.value[key];
        }
    });

    router.get(route('reports.index'), cleanedSearchParams, {
        preserveState: true,
    });
};

</script>


<template>
    <div class="flex flex-row">
        <div class="flex-1">
            <div class="mb-4">
                <InputLabel for="search">Search</InputLabel>
                <TextInput
                    id="search"
                    v-model="searchParams.search"
                    autocomplete="off"
                    class="w-full sm:text-sm mt-1"
                    name="search"
                    placeholder="Search..."
                    type="search"
                />
            </div>
            <div class="flex flex-col w-full md:flex-row">
                <div class="flex-1 mb-4">
                    <InputLabel for="search">Venue</InputLabel>
                    <TextInput
                        id="search"
                        v-model="searchParams.venue"
                        autocomplete="off"
                        class="w-full sm:text-sm mt-1"
                        name="search"
                        type="search"
                    />
                </div>
                <div class="flex-1 mb-4 md:ml-4">
                    <InputLabel for="search">Reporter</InputLabel>
                    <TextInput
                        id="search"
                        v-model="searchParams.reporter"
                        autocomplete="off"
                        class="w-full sm:text-sm mt-1"
                        name="search"
                        type="search"
                    />
                </div>
            </div>
            <div class="flex flex-col w-full md:flex-row">
                <div class="flex-1 mb-4">
                    <InputLabel for="search">Attachment</InputLabel>
                    <TextInput
                        id="search"
                        v-model="searchParams.attachment"
                        autocomplete="off"
                        class="w-full sm:text-sm mt-1"
                        name="attachment"
                        type="text"
                    />
                </div>
                <div class="flex-1 mb-4 md:ml-4">
                    <InputLabel for="search">Status</InputLabel>
                    <SelectInput
                        id="search"
                        v-model="searchParams.status"
                        :options="statusOptions"
                        autocomplete="off"
                        class="w-full sm:text-sm mt-1"
                        name="search"
                    />
                </div>
            </div>
            <div class="flex-1 mb-4">
                <InputLabel for="search">Tags</InputLabel>
                <MultiSelectInput
                    id="tags"
                    v-model="searchParams.tags"
                    :search-function="searchTags"
                    autocomplete="off"
                    class="w-full sm:text-sm mt-1"
                />
            </div>
            <div class="flex items-center justify-end mt-4">
                <SecondaryButton @click="cancel">
                    Cancel
                </SecondaryButton>
                <PrimaryButton class="ms-4" @click="submitFilter">
                    Search
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>
