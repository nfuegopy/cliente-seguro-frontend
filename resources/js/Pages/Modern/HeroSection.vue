<script setup>
import Button from "primevue/button";
import { onMounted } from "vue";
import gsap from "gsap";

const emit = defineEmits(["scroll-down"]);

onMounted(() => {
    gsap.from(".hero-anim", {
        y: 20,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        ease: "power3.out",
    });
});
</script>

<template>
    <section
        class="min-h-[85vh] flex flex-col justify-center items-center px-4 relative overflow-hidden bg-slate-50"
    >
        <div
            class="hero-anim mb-6 p-3 bg-white rounded-xl shadow-sm ring-1 ring-slate-100"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-8 h-8 text-teal-600"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 1.043 3.296 3.745 3.745 0 0 1 21 12Z"
                />
            </svg>
        </div>

        <h1
            class="hero-anim text-4xl md:text-5xl font-light tracking-tight text-slate-900 mb-4 text-center"
        >
            Tu Seguro,
            <span class="font-semibold text-teal-600">Simplificado</span>.
        </h1>

        <p
            class="hero-anim text-base text-slate-500 max-w-md text-center mb-8 leading-relaxed"
        >
            Sin trámites eternos. Descubre la nueva forma de proteger lo que
            amas en Paraguay.
        </p>

        <div class="hero-anim">
            <Button
                @click="$emit('scroll-down')"
                label="Ver Coberturas"
                icon="pi pi-arrow-down"
                iconPos="right"
                class="!rounded-full !px-6 !py-2 !bg-slate-900 !border-slate-900 hover:!bg-slate-800 !text-white !font-medium !shadow-md hover:!shadow-lg transition-all !text-xs uppercase tracking-wide"
            />
        </div>

        <div
            class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none opacity-60"
        >
            <div
                class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-teal-50/50 rounded-full blur-3xl"
            ></div>
            <div
                class="absolute top-[40%] -right-[10%] w-[30%] h-[30%] bg-blue-50/50 rounded-full blur-3xl"
            ></div>
        </div>
    </section>
</template>
