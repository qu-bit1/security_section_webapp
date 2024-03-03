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
import Column from "primevue/column";
import DataTable from "primevue/datatable";

const props = defineProps({
    reports: Object,
    filters: Object,
});

let showFilters = ref(String(props.filters.showFilters).toLowerCase() === 'true');

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
            <DataTable class="border-t" showGridlines scrollable highlightOnSelect :value="reports.data" dataKey="id" tableStyle="min-width: 50rem">
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="serial_number" header="S.NO.">
                    <template #body="data">
                        <Link :href="route('reports.show', data.data.id)">
                            {{ data.data.serial_number }}
                        </Link>
                    </template>
                </Column>
                <Column field="shift" header="Shift"></Column>
                <Column field="description" header="Description">
                    <template #body="data">
                        {{ data.data?.description?.substring(0,50) }}
                    </template>
                </Column>
                <Column field="status" header="Status">
                    <template #body="data">
                        {{ statusOptions.find(option => option.value === data.data.status).label}}
                    </template>
                </Column>
                <Column field="approved" header="Approved">
                    <template #body="data">
                        <Tag v-if="data.data.approved" value="Approved" class="bg-green-100 text-green-800 border-green-400 hover:border-green-600"/>
                        <Tag value="Pending" class="bg-yellow-100 text-yellow-800 border-yellow-400 hover:border-yellow-600" v-else/>
                    </template>
                </Column>
                <Column field="venue" header="Venue"></Column>
                <Column field="reporter" header="Reporter"></Column>
                <Column field="tags" header="Tags">
                    <template #body="data">
                        <ViewTags :report="data.data"/>
                    </template>
                </Column>
                <Column field="attachments" header="Attachments">
                    <template #body="data">
                        <ViewAttachments :report="data.data"/>
                    </template>
                </Column>
                <Column field="created_at" header="Created At">
                    <template #body="data">
                        {{ formatDate(data.data.created_at) }}
                    </template>
                </Column>
                <Column field="action" header="Action" style="min-width: 200px">
                    <template #body="data">
                        <div class="flex flex-row">
                        <DownloadReport :key="data.data.id" :report="data.data"><Download/></DownloadReport>

                        <template v-if="canEditReports() && !data.data.approved">
                            <SecondaryButton :href="route('reports.edit', data.data.id)" class="ml-2"><Edit/></SecondaryButton>
                        </template>

                        <template v-if="canDeleteReports() && !data.data.approved">
                            <DeleteReportForm :key="data.data.id" :report="data.data" class="ml-2"/>
                        </template>
                        </div>
                    </template>
                </Column>
            </DataTable>
            <Paginator :paginator="reports" class="mx-2"/>
        </div>
    </AuthenticatedLayout>
</template>
