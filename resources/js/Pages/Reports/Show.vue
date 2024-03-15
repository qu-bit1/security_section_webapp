<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DownloadReport from "@/Pages/Reports/Partials/DownloadReport.vue";
import CreateRemarkForm from "@/Pages/Reports/Partials/CreateRemarkForm.vue";
import ViewRemarks from "@/Pages/Reports/Partials/ViewRemarks.vue";
import ViewComments from "@/Pages/Reports/Partials/ViewComments.vue";
import {inject} from "vue";
import Download from "@/Components/icons/Download.vue";
import Edit from "@/Components/icons/Edit.vue";
import ApproveReportForm from "@/Pages/Reports/Partials/ApproveReportForm.vue";
import Tag from "primevue/tag";

defineProps({
    report: Object,
    remarks: Object,
    comments: Object,
    filters: Object,
});

const displayFields = [
    {key: 'serial_number', label: 'Serial Number', inline: true},
    {key: 'description', label: 'Description', inline: false},
    {key: 'dealing_officer', label: 'Dealing Officer', inline: true},
    {key: 'venue', label: 'Venue', inline: true},
    {key: 'reported_at', label: 'Reported At', inline: true},
    {key: 'tags', label: 'Tags', inline: false},
    {key: 'attachments', label: 'Attachments', inline: false},
];

const getHeadingClass = (inline) => {
    return inline ? 'inline-block mr-2' : '';
};

const can = inject('can');
const canEditReports = () => {
    return can('edit own reports | edit all reports');
};

const canDeleteReports = () => {
    return can('delete own reports | delete all reports');
};

const canApproveReports = () => {
    return can('approve reports');
};

const canCreateRemarks = () => {
    return can('create remarks');
};
</script>

<template>
    <Head :title="report.serial_number"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-end">
                <template v-if="canCreateRemarks()">
                    <CreateRemarkForm :key="report.id" :report="report" class="ml-2"/>
                </template>
                <DownloadReport :key="report.id" :reports="[report]"><Download/></DownloadReport>
                <template v-if="canApproveReports() && !report.approved">
                    <ApproveReportForm :reports="[report]" class="ml-2"/>
                </template>
                <template v-if="canEditReports() && !report.approved">
                    <SecondaryButton :href="route('reports.edit', report.id)" class="ml-2"><Edit/></SecondaryButton>
                </template>
                <template v-if="canDeleteReports() && !report.approved">
                    <DeleteReportForm :key="report.id" :reports="[report]" class="ml-2"/>
                </template>
            </div>
        </template>

        <div
            class="relative prose max-w-none lg:max-w-full xl:max-w-none prose-img:rounded-xl prose-img:w-full mx-auto prose-a:no-underline prose-a:text-rose-600 prose-table px-4 pb-4 sm:px-8 sm:pb-8 bg-white">
            <template v-for="field in displayFields" :key="field.key">
                <div class="mb-1">
                    <h3 :class="getHeadingClass(field.inline)">{{ field.label }} {{ field.inline ? ':' : '' }}</h3>
                    <template v-if="field.key === 'tags'">
                        <div class="flex">
                            <template v-for="tag in report[field.key]">
                                <Tag :value="tag.title" class="mr-2 mb-2"/>
                            </template>
                        </div>
                    </template>
                    <template v-else-if="field.key === 'attachments' && report[field.key].length > 0">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:md:grid-cols-4 gap-4">
                            <template v-for="attachment in report[field.key]">
                                <div class="border shadow-sm rounded-lg bg-white">
                                    <img :alt="'preview of '+attachment.name" :src="attachment.path"
                                         class="w-full h-32 object-cover"/>
                                    <div class="p-4 border-t">
                                        <h2 class="text-base font-medium text-gray-900">{{ attachment.name }}</h2>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template v-else>
                        <span>{{ report[field.key] }}</span>
                    </template>
                </div>
            </template>
            <ViewRemarks v-if="remarks.data && remarks.data.length > 0" :remarks="remarks" :report="report" :filters="filters" class="py-4"/>
            <ViewComments :comments="comments" :report="report" class="py-4" :filters="filters"/>
        </div>
    </AuthenticatedLayout>
</template>

