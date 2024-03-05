<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import ViewTags from "@/Pages/Reports/Partials/ViewTags.vue";
import {inject, ref} from "vue";
import ViewAttachments from "@/Pages/Reports/Partials/ViewAttachments.vue";
import DownloadReport from "@/Pages/Reports/Partials/DownloadReport.vue";
import {perPageOptions, shiftOptions, statusOptions} from "@/Compositions/Constants.js";
import Edit from "@/Components/icons/Edit.vue";
import {formatDate, truncate } from "@/Compositions/DateTime.js";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import InputText from "primevue/inputtext";
import {FilterMatchMode} from "primevue/api";
import Calendar from "primevue/calendar"
import MultiSelect from "primevue/multiselect";
import TriStateCheckbox from "primevue/tristatecheckbox";
import Filter from "@/Components/icons/Filter.vue";
import Export from "@/Components/icons/Export.vue";
import Eye from "@/Components/icons/Eye.vue";
import ApproveReportForm from "@/Pages/Reports/Partials/ApproveReportForm.vue";
import Download from "@/Components/icons/Download.vue";

const props = defineProps({
    reports: Object,
    filters: Object,
});

const loading = ref(false);
const totalRecords = ref(props.reports.total);
const selectedReports = ref();
const first = ref(props.reports.from);
const rows = ref(props.reports.per_page);
const lazyParams = ref(props.filters);
const obj = JSON.parse(props.filters.lazyEvent ?? '{}');
const filterDisplay = ref(obj.filterDisplay ?? '');
const customFilters = ref(obj.filters ?? {
    global: {value: null, matchMode: FilterMatchMode.CONTAINS},
    serial_number: {value: null, matchMode: FilterMatchMode.EQUALS},
    description: {value: null, matchMode: FilterMatchMode.CONTAINS},
    shift: {value: null, matchMode: FilterMatchMode.IN},
    status: {value: null, matchMode: FilterMatchMode.IN},
    created_at: {value: null, matchMode: FilterMatchMode.DATE_IS},
    approved: {value: null, matchMode: FilterMatchMode.EQUALS},
    venue: {value: null, matchMode: FilterMatchMode.CONTAINS},
    reporter: {value: null, matchMode: FilterMatchMode.CONTAINS},
});

// Injected Dependencies
const can = inject('can');

// Methods
const canApproveReports = () => can('approve reports');
const canEditReports = () => can('edit own reports | edit all reports');
const canDeleteReports = () => can('delete own reports | delete all reports');

const loadLazyData = (event) => {
    loading.value = true;
    lazyParams.value = {...lazyParams.value, first: event?.first || first.value, filterDisplay: filterDisplay.value};
    router.get(route('reports.index'), {lazyEvent: JSON.stringify(lazyParams.value)}, {
        preserveState: true,
        onSuccess: (data) => {
            first.value = data.props.reports.from;
            totalRecords.value = data.props.reports.total;
            loading.value = false;
        }
    });
};

const onPage = (event) => {
    lazyParams.value = event;
    loadLazyData(event);
};

const onSort = (event) => {
    lazyParams.value = event;
    loadLazyData(event);
};

const onFilter = (event) => {
    lazyParams.value.filters = customFilters.value;
    loadLazyData(event);
};

const onDisplayFilter = () => {
    filterDisplay.value = filterDisplay.value === '' ? 'row' : '';
    lazyParams.value.filterDisplay = filterDisplay.value;
};
</script>


<template>
    <Head title="Reports"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Reports</h2>
        </template>

        <div class="m-auto">
            <DataTable
                v-model:selection="selectedReports"
                sortMode="multiple"
                removableSort
                lazy
                paginator
                :first="first"
                :rows="rows"
                :totalRecords="totalRecords"
                :rowsPerPageOptions="perPageOptions"
                :loading="loading"
                @page="onPage($event)"
                @sort="onSort($event)"
                showGridlines
                scrollable
                highlightOnSelect
                :globalFilterFields="['description', 'reporter', 'venue']"
                v-model:filters="customFilters"
                @filter="onFilter($event)"
                :filterDisplay="filterDisplay"
                :value="reports.data"
                dataKey="id"
                tableStyle="min-width: 50rem"
            >
                <template #empty>
                    <div class="flex flex-col items-center justify-center">
                        No reports found
                    </div>
                </template>

                <!-- Header Template -->
                <template #header='{ event }'>
                    <div class="flex flex-col md:flex-row justify-end ">
                        <div class="flex-1 flex flex-row items-center">
                            <SecondaryButton @click.prevent="onDisplayFilter()"><Filter/></SecondaryButton>
                            <template v-if="selectedReports?.length > 0">
                                <DownloadReport :reports="selectedReports"><Export/></DownloadReport>
                                <template v-if="canApproveReports()">
                                    <ApproveReportForm :reports="selectedReports" class="ml-2"/>
                                </template>
                                <template v-if="canDeleteReports()">
                                    <DeleteReportForm :reports="selectedReports" class="ml-2"/>
                                </template>
                            </template>
                        </div>
                        <div class="flex flex-row items-center justify-end mt-2 md:mt-0">
                            <div class="relative">
                                <i class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 dark:text-surface-600" />
                                <InputText v-model="customFilters['global'].value" placeholder="Keyword Search" @keyup.enter="onFilter(event)" class="pl-10" />
                            </div>
                        </div>
                    </div>
                </template>

                <Column headerStyle="width: 3rem" selectionMode="multiple"></Column>

                <Column field="serial_number" header="S.NO." sortable>
                    <template #body="data">
                        <Link :href="route('reports.show', data.data.id)">
                            {{ data.data.serial_number }}
                        </Link>
                    </template>
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search by S.NO"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>

                <Column field="shift" filterField="shift" header="Shift">
                    <template #filter="{ filterModel, filterCallback }">
                        <MultiSelect v-model="filterModel.value" :options="shiftOptions" class="p-column-filter"
                                     optionLabel="value" placeholder="Any" @change="filterCallback()">
                            <template #option="slotProps">
                                <span>{{ slotProps.option.label }}</span>
                            </template>
                        </MultiSelect>
                    </template>
                </Column>

                <Column field="description" filterField="description" header="Description">
                    <template #body="data">
                        <div v-tooltip="data.data?.description">
                            {{ truncate(data.data.description, 10)}}
                        </div>
                    </template>
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search in description"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>

                <Column field="status" filterField="status" header="Status">
                    <template #body="data">
                        {{ statusOptions.find(option => option.value === data.data.status).label }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <MultiSelect v-model="filterModel.value" :options="statusOptions" class="p-column-filter"
                                     optionLabel="value" placeholder="Any" @change="filterCallback()">
                            <template #option="slotProps">
                                <span>{{ slotProps.option.label }}</span>
                            </template>
                        </MultiSelect>
                    </template>
                </Column>

                <Column :showFilterMenu="false" field="approved" header="Approved" sortable>
                    <template #filter="{ filterModel, filterCallback }">
                        <TriStateCheckbox v-model="filterModel.value" inputId="verified-filter"
                                          @change="filterCallback()"/>
                    </template>
                </Column>

                <Column field="venue" header="Venue" sortable>
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search by venue"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>

                <Column field="reporter" header="Reporter" sortable>
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search by reporter"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>

                <Column field="tags" header="Tags" sortable>
                    <template #body="data">
                        <ViewTags :report="data.data"/>
                    </template>
                </Column>

                <Column field="attachments" header="Attachments" sortable>
                    <template #body="data">
                        <ViewAttachments :report="data.data"/>
                    </template>
                </Column>

                <Column dataType="date" field="created_at" filterField="created_at" header="Created At" sortable>
                    <template #body="data">
                        {{ formatDate(data.data.created_at) }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <Calendar v-model="filterModel.value" dateFormat="mm/dd/yy" hourFormat="24" mask="99/99/9999"
                                  placeholder="mm/dd/yyyy" showTime @keyup.enter="filterCallback"/>
                    </template>
                </Column>

                <!-- Action Column -->
                <Column field="action" header="Action" style="min-width: 200px">
<!--                    frozen alignFrozen="right"-->
                    <template #body="data">
                        <div class="flex flex-row">
                            <SecondaryButton :href="route('reports.show', data.data.id)"><Eye/></SecondaryButton>
                            <DownloadReport :key="data.data.id" :reports="[data.data]"><Download/></DownloadReport>
                            <template v-if="canEditReports() && !data.data.approved">
                                <SecondaryButton :href="route('reports.edit', data.data.id)" class="ml-2"><Edit/></SecondaryButton>
                            </template>
                            <template v-if="canDeleteReports() && !data.data.approved">
                                <DeleteReportForm :key="data.data.id" :reports="[data.data]" class="ml-2"/>
                            </template>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AuthenticatedLayout>
</template>
