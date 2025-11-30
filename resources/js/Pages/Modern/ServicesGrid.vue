<script setup>
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { onMounted } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const props = defineProps({
    secciones: { type: Array, default: () => [] },
});

const slugify = (text) => {
    return text
        .toString()
        .toLowerCase()
        .replace(/\s+/g, "-")
        .replace(/[^\w-]+/g, "")
        .replace(/--+/g, "-")
        .replace(/^-+/, "")
        .replace(/-+$/, "");
};

onMounted(() => {
    gsap.from(".service-card", {
        y: 40,
        opacity: 0,
        duration: 0.6,
        stagger: 0.1,
        ease: "power2.out",
        scrollTrigger: {
            trigger: "#services-grid",
            start: "top 90%",
        },
    });
});
</script>

<template>
    <section
        id="services-grid"
        class="py-16 px-4 max-w-6xl mx-auto bg-white rounded-t-[2.5rem] -mt-8 shadow-[0_-5px_30px_rgba(0,0,0,0.01)] relative z-10"
    >
        <div class="mb-12 text-center">
            <span
                class="text-teal-600 font-bold tracking-widest text-[10px] uppercase bg-teal-50 px-2 py-1 rounded-full"
                >Soluciones</span
            >
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mt-3">
                Elige tu protección
            </h2>
        </div>

        <div
            v-if="secciones && secciones.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div
                v-for="seccion in secciones"
                :key="seccion.id"
                class="service-card group bg-white rounded-2xl border border-slate-100 p-2 shadow-sm hover:shadow-md hover:border-teal-100 transition-all duration-300 cursor-pointer flex flex-col h-full"
            >
                <div
                    class="h-32 w-full overflow-hidden rounded-xl bg-slate-50 relative mb-3"
                >
                    <div
                        class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors duration-300 z-10"
                    ></div>
                    <img
                        :src="seccion.imagen_url"
                        :alt="seccion.titulo"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 grayscale group-hover:grayscale-0"
                        loading="lazy"
                    />
                </div>

                <div class="px-3 pb-3 flex flex-col flex-1">
                    <h3
                        class="text-lg font-bold text-slate-900 mb-1 group-hover:text-teal-600 transition-colors"
                    >
                        {{ seccion.titulo }}
                    </h3>

                    <p
                        class="text-slate-500 text-xs leading-relaxed mb-4 line-clamp-2 flex-1"
                    >
                        {{ seccion.descripcion }}
                    </p>

                    <Link
                        :href="
                            route('public.page.show', {
                                slug: slugify(seccion.titulo),
                            })
                        "
                        class="mt-auto w-full py-2 rounded-lg bg-slate-50 text-slate-800 font-semibold text-xs text-center group-hover:bg-teal-600 group-hover:text-white transition-colors border border-slate-100 group-hover:border-teal-600"
                    >
                        Configurar {{ seccion.texto_boton || "" }}
                    </Link>
                </div>
            </div>
        </div>

        <div
            v-else
            class="text-center py-16 px-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200"
        >
            <div
                class="inline-flex justify-center items-center w-12 h-12 bg-white rounded-full shadow-sm mb-3"
            >
                <i class="pi pi-inbox text-xl text-slate-400"></i>
            </div>
            <h3 class="text-base font-semibold text-slate-700">
                No hay coberturas activas
            </h3>
        </div>
    </section>
</template>
