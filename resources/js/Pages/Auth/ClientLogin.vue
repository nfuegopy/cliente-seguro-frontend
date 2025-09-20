<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Checkbox from "primevue/checkbox";
import Message from "primevue/message";

defineProps({ canResetPassword: Boolean, status: String });
const form = useForm({ email: "", password: "", remember: false });
const submit = () => {
    form.post(route("cliente.login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Acceso Clientes" />

    <div
        class="flex items-center justify-center min-h-screen bg-gradient-to-br from-slate-900 to-gray-900 text-white"
    >
        <div
            class="w-full max-w-md p-8 space-y-6 bg-slate-800/60 rounded-xl shadow-2xl backdrop-blur-sm"
        >
            <div class="text-center">
                <svg
                    class="w-20 h-20 mx-auto text-slate-500"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 55 55"
                >
                    <path
                        fill="#2dd4bf"
                        d="M30.133 39.867 13.867 52.5 29.867 0l22.533 13.6-13.867 26.267-8.4-16.8Z"
                    />
                    <path
                        fill="#2dd4bf"
                        d="m29.867 0 16.266 29.867L23.6 39.867.267 26.267 29.867 0Z"
                    />
                </svg>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-white">
                    Acceso de Clientes
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
                        placeholder="Correo Electrónico"
                        required
                        class="w-full"
                        :invalid="!!form.errors.email"
                        :pt="{
                            root: {
                                class: 'bg-slate-700 border-slate-600 text-white focus:ring-teal-500',
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
                                class: 'bg-slate-700 border-slate-600 text-white focus:ring-teal-500',
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
                            class="text-sm text-slate-300 select-none"
                            >Recordarme</label
                        >
                    </div>

                    <div class="text-sm">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="font-medium text-teal-400 hover:text-teal-300"
                        >
                            ¿Olvidaste tu contraseña?
                        </Link>
                    </div>
                </div>

                <div>
                    <Button
                        type="submit"
                        label="Ingresar"
                        icon="pi pi-sign-in"
                        class="w-full !font-semibold !bg-teal-500 hover:!bg-teal-600 !border-teal-500"
                        :loading="form.processing"
                    />
                </div>
            </form>
        </div>
    </div>
</template>
