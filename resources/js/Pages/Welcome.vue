<script setup>
import { Head } from "@inertiajs/vue3";
import WelcomeLayout from "@/Components/Welcome/WelcomeLayout.vue";
import WelcomeHeader from "@/Components/Welcome/WelcomeHeader.vue";
import HeroSection from "@/Components/Welcome/HeroSection.vue";

import { onMounted } from "vue";
import gsap from "gsap";
import Lenis from "@studio-freight/lenis";

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

    // Animación de entrada con GSAP
    gsap.from(".hero-content", {
        opacity: 0,
        y: 50,
        duration: 1.5,
        delay: 0.5,
        ease: "power3.out",
    });
});
</script>

<template>
    <Head title="Bienvenido" />
    <WelcomeLayout>
        <WelcomeHeader :can-login="canLogin" :can-register="canRegister" />
        <HeroSection class="hero-content" />
    </WelcomeLayout>
</template>
