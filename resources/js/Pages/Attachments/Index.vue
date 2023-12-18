<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Paginator from "@/Components/Paginator.vue";
import {ref, watch} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    attachments: Object,
    filters: Object,
});

let search = ref(props.filters.search);
// let showFilters = ref(props.filters.search !== null);

watch(search, (value) => {
    router.get(route('attachments.index'),{search: value}, {
        preserveState: true,
    });
});
</script>


<template>
    <Head title="Attachments"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Attachments</h2>
                <div class="flex-1 flex justify-end">
<!--                    <SecondaryButton @click="showFilters = !showFilters">-->
<!--                        Filter-->
<!--                    </SecondaryButton>-->
                    <PrimaryButton :href="route('attachments.create')" class="ml-2">
                        Upload Attachment
                    </PrimaryButton>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex flex-row">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Search</label>
                        <input
                            v-model="search"
                            id="search"
                            name="search"
                            type="search"
                            autocomplete="off"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Search..."
                        />
                    </div>
                </div>
            </div>
        </template>
        <div class="max-w-7xl m-auto p-2 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <template v-for="attachment in attachments.data">
                    <div
                        class="border shadow sm:rounded-lg bg-white"
                    >
                        <img :src="attachment.path" class="w-full h-32 object-cover" :alt="'preview of '+attachment.name"/>
                        <div class="p-4 border-t">
                            <div class="flex justify-between">
                                <div class="flex-1">
                                    <h2 class="text-base font-medium text-gray-900">{{ attachment.name }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <Paginator :paginator="attachments"/>
        </div>
    </AuthenticatedLayout>
</template>
