<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import {inject, ref} from "vue";
import ViewAttachments from "@/Pages/Reports/Partials/ViewAttachments.vue";
import DownloadReport from "@/Pages/Reports/Partials/DownloadReport.vue";
import {perPageOptions} from "@/Compositions/Constants.js";
import Edit from "@/Components/icons/Edit.vue";
import {formatDate, truncate } from "@/Compositions/DateTime.js";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import InputText from "primevue/inputtext";
import {FilterMatchMode} from "primevue/api";
import MultiSelect from "primevue/multiselect";
import Filter from "@/Components/icons/Filter.vue";
import Export from "@/Components/icons/Export.vue";
import ApproveReportForm from "@/Pages/Reports/Partials/ApproveReportForm.vue";
import Tag from "primevue/tag";
import Comment from "@/Components/icons/Comment.vue";
import TriStateCheckbox from "primevue/tristatecheckbox";
import Calender from "@/Components/icons/Calender.vue";
import {attachmentPath} from "@/Compositions/Helpers.js";
import {DateTime} from "luxon";

const props = defineProps({
    reports: Object,
    filters: Object,
    tags: Object,
});

const tagOptions = ref(props.tags);
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
    tags: {value: null, matchMode: FilterMatchMode.IN},
    created_at: {value: null, matchMode: FilterMatchMode.DATE_IS},
    approved: {value: null, matchMode: FilterMatchMode.EQUALS},
    venue: {value: null, matchMode: FilterMatchMode.CONTAINS},
    reporter: {value: null, matchMode: FilterMatchMode.CONTAINS},
});
const expandedRows = ref({});

const onRowExpand = (event) => {
    // only one row can be expanded at a time
    if(Object.keys(expandedRows.value).length > 0) {
        expandedRows.value = {};
        expandedRows.value[event.data.id] = true;
    }
};

const onRowCollapse = (event) => {
    expandedRows.value = {};
};

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

const onTagFilter = async (event) => {
    try {
        const response = await axios.get(route('tags.search', {search: event.value}));
        const existingTitles = new Set(tagOptions.value.map(option => option.title));

        // Add tags which don't exist in the options
        response.data.tags.forEach(tag => {
            if (!existingTitles.has(tag.title)) {
                tagOptions.value.push(tag);
            }
        });

        return tagOptions.value;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
</script>


<template>
    <Head title="Reports"/>
    <AuthenticatedLayout>
        <div class="m-auto">
            <DataTable
                v-model:expandedRows="expandedRows"
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
                tableStyle="min-width: 30rem"
                @rowExpand="onRowExpand"
                @rowCollapse="onRowCollapse"
            >
                <template #empty>
                    <div class="flex flex-col items-center justify-center">
                        No reports found
                    </div>
                </template>
                <Column expander/>

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
                        <Link :href="route('reports.show', data.data.id)" class="whitespace-nowrap">
                            {{ data.data.serial_number }}
                            <template v-if="data.data.approved">
                                <div
                                    v-tooltip="'approved'"
                                    class="inline-flex items-center text-emerald-500 font-bold text-base tracking-widest ml-1">
                                    <i class="pi pi-check"/>
                                </div>
                            </template>
                            <template v-else>
                                <div
                                    v-tooltip="'pending'"
                                    class="inline-flex items-center text-amber-500 font-bold text-base tracking-widest ml-1">
                                    <i class="pi pi-history"/>
                                </div>
                            </template>
                        </Link>
                    </template>
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search by S.NO"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>

                <Column field="description" filterField="description" header="Description">
                    <template #body="data">
                        <div class="flex flex-row gap-2">
                            <div v-tooltip="data.data?.description" v-if="data.data.description" class="mb-2 block">
                                <Link :href="route('reports.show', data.data.id)">
                                    {{ truncate(data.data.description, 9)}}
                                </Link>
                            </div>
                            <div class="flex flex-row justify-end gap-4 flex-1">
                                <div
                                    class="inline-flex items-center"
                                >
                                    <ViewAttachments :report="data.data"/>
                                </div>
                                <div class="inline-flex items-center">
                                    <Link :href="route('reports.show', data.data.id)+'#commentSection'" class="whitespace-nowrap">
                                        {{ data.data.comments_count }} <Comment class="ml-1"/>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search in description"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>

                <Column field="venue" header="Venue" sortable>
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search by venue"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>

                <Column field="tags" header="Tags" filterField="tags" sortable :showFilterMenu="false">
                    <template #body="data">
                        <template v-for="tag in data.data.tags">
                            <Tag :value="tag.title" class="mr-2 mb-2"/>
                        </template>
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <MultiSelect v-model="filterModel.value"
                                     data-key="title"
                                     display="chip"
                                     :options="tagOptions"
                                     filter
                                     optionLabel="title"
                                     optionValue="title"
                                     placeholder="Any"
                                     @filter="onTagFilter"
                                     @change="filterCallback()"
                        >
                            <template #option="slotProps">
                                <Tag :value="slotProps.option.title"/>
                            </template>
                        </MultiSelect>
                    </template>
                </Column>

                <Column dataType="date" field="created_at" filterField="created_at" header="Created At" sortable>
                    <template #body="data">
                        {{ formatDate(data.data.created_at) }}
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <VueDatePicker v-model="filterModel.value" @update:model-value="filterCallback">
                            <template #input-icon>
                                <Calender class="mx-3"/>
                            </template>
                            <template #dp-input="{ value }">
                                <InputText
                                    class="w-full pl-8 max-w-full min-w-max"
                                    :value="value"
                                    autocomplete="reported_at"
                                    readonly
                                />
                            </template>
                        </VueDatePicker>
                    </template>
                </Column>

                <!-- Action Column -->
                <Column field="action" header="Action" filterField="approved" :showFilterMenu="false" style="min-width: 150px">
<!--                    frozen alignFrozen="right"-->
                    <template #body="data">
                        <div class="flex flex-col justify-start items-start">
                            <template v-if="canEditReports() && !data.data.approved">
                                <SecondaryButton :href="route('reports.edit', data.data.id)" class="mb-2"><Edit/></SecondaryButton>
                            </template>
                        </div>
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <TriStateCheckbox v-model="filterModel.value" inputId="verified-filter"
                                          @change="filterCallback()"/>
                    </template>
                </Column>
                <template #expansion="slotProps">
                    <div class="p-3">
                        <div class="mb-3">
                            <h3 class="text-xl font-bold mb-2">Description:</h3>
                            <span>{{ slotProps.data.description ?? "NA" }}</span>
                        </div>
                        <div class="mb-3">
                            <h3 class="inline-block mr-2 text-xl font-bold">Reported At:</h3>
                            <span>{{ DateTime.fromSQL(slotProps.data.reported_at, {zone: 'utc'}).toJSDate().toLocaleString() }}</span>
                        </div>
                        <div class="mb-3">
                            <h3 class="text-xl font-bold mb-2">Attachments: </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:md:grid-cols-4 gap-4"  v-if="slotProps.data.attachments.length > 0">
                                <template v-for="attachment in slotProps.data.attachments">
                                    <div class="border shadow-sm rounded-lg bg-white">
                                        <object :data="attachmentPath(attachment.path)" class="w-full m-0 not-prose rounded-t-lg object-cover" width="100%" height="180px">
                                            <div class="p-4">preview not available</div>
                                        </object>
                                        <div class="p-4 border-t">
                                            <h2 class="text-base font-medium text-gray-900">{{ attachment.name }}</h2>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div v-else>
                                <span>NA</span>
                            </div>
                        </div>
                    </div>
                </template>
            </DataTable>
        </div>
    </AuthenticatedLayout>
</template>
