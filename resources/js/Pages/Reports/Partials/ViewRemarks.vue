<script setup>
import DeleteRemarkForm from "@/Pages/Reports/Partials/DeleteRemarkForm.vue";
import UpdateRemarkForm from "@/Pages/Reports/Partials/UpdateRemarkForm.vue";
import Paginator from "@/Components/Paginator.vue";
import formatDate from "../../../Compositions/DateTime.js";
import {inject} from "vue";
import Column from "primevue/column";
import DataTable from "primevue/datatable";

const props = defineProps({
    remarks: {
        type: Object,
    }
})

const can = inject('can')
const canEditRemarks = () => {
    return can('edit own remarks | edit all remarks');
};

const canDeleteRemarks = () => {
    return can('delete own remarks | delete all remarks');
};
</script>

<template>
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Remarks</h2>
        <div class="py-4 not-prose">
            <DataTable showGridlines :value="remarks.data" dataKey="id" tableStyle="min-width: 50rem" class="border-t border-l">
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
            <Paginator :paginator="remarks" per-page-key="per_page_remarks"/>
        </div>
    </div>
</template>
