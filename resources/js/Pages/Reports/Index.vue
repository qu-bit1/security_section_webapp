<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DeleteReportForm from "@/Pages/Reports/Partials/DeleteReportForm.vue";

defineProps({reports: Object});
</script>


<template>
    <Head title="Reports"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports</h2>
                <div class="flex-1 flex justify-end">
                    <PrimaryButton :href="route('reports.create')">
                        Filter
                    </PrimaryButton>
                </div>
            </div>
        </template>
        <div class="max-w-7xl m-auto">
            <div class="bg-skin-base overflow-hidden  sm:rounded-lg p-2">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg border bg-white">
                            <table class="min-w-full divide-y divide-gray-200 sm:rounded-lg">
                                <thead class="bg-gray-50 font-medium text-left">
                                <tr>
                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">
                                        title
                                    </th>
                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">
                                        Venue
                                    </th>
                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">
                                        Reporter
                                    </th>
                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">
                                        Attachments
                                    </th>

                                    <!--                                    <th scope="col" class="px-6 py-4 uppercase tracking-wider">-->
                                    <!--                                        color</th>-->
                                    <th scope="col" class="relative px-6 py-4">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-skin-base divide-y divide-gray-200 text-gray-600">
                                <template v-for="report in reports" :key="report.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Link
                                                :href="route('reports.show', report.id)"
                                            >
                                                {{ report.title }}
                                            </Link>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.status }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.venue }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.reporter }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ report.attachments.length }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex flex-row">
                                            <SecondaryButton
                                                :href="route('reports.edit', report.id)"
                                            >
                                                Edit
                                            </SecondaryButton>
                                            <DeleteReportForm class="ml-2" :report="report" :key="report.id"/>
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
    </AuthenticatedLayout>
</template>
