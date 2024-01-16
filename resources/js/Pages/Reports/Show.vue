<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DownloadReport from "@/Pages/Reports/Partials/DownloadReport.vue";
import Tag from "@/Components/Tag.vue";

defineProps({
    report: Object,
});
</script>

<template>
    <Head :title="report.title"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report - {{ report.title }}</h2>
            <div class="flex-1 flex justify-end">
                <DownloadReport :key="report.id" :report="report">
                    Download
                </DownloadReport>
                <SecondaryButton :href="route('reports.edit', report.id)" class="ml-2">
                    Edit Report
                </SecondaryButton>
                <DeleteReportForm :key="report.id" :report="report" class="ml-2"/>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Details</h2>
                    <div>
                        <div class="py-4 grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700" for="title">Title</label>
                                <div class="mt-1">
                                    <input id="title" :value="report.title"
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>

