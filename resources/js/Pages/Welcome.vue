<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import WelcomeLayout from "@/Components/Welcome/WelcomeLayout.vue";
import HeroSection from "@/Components/Welcome/HeroSection.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "primevue/button";
import { onMounted } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    secciones: Array,
});

// --- 1. AÑADIR FUNCIÓN PARA CREAR SLUG ---
// Esta función convierte "Seguro de Vehículo" en "seguro-de-vehiculo"
const slugify = (text) => {
    return text
        .toString()
        .toLowerCase()
        .replace(/\s+/g, "-") // Reemplaza espacios con -
        .replace(/[^\w-]+/g, "") // Quita caracteres no alfanuméricos
        .replace(/--+/g, "-") // Reemplaza múltiples - con uno solo
        .replace(/^-+/, "") // Quita - del inicio
        .replace(/-+$/, ""); // Quita - del final
};

// Función para determinar si una URL es externa (empieza con http)
// (Esta función ya no la usamos, pero la dejamos por si la necesitas)
// const isExternal = (url) => {
//     return url && url.startsWith("http");
// };

onMounted(() => {
    gsap.from(".hero-content-text", {
        opacity: 0,
        y: 50,
        duration: 1.5,
        delay: 0.5,
        ease: "power3.out",
    });
    gsap.from(".service-section", {
        y: 50,
        opacity: 0,
        duration: 1,
        stagger: 0.2,
        ease: "power3.out",
        scrollTrigger: {
            trigger: ".services-section-container",
            start: "top 80%",
            toggleActions: "play none none reverse",
        },
    });
});
</script>

<template>
    <Head title="Bienvenido" />
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
            </div>
        </div>

        <HeroSection class="flex-1" />

        <section
            class="min-h-screen w-full bg-gray-800 text-white py-20 services-section-container"
        >
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-16 text-teal-400">
                    Nuestros Servicios
                </h2>

                <div
                    v-for="(seccion, index) in secciones"
                    :key="seccion.id"
                    class="service-section flex flex-col items-center gap-12 mb-20 md:mb-32"
                    :class="{
                        'md:flex-row-reverse': index % 2 !== 0,
                        'md:flex-row': index % 2 === 0,
                    }"
                >
                    <img
                        :src="seccion.imagen_url"
                        :alt="seccion.titulo"
                        class="w-full md:w-1/2 rounded-xl shadow-2xl transform transition-transform hover:scale-105 duration-300"
                    />
                    <div
                        class="w-full md:w-1/2 text-center"
                        :class="{
                            'md:text-right': index % 2 !== 0,
                            'md:text-left': index % 2 === 0,
                        }"
                    >
                        <h3 class="text-4xl font-bold mb-4">
                            {{ seccion.titulo }}
                        </h3>
                        <p class="text-lg text-gray-300 mb-6 leading-relaxed">
                            {{ seccion.descripcion }}
                        </p>

                        <Link
                            :href="
                                route('public.page.show', {
                                    slug: slugify(seccion.titulo),
                                })
                            "
                        >
                            <Button
                                :label="seccion.texto_boton"
                                size="large"
                                class="!bg-teal-500 hover:!bg-teal-600 !border-teal-500 !font-semibold"
                            />
                        </Link>
                    </div>
                </div>

                <div
                    v-if="!secciones || secciones.length === 0"
                    class="text-center text-gray-400"
                >
                    <p>No hay servicios para mostrar en este momento.</p>
                </div>
            </div>
        </section>
    </WelcomeLayout>
</template>
