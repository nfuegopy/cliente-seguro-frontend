<script setup>
import { route } from "ziggy-js";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
</script>

<template>
    <div
        class="relative z-20 flex-1 flex items-center justify-center text-center p-6 lg:p-12 w-full lg:w-1/2"
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
                Protegemos lo que más te importa. Ofrecemos una amplia gama de
                seguros para tu vehículo, tu hogar y tu vida, adaptados a tus
                necesidades. Cotiza con nosotros y encuentra la cobertura
                perfecta para ti y tu familia.
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
</template>
