<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import Panel from "primevue/panel";
import InputText from "primevue/inputtext";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <Panel header="Profile Information" toggleable>
            <template #header>
                <div class="flex flex-col justify-start">
                    <h2 class="text-lg font-medium">Update Password</h2>
                    <p class="mt-1 text-sm">
                        Ensure your account is using a long, random password to stay secure.
                    </p>
                </div>
            </template>
            <form class="space-y-6" @submit.prevent="updatePassword">
                <div class="flex flex-col gap-2">
                    <InputLabel for="current_password" value="Current Password"/>
                    <InputText id="current_password" ref="currentPasswordInput" type="password" autocomplete="current-password" v-model="form.current_password" aria-describedby="current_password" required />
                    <InputError :message="form.errors.current_password"/>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <InputLabel for="password" value="New Password"/>
                    <InputText id="password" ref="PasswordInput" type="password" autocomplete="new-password" v-model="form.password" aria-describedby="new-password"/>
                    <InputError :message="form.errors.password"/>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <InputLabel for="password_confirmation" value="Confirm Password"/>
                    <InputText id="password_confirmation" ref="PasswordInput" type="password" autocomplete="new-password" v-model="form.password_confirmation" aria-describedby="new-password"/>
                    <InputError :message="form.errors.password_confirmation"/>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </Panel>
    </section>
</template>
