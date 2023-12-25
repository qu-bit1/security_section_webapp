<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import Paginator from "@/Components/Paginator.vue";
import ViewTags from "@/Pages/Reports/Partials/ViewTags.vue";
import FilterReportForm from "@/Pages/Reports/Partials/FilterReportForm.vue";
import {ref} from "vue";
import ViewAttachments from "@/Pages/Reports/Partials/ViewAttachments.vue";

const props = defineProps({
    reports: Object,
    filters: Object,
});
let showFilters = ref(props.filters.showFilters);
</script>


<template>
    <Head title="Reports"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports</h2>
                <div class="flex-1 flex justify-end">
                    <PrimaryButton @click="showFilters = !showFilters">
                        Filter
                    </PrimaryButton>
                </div>
            </div>
            <div v-if="showFilters" class="mt-4">
                <FilterReportForm :cancel="()=> (showFilters = !showFilters)" :filters="filters"/>
            </div>
        </template>
        <div class="max-w-7xl m-auto">
            <div class="bg-skin-base overflow-hidden  sm:rounded-lg p-2">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg border bg-white">
                            <table class="min-w-full divide-y divide-gray-200 sm:rounded-lg">
                                <thead class="bg-gray-50 font-medium text-left">
                                <tr>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        #
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        title
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Description
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Status
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Venue
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Reporter
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Tags
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Attachments
                                    </th>
                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        Created At
                                    </th>

                                    <!--                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">-->
                                    <!--                                        color</th>-->
                                    <th class="relative px-6 py-4" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-skin-base divide-y divide-gray-200 text-gray-600">
                                <template v-for="report in reports.data" :key="report.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link
                                                :href="route('reports.show', report.id)"
                                            >
                                                {{ report.title }}
                                            </Link>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.status }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.venue }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.reporter }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <ViewTags :report="report"/>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <ViewAttachments :report="report"/>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.created_at }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex flex-row">
                                            <SecondaryButton
                                                :href="route('reports.edit', report.id)"
                                            >
                                                Edit
                                            </SecondaryButton>
                                            <DeleteReportForm :key="report.id" :report="report" class="ml-2"/>
                                        </td>

                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <Paginator :paginator="reports"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
