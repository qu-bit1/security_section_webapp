<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Paginator from "primevue/paginator";
import {ref} from "vue";
import Tag from "@/Components/Tag.vue";
import {perPageOptions} from "@/Compositions/Constants.js";

const props = defineProps({
    tags: Object,
    filters: Object,
});

const totalRecords = ref(props.tags.total);
const first = ref(props.tags.from - 1);
const rows = ref(props.tags.per_page);

let search = ref(props.filters.search);

const loadLazyData = (event) => {
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);

    // Append parameters to the URL
    url.searchParams.set('per_page', event?.rows || rows.value);
    url.searchParams.set('page', (event?.page??first.value)+1);
    url.searchParams.set('search', search.value??'');

    router.get(url, {}, {
        preserveState: true,
        onSuccess: (data) => {
            first.value = data.props.tags.from-1;
            totalRecords.value = data.props.tags.total;
            rows.value = data.props.tags.per_page;
        }
    });
};
const onPage = (event) => {
    loadLazyData(event);
};
</script>


<template>
    <Head title="Tags"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tags</h2>
                <div class="flex-1 flex justify-end">
                    <PrimaryButton :href="route('tags.create')" class="ml-2">
                        New Tag
                    </PrimaryButton>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex flex-row">
                    <div class="flex-1">
                        <label class="sr-only" for="search">Search</label>
                        <input
                            id="search"
                            v-model="search"
                            autocomplete="off"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            name="search"
                            placeholder="Search..."
                            type="search"
                            @keyup.enter="loadLazyData"
                        />
                    </div>
                </div>
            </div>
        </template>
        <div class="p-2 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <template v-for="tag in tags.data">
                    <div
                        class="border shadow sm:rounded-lg bg-white"
                    >
                        <div class="p-4">
                            <Tag :value="tag.title"/>
                            <div v-if="tag.description" class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ tag.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <Paginator v-model:first="first" @page="onPage($event)" :rows="rows" :totalRecords="totalRecords" :rowsPerPageOptions="perPageOptions"/>
        </div>
    </AuthenticatedLayout>
</template>
