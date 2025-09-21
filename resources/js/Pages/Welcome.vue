<script setup>
import { route } from "ziggy-js";
import { Head, Link } from "@inertiajs/vue3";
import Button from "primevue/button";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
    },
    phpVersion: {
        type: String,
    },
});
</script>

<template>
    <Head title="Bienvenido" />

    <div
        class="relative min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col lg:flex-row"
    >
        <div v-if="canLogin" class="absolute top-0 right-0 p-6 z-30">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-white hover:text-gray-300"
                >Dashboard</Link
            >
            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-white hover:text-gray-300"
                    >Iniciar Sesión</Link
                >
            </template>
        </div>

        <div class="relative flex-1 flex flex-col lg:flex-row">
            <div
                class="relative z-20 flex-1 flex items-center justify-center text-center lg:text-left p-6 lg:p-12"
            >
                <div class="w-full max-w-xl">
                    <h1
                        class="text-4xl xl:text-5xl font-bold text-white lg:text-gray-900 dark:text-white mb-6 leading-tight"
                    >
                        <span>Tu tranquilidad, </span>
                        <span class="text-primary">nuestro compromiso.</span>
                    </h1>

                    <p
                        class="text-gray-200 lg:text-gray-700 dark:text-gray-300 text-lg leading-relaxed mb-8"
                    >
                        Protegemos lo que más te importa. Ofrecemos una amplia
                        gama de seguros para tu vehículo, tu hogar y tu vida,
                        adaptados a tus necesidades. Cotiza con nosotros y
                        encuentra la cobertura perfecta para ti y tu familia.
                    </p>

                    <div
                        class="flex items-center gap-4 justify-center lg:justify-start"
                    >
                        <Link :href="route('cliente.login')">
                            <Button
                                label="Acceso Clientes"
                                type="button"
                                size="large"
                            />
                        </Link>

                        <Link :href="route('login')">
                            <Button
                                label="Acceso Interno"
                                type="button"
                                outlined
                                size="large"
                            />
                        </Link>
                    </div>
                </div>
            </div>

            <div class="relative flex-1">
                <div class="absolute lg:hidden inset-0 bg-black/50 z-10" />

                <img
                    src="/img/demo-imagen.png"
                    alt="Familia protegida por un seguro"
                    class="h-full w-full object-cover"
                />
            </div>
        </div>
    </div>
</template>
