<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    report: Object,
});
</script>

<template>
    <Head :title="report.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report - {{ report.title}}</h2>
            <div class="flex-1 flex justify-end">
                <SecondaryButton :href="route('reports.edit', report.id)">
                    Edit Report
                </SecondaryButton>
                <DeleteReportForm class="ml-2" :report="report" :key="report.id"/>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Details</h2>
                    <div>
                        <div class="py-4 grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <div class="mt-1">
                                    <input type="text" name="title" id="title" :value="report.title"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           readonly>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <div class="mt-1">
                                    <textarea id="description" name="description" rows="3"
                                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                              readonly>{{ report.description }}</textarea>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1">
                                    <input type="text" name="status" id="status" :value="report.status"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           readonly>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
                                <div class="mt-1">
                                    <input type="text" name="venue" id="venue" :value="report.venue"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           readonly>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label for="reporter" class="block text-sm font-medium text-gray-700">Reporter</label>
                                <div class="mt-1">
                                    <input type="text" name="reporter" id="reporter" :value="report.reporter"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                             readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" v-if="report.attachments.length>0">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Attachments</h2>
                    <div class="py-4 grid grid-cols-4 gap-4">
                        <template v-for="attachment in report.attachments">
                            <div
                                class="border shadow sm:rounded-lg bg-white"
                            >
                                <img :src="attachment.path" class="w-full h-32 object-cover" :alt="'preview of '+attachment.name"/>
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

