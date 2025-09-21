<script setup>
import { route } from "ziggy-js";
import { Link } from "@inertiajs/vue3";

defineProps({ canLogin: Boolean, canRegister: Boolean });
</script>

<template>
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
</template>
