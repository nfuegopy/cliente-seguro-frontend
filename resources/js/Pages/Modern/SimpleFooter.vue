<script setup>
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
</script>

<template>
    <footer class="py-8 px-6 bg-slate-50 border-t border-slate-100">
        <div
            class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4"
        >
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                <span
                    class="text-xs font-semibold text-slate-700 tracking-wide uppercase"
                    >SeguroSimple</span
                >
            </div>

            <div
                class="text-[10px] text-slate-400 text-center md:text-right flex items-center gap-3"
            >
                <span>&copy; 2025 Derechos Reservados</span>
                <span class="text-slate-300">|</span>

                <Link
                    :href="route('login')"
                    class="hover:text-teal-600 transition-colors cursor-pointer"
                    title="Ingreso Administrativo"
                >
                    Admin
                </Link>
            </div>
        </div>
    </footer>
</template>
