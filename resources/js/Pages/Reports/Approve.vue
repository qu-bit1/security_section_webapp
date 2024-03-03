<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Paginator from "@/Components/Paginator.vue";
import ViewTags from "@/Pages/Reports/Partials/ViewTags.vue";
import ViewAttachments from "@/Pages/Reports/Partials/ViewAttachments.vue";
import {statusOptions} from "@/Compositions/Constants.js";
import Check from "@/Components/icons/Check.vue";
import formatDate from "../../Compositions/DateTime.js";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

const props = defineProps({
    reports: Object,
});

const form = useForm({});

const approveReport = async (reportId) => {
    if (confirm('Are you sure you want to approve this report?')) {
        form.post(route('reports.approveOne', reportId), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
            onFinish: () => form.reset(),
        });
    }
}
</script>


<template>
    <Head title="Reports"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Approve Reports</h2>
            </div>
        </template>
        <div class="m-auto">
            <DataTable showGridlines scrollable highlightOnSelect :value="reports" dataKey="id" tableStyle="min-width: 50rem">
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
                <Column field="action" header="Action">
                    <template #body="data">
                        <SecondaryButton
                            @click="approveReport(data.data.id)"
                            class="ml-2"
                            v-if="can('approve reports')"
                        >
                            <Check/>
                        </SecondaryButton>
                    </template>
                </Column>
            </DataTable>
            <Paginator :paginator="reports" class="mx-2"/>
        </div>
    </AuthenticatedLayout>
</template>
