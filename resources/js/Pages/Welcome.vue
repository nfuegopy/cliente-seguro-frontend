<script setup>
import { Head } from "@inertiajs/vue3";
import WelcomeLayout from "@/Components/Welcome/WelcomeLayout.vue";
import HeroSection from "@/Components/Welcome/HeroSection.vue";
import ServicesSection from "@/Components/Welcome/ServicesSection.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "primevue/button";
import { Link } from "@inertiajs/vue3";

import { onMounted } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import Lenis from "@studio-freight/lenis";

gsap.registerPlugin(ScrollTrigger);

defineProps({ canLogin: Boolean, canRegister: Boolean });

onMounted(() => {
    // Inicializar Lenis para smooth scrolling
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    });
    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    // Animación de entrada para el texto del héroe
    gsap.from(".hero-content-text", {
        opacity: 0,
        y: 50,
        duration: 1.5,
        delay: 0.5,
        ease: "power3.out",
    });

    // Animación de las tarjetas de servicios
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
        <ServicesSection class="flex-1" />
    </WelcomeLayout>
</template>
