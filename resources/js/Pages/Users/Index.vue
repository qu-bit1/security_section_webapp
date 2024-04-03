<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DeleteRoleForm from "@/Pages/Roles/Partials/DeleteRoleForm.vue";
import Edit from "@/Components/icons/Edit.vue";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Tag from "primevue/tag";
import InputText from "primevue/inputtext";
import Export from "@/Components/icons/Export.vue";
import Filter from "@/Components/icons/Filter.vue";
import ApproveReportForm from "@/Pages/Reports/Partials/ApproveReportForm.vue";
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import DownloadReport from "@/Pages/Reports/Partials/DownloadReport.vue";
import {ref} from "vue";
import {FilterMatchMode} from "primevue/api";
import {perPageOptions} from "@/Compositions/Constants.js";
import MultiSelect from "primevue/multiselect";
import Setting from "@/Components/icons/Setting.vue";
import AssignRoleForm from "@/Pages/Users/Partials/AssignRoleForm.vue";

const props = defineProps({
    users: Object,
    filters: Object,
    roles: {
        type: Array
    }
});

const roleOptions = ref(props.roles);
const loading = ref(false);
const totalRecords = ref(props.users.total);
const first = ref(props.users.from);
const rows = ref(props.users.per_page);
const lazyParams = ref(props.filters);
const obj = JSON.parse(props.filters.lazyEvent ?? '{}');
const filterDisplay = ref(obj.filterDisplay ?? '');
const customFilters = ref(obj.filters ?? {
    global: {value: null, matchMode: FilterMatchMode.CONTAINS},
    name: {value: null, matchMode: FilterMatchMode.EQUALS},
    email: {value: null, matchMode: FilterMatchMode.CONTAINS},
    roles: {value: null, matchMode: FilterMatchMode.IN},
    created_at: {value: null, matchMode: FilterMatchMode.DATE_IS},
});

const loadLazyData = (event) => {
    loading.value = true;
    lazyParams.value = {...lazyParams.value, first: event?.first || first.value, filterDisplay: filterDisplay.value};
    router.get(route('users.index'), {lazyEvent: JSON.stringify(lazyParams.value)}, {
        preserveState: true,
        onSuccess: (data) => {
            first.value = data.props.users.from;
            totalRecords.value = data.props.users.total;
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
    <Head title="Users"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
            </div>
        </template>

        <div class="py-4 not-prose">
            <DataTable
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
                :value="users.data"
                dataKey="id"
                tableStyle="min-width: 30rem"
            >
                <template #empty>
                    <div class="flex flex-col items-center justify-center">
                        No users found
                    </div>
                </template>

                <!-- Header Template -->
                <template #header='{ event }'>
                    <div class="flex flex-col md:flex-row justify-end ">
                        <div class="flex-1 flex flex-row items-center">
                            <SecondaryButton @click.prevent="onDisplayFilter()"><Filter/></SecondaryButton>
                        </div>
                        <div class="flex flex-row items-center justify-end mt-2 md:mt-0">
                            <div class="relative">
                                <i class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 dark:text-surface-600" />
                                <InputText v-model="customFilters['global'].value" placeholder="Keyword Search" @keyup.enter="onFilter(event)" class="pl-10" />
                            </div>
                        </div>
                    </div>
                </template>
                <Column header="#" style="width: 3rem">
                    <template #body="data">
                        {{ data.index + 1 }}
                    </template>
                </Column>
                <Column field="name" header="Name" filterField="name" :sortable="true">
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search in name"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>
                <Column field="email" header="Email" filterField="email" :sortable="true">
                    <template #filter="{filterModel, filterCallback}">
                        <InputText v-model="filterModel.value" class="p-column-filter" placeholder="Search in email"
                                   type="text" @keyup.enter="filterCallback"/>
                    </template>
                </Column>
                <Column field="roles" header="Roles" filterField="roles" :sortable="true" :showFilterMenu="false">
                    <template #body="data">
                        <template v-for="role in data.data.roles">
                            <Tag :value="role.name" class="mr-2 mb-2"/>
                        </template>
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <MultiSelect
                             v-model="filterModel.value"
                             data-key="name"
                             display="chip"
                             :options="roleOptions"
                             filter
                             optionLabel="name"
                             optionValue="name"
                             placeholder="Any"
                             @change="filterCallback()"
                        >
                            <template #option="slotProps">
                                <Tag :value="slotProps.option.name"/>
                            </template>
                        </MultiSelect>
                    </template>
                </Column>
                <Column header="Action" style="width: 3rem">
                    <template #body="data">
                        <div class="flex flex-row">
                            <AssignRoleForm :user="data.data" :roles="roles"/>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AuthenticatedLayout>
</template>
