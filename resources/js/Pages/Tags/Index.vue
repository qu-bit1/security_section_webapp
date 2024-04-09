<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Paginator from "primevue/paginator";
import {ref} from "vue";
import {perPageOptions} from "@/Compositions/Constants.js";
import InputText from "primevue/inputtext";
import Card from "primevue/card";
import Tag from "primevue/tag";

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
                <div class="flex-1 flex justify-end">
                    <PrimaryButton :href="route('tags.create')" class="ml-2">
                        New Tag
                    </PrimaryButton>
                </div>
            </div>
            <div class="mt-4">
                <span class="relative">
                    <i class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 dark:text-surface-600" />
                    <InputText v-model="search" placeholder="Search" class="pl-10 w-full" @keyup.enter="loadLazyData"/>
                </span>
            </div>
        </template>
        <div class="p-2 pb-2 sm:px-6 sm:pb-6 lg:px-8 lg:pb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
                <template v-for="tag in tags.data">
                    <div class="border shadow-sm rounded-lg bg-white">
                        <div class="p-4">
                            <Tag :value="tag.title" class="mr-2"/>
                        </div>
                        <div class="p-4 border-t" v-if="tag.description">
                            <h2 class="text-base font-medium text-gray-900"> {{ tag.description }}</h2>
                        </div>
                    </div>
                </template>
            </div>
            <Paginator v-model:first="first" @page="onPage($event)" :rows="rows" :totalRecords="totalRecords" :rowsPerPageOptions="perPageOptions"/>
        </div>
    </AuthenticatedLayout>
</template>
