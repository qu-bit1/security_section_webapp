<script setup>
import DeleteRemarkForm from "@/Pages/Reports/Partials/DeleteRemarkForm.vue";
import UpdateRemarkForm from "@/Pages/Reports/Partials/UpdateRemarkForm.vue";
import {formatDate} from "@/Compositions/DateTime.js";
import {inject, ref} from "vue";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import {perPageOptions} from "@/Compositions/Constants.js";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    report:{
        type: Object,
    },
    remarks: {
        type: Object,
    },
    filters:{
        type: Object,
    }
})
const loading = ref(false);
const totalRecords = ref(props.remarks.total);
const first = ref(props.remarks.from);
const rows = ref(props.remarks.per_page);
const page = ref(props.filters.page??0);
const lazyParams = ref(props.filters);


const loadLazyData = (event) => {
    loading.value = true;
    lazyParams.value = {...lazyParams.value, first: event?.first || first.value};

    const sectionTop = document.getElementById('remarksPagination').getBoundingClientRect().top + window.scrollY;

    const currentUrl = window.location.href;
    const url = new URL(currentUrl);

    // Append parameters to the URL
    url.searchParams.set('lazyEvent', JSON.stringify(lazyParams.value));

    router.get(url, {}, {
        preserveState: true,
        onSuccess: (data) => {
            first.value = data.props.remarks.from;
            totalRecords.value = data.props.remarks.total;

            // After loading, scroll back to the saved section top position
            window.scrollTo(0, sectionTop);
            loading.value = false;
        }
    });
};

const can = inject('can')
const canEditRemarks = () => {
    return can('edit own remarks | edit all remarks');
};

const canDeleteRemarks = () => {
    return can('delete own remarks | delete all remarks');
};

const onPage = (event) => {
    lazyParams.value = event;
    loadLazyData(event);
};

</script>

<template>
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Remarks</h2>
        <div class="py-4 not-prose">
            <DataTable
                id="remarksPagination"
                showGridlines
                :value="remarks.data"
                dataKey="id"
                tableStyle="min-width: 50rem"
                lazy
                paginator
                :first="first"
                :rows="rows"
                :totalRecords="totalRecords"
                :rowsPerPageOptions="perPageOptions"
                :loading="loading"
                @page="onPage($event)"
            >
                <Column header="#" style="width: 3rem">
                    <template #body="data">
                        {{ data.index + 1 }}
                    </template>
                </Column>
                <Column field="body" header="Body"></Column>
                <Column field="user.name" header="Created By"></Column>
                <Column field="created_at" header="Created At">
                    <template #body="data">
                        {{ formatDate(data.data.created_at) }}
                    </template>
                </Column>
                <Column header="Action" style="width: 3rem">
                    <template #body="data">
                        <div class="flex flex-row">
                            <UpdateRemarkForm v-if="canEditRemarks()" :key="data.data.id" :remark="data.data"/>
                            <DeleteRemarkForm v-if="canDeleteRemarks()" :key="data.data.id" :remark="data.data" class="ml-2"/>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>
