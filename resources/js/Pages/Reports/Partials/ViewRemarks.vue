<script setup>
import DeleteRemarkForm from "@/Pages/Reports/Partials/DeleteRemarkForm.vue";
import UpdateRemarkForm from "@/Pages/Reports/Partials/UpdateRemarkForm.vue";
import Paginator from "@/Components/Paginator.vue";
import formatDate from "../../../Compositions/DateTime.js";
import {inject} from "vue";

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

const tableHeaders = [
    '#',
    'Body',
    'Created By',
    'Created At',
    'Action',
];
</script>

<template>
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Remarks</h2>
        <div class="py-4">
            <div class="bg-skin-base overflow-hidden sm:rounded-lg">
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
                                <template v-for="(remark, index) in remarks.data" :key="remark.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ remark.body }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ remark.user.name }}</td>
                                        <td class="px-6 py-4 whitespace-pre">{{ formatDate(remark.created_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex flex-row">
                                            <UpdateRemarkForm v-if="canEditRemarks()" :key="remark.id" :remark="remark" class="ml-2" />
                                            <DeleteRemarkForm v-if="canDeleteRemarks()" :key="remark.id" :remark="remark" class="ml-2" />
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <Paginator :paginator="remarks" per-page-key="per_page_remarks"/>
        </div>
    </div>
</template>
