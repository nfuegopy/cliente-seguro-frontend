<script setup>
import { Head } from "@inertiajs/vue3";
import HeroSection from "./Modern/HeroSection.vue";
import ServicesGrid from "./Modern/ServicesGrid.vue";
import TestimonialsSection from "./Modern/TestimonialsSection.vue";
import SimpleFooter from "./Modern/SimpleFooter.vue";

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    secciones: {
        type: Array,
        default: () => [],
    },
});

// --- DEBUG: MIRA LA CONSOLA DEL NAVEGADOR (F12) ---
console.log("🚀 VUE WELCOME CARGADO. Datos recibidos:", props.secciones);
// --------------------------------------------------

const scrollToServices = () => {
    const element = document.getElementById("services-grid-anchor");
    if (element) {
        element.scrollIntoView({ behavior: "smooth" });
    }
};
</script>

<template>
    <Head title="Inicio" />

    <div
        class="bg-slate-50 min-h-screen font-sans selection:bg-teal-100 selection:text-teal-900"
    >
        <HeroSection @scroll-down="scrollToServices" />

        <div id="services-grid-anchor" class="pt-4"></div>

        <ServicesGrid :secciones="secciones" />

        <TestimonialsSection />

        <SimpleFooter />
    </div>
</template>
