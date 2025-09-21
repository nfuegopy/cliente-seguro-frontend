<script setup>
import { route } from "ziggy-js";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
</script>

<template>
    <div v-if="page.props.auth.user">
        <Link
            :href="route('dashboard')"
            class="font-semibold text-white hover:text-gray-300 transition-colors"
        >
            Dashboard
        </Link>
    </div>
    <div v-else>
        <Link :href="route('cliente.login')">
            <Button
                label="Acceso Clientes"
                type="button"
                size="large"
                class="!font-bold !bg-teal-500 hover:!bg-teal-600 !border-teal-500 transition-transform hover:scale-105"
            />
        </Link>
        <Link :href="route('login')">
            <Button
                label="Acceso Interno"
                type="button"
                outlined
                size="large"
                class="!font-bold !border-white !text-white hover:!bg-white/10 transition-transform hover:scale-105"
            />
        </Link>
    </div>
</template>
