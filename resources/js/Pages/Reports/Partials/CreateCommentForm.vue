<script setup>
import {useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Avatar from "@/Components/Avatar.vue";
import {inject} from "vue";

const props = defineProps({
    report: {
        type: Object,
    }
})

const form = useForm({
    body: '',
});

const submit = () => {
    form.post(route('reports.comments.store', props.report.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const can = inject('can')
const canCreateComments = () => {
    return can('create comments');
};
</script>

<template>
    <section class="space-y-6">
        <template v-if="canCreateComments()">
            <div class="flex items-center space-x-4 not-prose">
                <Avatar />
                <div class="space-y-1 font-medium ">
                    <p>Add a comment</p>
                </div>
            </div>
            <div class="mt-4 mb-2">
                <form @submit.prevent="submit">
                    <div class="mb-5 mx-auto">
                        <TextInput
                            id="body"
                            v-model="form.body"
                            autocomplete="body"
                            class="mt-1 block w-full"
                            input-type="textarea"
                        />
                        <InputError :message="form.errors.body" class="mt-2"/>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-3">
                            Create Comment
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </template>
        <template v-else>
            <div class="not-prose">
                <div class="flex flex-col items-center justify-center px-8 py-8 mb-4 text-sm text-[#1f2833] leading-6 border not-prose  border-rose-200 bg-rose-50 rounded-xl">
                    <p class="text-base">You are not allowed to comment. <a href="#" class="font-black text-skin-600">learn more</a></p>
                </div>
            </div>
        </template>
        <hr/>
    </section>
</template>
