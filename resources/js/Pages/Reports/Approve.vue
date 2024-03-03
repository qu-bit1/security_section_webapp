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

const tableHeaders = [
    'S.NO.',
    'Shift',
    'Description',
    'Status',
    'Venue',
    'Reporter',
    'Tags',
    'Attachments',
    'Created At',
    'Action',
];
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
                                <template v-for="report in reports" :key="report.id">
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
                                            <SecondaryButton
                                                @click="approveReport(report.id)"
                                                class="ml-2"
                                                v-if="can('approve reports')"
                                            >
                                               <Check/>
                                            </SecondaryButton>
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
