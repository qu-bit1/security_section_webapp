<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import Paginator from "@/Components/Paginator.vue";
import ViewTags from "@/Pages/Reports/Partials/ViewTags.vue";
import FilterReportForm from "@/Pages/Reports/Partials/FilterReportForm.vue";
import {inject, ref} from "vue";
import ViewAttachments from "@/Pages/Reports/Partials/ViewAttachments.vue";
import DownloadReport from "@/Pages/Reports/Partials/DownloadReport.vue";
import Tag from "@/Components/Tag.vue";
import {statusOptions} from "@/Compositions/Constants.js";
import Edit from "@/Components/icons/Edit.vue";
import Download from "@/Components/icons/Download.vue";
import Check from "@/Components/icons/Check.vue";
import Export from "@/Components/icons/Export.vue";
import Filter from "@/Components/icons/Filter.vue";
import formatDate from "../../Compositions/DateTime.js";

const props = defineProps({
    reports: Object,
    filters: Object,
});

let showFilters = ref(String(props.filters.showFilters).toLowerCase() === 'true');

const tableHeaders = [
    'S.NO.',
    'Shift',
    'Description',
    'Status',
    'Approved',
    'Venue',
    'Reporter',
    'Tags',
    'Attachments',
    'Created At',
    'Action',
];

const can = inject('can');
const canApproveReports = () => {
    return can('approve reports');
};

const canEditReports = () => {
    return can('edit own reports | edit all reports');
};

const canDeleteReports = () => {
    return can('delete own reports | delete all reports');
};
</script>


<template>
    <Head title="Reports"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports</h2>
                <div class="flex-1 flex justify-end">
                    <DownloadReport><Export/></DownloadReport>

                    <template v-if="canApproveReports()">
                        <SecondaryButton :href="route('reports.approve')" class="ml-2"><Check/></SecondaryButton>
                    </template>

                    <PrimaryButton @click="showFilters = !showFilters" class="ml-2"><Filter/></PrimaryButton>
                </div>
            </div>
            <div class="mt-4">
                <FilterReportForm v-model:showFilters="showFilters" :cancel="()=> (showFilters = !showFilters)" :filters="filters"/>
            </div>
        </template>

        <div class="m-auto">
            <div class="bg-skin-base overflow-hidden  sm:rounded-lg p-2">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg border bg-white">
                            <table class="min-w-full divide-y divide-gray-200 sm:rounded-lg">
                                <thead class="bg-gray-50 font-medium text-left">
                                <tr>
                                    <th v-for="(header, index) in tableHeaders" :key="index" class="px-6 py-4 uppercase tracking-wider" scope="col">
                                        {{ header }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-skin-base divide-y divide-gray-200 text-gray-600">
                                <template v-for="report in reports.data" :key="report.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.serial_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link
                                                :href="route('reports.show', report.id)"
                                            >
                                                {{ report.shift }}
                                            </Link>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report?.description?.substring(0,50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ statusOptions.find(option => option.value === report.status).label}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Tag v-if="report.approved" value="Approved" class="bg-green-100 text-green-800 border-green-400 hover:border-green-600"/>
                                            <Tag value="Pending" class="bg-yellow-100 text-yellow-800 border-yellow-400 hover:border-yellow-600" v-else/>
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
                                        <td class="px-6 py-4 whitespace-pre">
                                            {{ formatDate(report.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex flex-row">
                                            <DownloadReport :key="report.id" :report="report"><Download/></DownloadReport>

                                            <template v-if="canEditReports() && !report.approved">
                                                <SecondaryButton :href="route('reports.edit', report.id)" class="ml-2"><Edit/></SecondaryButton>
                                            </template>

                                            <template v-if="canDeleteReports() && !report.approved">
                                                <DeleteReportForm :key="report.id" :report="report" class="ml-2"/>
                                            </template>
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
