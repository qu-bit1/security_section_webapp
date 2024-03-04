<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DownloadReport from "@/Pages/Reports/Partials/DownloadReport.vue";
import Tag from "@/Components/Tag.vue";
import CreateRemarkForm from "@/Pages/Reports/Partials/CreateRemarkForm.vue";
import ViewRemarks from "@/Pages/Reports/Partials/ViewRemarks.vue";
import ViewComments from "@/Pages/Reports/Partials/ViewComments.vue";
import {inject} from "vue";
import Download from "@/Components/icons/Download.vue";
import Edit from "@/Components/icons/Edit.vue";
import Check from "@/Components/icons/Check.vue";

defineProps({
    report: Object,
    remarks: Object,
    comments: Object,
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

const displayFields = [
    {key: 'serial_number', label: 'Serial Number', inline: true},
    {key: 'description', label: 'Description', inline: false},
    {key: 'reporter', label: 'Reporter', inline: true},
    {key: 'status', label: 'Status', inline: true},
    {key: 'shift', label: 'Shift', inline: true},
    {key: 'venue', label: 'Venue', inline: true},
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
                <DownloadReport :key="report.id" :report="report"><Download/></DownloadReport>
                <template v-if="canApproveReports() && !report.approved">
                    <SecondaryButton class="ml-2" @click="approveReport(report.id)"><Check/></SecondaryButton>
                </template>
                <template v-if="canEditReports() && !report.approved">
                    <SecondaryButton :href="route('reports.edit', report.id)" class="ml-2"><Edit/></SecondaryButton>
                </template>
                <template v-if="canDeleteReports() && !report.approved">
                    <DeleteReportForm :key="report.id" :report="report" class="ml-2"/>
                </template>
            </div>
        </template>

        <div
            class="relative prose max-w-none lg:max-w-full xl:max-w-none prose-img:rounded-xl prose-img:w-full mx-auto prose-a:no-underline prose-a:text-rose-600 prose-table p-4 sm:p-8 bg-white">
            <template v-for="field in displayFields" :key="field.key">
                <div class="mb-1">
                    <h3 :class="getHeadingClass(field.inline)">{{ field.label }} {{ field.inline ? ':' : '' }}</h3>
                    <template v-if="field.key === 'tags'">
                        <div class="flex space-x-2">
                            <template v-for="tag in report[field.key]">
                                <Tag :value="tag.title"/>
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
            <ViewRemarks v-if="remarks.data && remarks.data.length > 0" :remarks="remarks" class="py-4"/>
            <ViewComments :comments="comments" :report="report" class="py-4"/>
        </div>
    </AuthenticatedLayout>
</template>

