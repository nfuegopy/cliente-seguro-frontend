<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.userFromApi;
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <div class="p-6">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div v-if="user" class="p-6 text-gray-900">
                        ¡Bienvenido, {{ user.nombre }} {{ user.apellido }}!
                        <p class="mt-4">
                            Selecciona una opción del menú lateral para
                            comenzar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
