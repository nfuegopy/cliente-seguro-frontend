<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import WelcomeLayout from "@/Components/Welcome/WelcomeLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "primevue/button";

const props = defineProps({
    seccion: Object,
});
</script>

<template>
    <Head :title="seccion.titulo" />

    <WelcomeLayout>
        <div class="fixed top-0 w-full p-6 z-30">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <Link href="/">
                    <ApplicationLogo
                        class="w-16 h-16 fill-current text-white"
                    />
                </Link>
                <div class="flex items-center gap-4">
                    <Link :href="route('cliente.login')">
                        <Button
                            label="Acceso Clientes"
                            type="button"
                            size="large"
                            class="!font-bold !bg-teal-500 hover:!bg-teal-600 !border-teal-500"
                        />
                    </Link>
                    <Link :href="route('login')">
                        <Button
                            label="Acceso Interno"
                            type="button"
                            outlined
                            size="large"
                            class="!font-bold !border-white !text-white hover:!bg-white/10"
                        />
                    </Link>
                </div>
            </div>
        </div>

        <div
            class="min-h-screen flex items-center justify-center text-white text-center p-6 bg-gray-900"
        >
            <div class="max-w-4xl">
                <img
                    :src="seccion.imagen_url"
                    :alt="seccion.titulo"
                    class="max-w-md mx-auto rounded-lg shadow-2xl mb-8 transform transition-transform hover:scale-105 duration-300"
                />

                <h1
                    class="text-4xl xl:text-5xl font-extrabold mb-4 text-teal-400"
                >
                    {{ seccion.titulo }}
                </h1>

                <p class="text-lg text-gray-300 leading-relaxed">
                    {{ seccion.descripcion }}
                </p>
            </div>
        </div>
    </WelcomeLayout>
</template>
