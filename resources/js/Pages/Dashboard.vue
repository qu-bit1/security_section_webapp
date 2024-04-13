<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import {computed} from "vue";

const user = computed(() => {
    return usePage().props.auth.user;
});
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="p-6 text-gray-900">You are logged in as: <b>{{ user.name }}</b></div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
