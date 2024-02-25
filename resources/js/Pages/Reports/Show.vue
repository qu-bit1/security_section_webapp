<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DownloadReport from "@/Pages/Reports/Partials/DownloadReport.vue";
import Tag from "@/Components/Tag.vue";
import CreateRemarkForm from "@/Pages/Reports/Partials/CreateRemarkForm.vue";
import DeleteRemarkForm from "@/Pages/Reports/Partials/DeleteRemarkForm.vue";
import UpdateRemarkForm from "@/Pages/Reports/Partials/UpdateRemarkForm.vue";

defineProps({
    report: Object,
    remarks: Object,
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
    <Head :title="report.serial_number"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report - {{ report.serial_number }}</h2>
                <div v-if="report.approved" class="ml-2">
                    <Tag value="Approved" class="bg-green-100 text-green-800 border-green-400 hover:border-green-600"/>
                </div>
                <div class="flex-1 flex justify-end">
                    <CreateRemarkForm
                        :key="report.id" :report="report" class="ml-2"
                        v-if="can('create remarks')"
                    />
                    <DownloadReport :key="report.id" :report="report">
                        Download
                    </DownloadReport>
                    <SecondaryButton
                        @click="approveReport(report.id)"
                        class="ml-2"
                        v-if="can('approve reports') && !report.approved"
                    >
                        Approve
                    </SecondaryButton>
                    <SecondaryButton
                        :href="route('reports.edit', report.id)"
                        class="ml-2"
                        v-if="can('edit own reports | edit all reports') && !report.approved"
                    >
                        Edit Report
                    </SecondaryButton>
                    <DeleteReportForm
                        v-if="can('delete own reports | delete all reports') && !report.approved"
                        :key="report.id" :report="report" class="ml-2"/>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Details</h2>
                    <div>
                        <div class="py-4 grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700" for="title">Shift</label>
                                <div class="mt-1">
                                    <input id="title" :value="report.serial_number"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           name="title"
                                           readonly
                                           type="text">
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700"
                                       for="description">Description</label>
                                <div class="mt-1">
                                    <textarea id="description"
                                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                              name="description"
                                              readonly
                                              rows="3">{{ report.description }}</textarea>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700" for="status">Status</label>
                                <div class="mt-1">
                                    <input id="status" :value="report.status"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           name="status"
                                           readonly
                                           type="text">
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700" for="venue">Venue</label>
                                <div class="mt-1">
                                    <input id="venue" :value="report.venue"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           name="venue"
                                           readonly
                                           type="text">
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700" for="reporter">Reporter</label>
                                <div class="mt-1">
                                    <input id="reporter" :value="report.reporter"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           name="reporter"
                                           readonly
                                           type="text">
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700" for="reporter">Tags</label>
                                <div class="mt-1">
                                    <template v-for="tag in report.tags">
                                        <Tag :value="tag.title" />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="report.attachments.length>0" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Attachments</h2>
                    <div class="py-4 grid grid-cols-4 gap-4">
                        <template v-for="attachment in report.attachments">
                            <div
                                class="border shadow sm:rounded-lg bg-white"
                            >
                                <img :alt="'preview of '+attachment.name" :src="attachment.path"
                                     class="w-full h-32 object-cover"/>
                                <div class="p-4 border-t">
                                    <div class="flex justify-between">
                                        <div class="flex-1">
                                            <h2 class="text-base font-medium text-gray-900">{{ attachment.name }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" v-if="remarks.length>0">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Remarks</h2>
                    <div class="py-4">
                        <div class="bg-skin-base overflow-hidden  sm:rounded-lg">
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
                                                        Description
                                                    </th>
                                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                                        Created By
                                                    </th>
                                                    <th class="px-6 py-4 uppercase tracking-wider" scope="col">
                                                        Created At
                                                    </th>
                                                    <th class="relative px-6 py-4" scope="col">
                                                        <span class="sr-only">Edit</span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody class="bg-skin-base divide-y divide-gray-200 text-gray-600">
                                                <template v-for="(remark, index) in remarks" :key="remark.id">
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            {{ index+1 }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            {{ remark?.description?.substring(0,50) }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            {{ remark.user.name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            {{ remark.created_at }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex flex-row">
                                                            <UpdateRemarkForm
                                                               v-if="can('edit own remarks | edit all remarks')"
                                                               :key="remark.id" :remark="remark" class="ml-2"
                                                               />

                                                            <DeleteRemarkForm
                                                                v-if="can('delete own remarks | delete all remarks')"
                                                                :key="remark.id" :remark="remark" class="ml-2"/>
                                                        </td>
                                                    </tr>
                                                </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

