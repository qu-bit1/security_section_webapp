<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Paginator from "@/Components/Paginator.vue";
import {ref, watch} from "vue";

const props = defineProps({
    tags: Object,
    filters: Object,
});

let search = ref(props.filters.search);

watch(search, (value) => {
    router.get(route('tags.index'), {search: value}, {
        preserveState: true,
    });
});
</script>


<template>
    <Head title="Tags"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tags</h2>
                <div class="flex-1 flex justify-end">
                    <!--                    <SecondaryButton @click="showFilters = !showFilters">-->
                    <!--                        Filter-->
                    <!--                    </SecondaryButton>-->
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
                        />
                    </div>
                </div>
            </div>
        </template>
        <div class="max-w-7xl m-auto p-2 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <template v-for="tag in tags.data">
                    <div
                        class="border shadow sm:rounded-lg bg-white"
                    >
                        <div class="p-4">
                            <span
                                class="inline-flex items-center py-1 px-2 mx-1 text-xs leading-4 font-bold tracking-wide bg-rose-100 text-rose-600 border border-rose-400 rounded-full shadow-sm"
                            >
                                {{ tag.title }}
                            </span>
                            <div v-if="tag.description" class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ tag.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <Paginator :paginator="tags"/>
        </div>
    </AuthenticatedLayout>
</template>
