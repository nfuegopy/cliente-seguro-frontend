<script setup>
import { route } from "ziggy-js";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Checkbox from "primevue/checkbox";
import Message from "primevue/message";

defineProps({ canResetPassword: Boolean, status: String });
const form = useForm({ email: "", password: "", remember: false });
const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Acceso Interno" />

    <div
        class="flex items-center justify-center min-h-screen bg-gray-900 text-white"
    >
        <div
            class="w-full max-w-md p-8 space-y-6 bg-gray-800 rounded-xl shadow-2xl"
        >
            <div class="text-center">
                <svg
                    class="w-20 h-20 mx-auto text-gray-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286Zm0 13.036h.008v.008h-.008v-.008Z"
                    />
                </svg>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-white">
                    Acceso Interno
                </h2>
            </div>

            <Message v-if="status" severity="success" :closable="false">{{
                status
            }}</Message>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="p-fluid">
                    <InputText
                        id="email"
                        v-model="form.email"
                        placeholder="Correo de Usuario"
                        required
                        :invalid="!!form.errors.email"
                        class="w-full"
                        :pt="{
                            root: {
                                class: 'bg-gray-700 border-gray-600 text-white focus:ring-indigo-500',
                            },
                        }"
                    />
                    <small
                        v-if="form.errors.email"
                        class="text-red-400 mt-2 block"
                        >{{ form.errors.email }}</small
                    >
                </div>

                <div class="p-fluid">
                    <InputText
                        id="password"
                        v-model="form.password"
                        type="password"
                        placeholder="Contraseña"
                        required
                        class="w-full"
                        :invalid="!!form.errors.password"
                        :pt="{
                            root: {
                                class: 'bg-gray-700 border-gray-600 text-white focus:ring-indigo-500',
                            },
                        }"
                    />
                    <small
                        v-if="form.errors.password"
                        class="text-red-400 mt-2 block"
                        >{{ form.errors.password }}</small
                    >
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <Checkbox
                            id="remember"
                            v-model="form.remember"
                            :binary="true"
                        />
                        <label
                            for="remember"
                            class="text-sm text-gray-300 select-none"
                            >Recordarme</label
                        >
                    </div>

                    <div class="text-sm">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="font-medium text-indigo-400 hover:text-indigo-300"
                        >
                            ¿Olvidaste tu contraseña?
                        </Link>
                    </div>
                </div>

                <div>
                    <Button
                        type="submit"
                        label="Ingresar al Sistema"
                        icon="pi pi-lock"
                        class="w-full !font-semibold !bg-indigo-600 hover:!bg-indigo-700 !border-indigo-600"
                        :loading="form.processing"
                    />
                </div>
            </form>
        </div>
    </div>
</template>
