<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Paginator from "@/Components/Paginator.vue";

defineProps({attachments: Object});
</script>


<template>
    <Head title="Attachments"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Attachments</h2>
                <div class="flex-1 flex justify-end">
                    <PrimaryButton :href="route('attachments.create')">
                        Upload Attachment
                    </PrimaryButton>
                </div>
            </div>
        </template>
        <div class="max-w-7xl m-auto p-6">
            <div class="grid grid-cols-4 gap-4">
                <template v-for="attachment in attachments.data">
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
            <Paginator :paginator="attachments"/>
        </div>
    </AuthenticatedLayout>
</template>
