<script setup>
import CreateCommentForm from "@/Pages/Reports/Partials/CreateCommentForm.vue";
import Avatar from "@/Components/Avatar.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DeleteCommentForm from "@/Pages/Reports/Partials/DeleteCommentForm.vue";
import UpdateCommentForm from "@/Pages/Reports/Partials/UpdateCommentForm.vue";
import {formatDate} from "@/Compositions/DateTime.js";
import {inject, ref} from "vue";
import KebabHorizontal from "@/Components/icons/KebabHorizontal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Paginator from "primevue/paginator";
import {router} from "@inertiajs/vue3";
import {perPageOptions} from "@/Compositions/Constants.js";

const props = defineProps({
    report: {
        type: Object,
    },
    comments: {
        type: Object,
    },
    filters:{
        type: Object,
    }
});
const totalRecords = ref(props.comments.total);
const first = ref(props.comments.from - 1);
const rows = ref(props.comments.per_page);

const can = inject('can')
const showDropdown = () => {
    return can('edit own comments | edit all comments | delete own comments | delete all comments');
};

const canEditComment = () => {
    return can('edit own comments | edit all comments');
};

const canDeleteComment = () => {
    return can('delete own comments | delete all comments');
};

const loadLazyData = (event) => {
    const sectionTop = document.getElementById('commentSection').getBoundingClientRect().top + window.scrollY;

    const currentUrl = window.location.href;
    const url = new URL(currentUrl);

    // Append parameters to the URL
    url.searchParams.set('per_page', event?.rows || rows.value);
    url.searchParams.set('page', (event?.page??first.value)+1);

    router.get(url, {}, {
        preserveState: true,
        onSuccess: (data) => {
            first.value = data.props.comments.from-1;
            totalRecords.value = data.props.comments.total;
            rows.value = data.props.comments.per_page;

            // After loading, scroll back to the saved section top position
            window.scrollTo(0, sectionTop);
            // loading.value = false;
        }
    });
};

const onPage = (event) => {
    loadLazyData(event);
};
</script>

<template>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Comments ({{comments.total}})</h2>
    <div class="py-4" id="commentSection">
        <CreateCommentForm :report="report"/>

        <div class="mt-4 space-y-4">
            <div
                v-for="comment in comments.data"
                :key="comment.id"
                class="p-4 border border-gray-200 rounded-lg shadow-sm group"
            >
                <!-- Comment header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <Avatar :user="comment.user" />
                        <div class="font-medium">
                            <span>{{ comment.user.name }}</span>
                            <div class="text-sm text-gray-500">{{ formatDate(comment.created_at, true) }}</div>
                        </div>
                    </div>
                    <!-- Dropdown for edit and delete options -->
                    <div v-if="showDropdown()" class="hidden group-hover:flex">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <SecondaryButton class=""><KebabHorizontal /></SecondaryButton>
                            </template>
                            <template #content>
                                <UpdateCommentForm v-if="canEditComment()" :comment="comment" />
                                <DeleteCommentForm v-if="canDeleteComment()" :comment="comment" />
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- Comment body -->
                <div class="mt-2 prose max-w-none sm:max-w-full prose-img:rounded-xl prose-a:text-rose-600">{{ comment.body }}</div>
            </div>
            <!-- Paginator for comment pagination -->
            <Paginator v-model:first="first" @page="onPage($event)" :rows="rows" :totalRecords="totalRecords" :rowsPerPageOptions="perPageOptions"/>
        </div>
    </div>
</template>
