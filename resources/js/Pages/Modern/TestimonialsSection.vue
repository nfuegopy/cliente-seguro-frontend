<script setup>
const testimonios = [
    {
        id: 1,
        quote: "La rapidez en la atención fue increíble. Nunca pensé que contratar un seguro fuera tan sencillo.",
        author: "Carla Mendoza",
        role: "Cliente desde 2023",
    },
    {
        id: 2,
        quote: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.",
        author: "Javier Torres",
        role: "Propietario de Negocio",
    },
    {
        id: 3,
        quote: "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
        author: "Sofía Benítez",
        role: "Freelancer",
    },
];
</script>

<template>
    <section class="py-20 bg-white border-t border-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div class="lg:col-span-1">
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">
                        Confianza que se siente.
                    </h2>
                    <p class="text-slate-500 leading-relaxed mb-8">
                        Miles de paraguayos ya eligen la tranquilidad de estar
                        respaldados por nosotros. Transparencia y solidez en
                        cada póliza.
                    </p>
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white"
                            ></div>
                            <div
                                class="w-8 h-8 rounded-full bg-slate-300 border-2 border-white"
                            ></div>
                            <div
                                class="w-8 h-8 rounded-full bg-slate-400 border-2 border-white"
                            ></div>
                        </div>
                        <span class="text-xs font-semibold text-slate-600 pl-2"
                            >+500 Clientes felices</span
                        >
                    </div>
                </div>

                <div class="lg:col-span-2 flex flex-col md:flex-row gap-6">
                    <div
                        v-for="t in testimonios.slice(0, 2)"
                        :key="t.id"
                        class="flex-1 bg-slate-50 p-8 rounded-2xl"
                    >
                        <div
                            class="text-teal-500 text-4xl font-serif mb-4 leading-none"
                        >
                            "
                        </div>
                        <p class="text-slate-700 italic mb-6 text-sm">
                            {{ t.quote }}
                        </p>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">
                                {{ t.author }}
                            </h4>
                            <span
                                class="text-xs text-slate-400 uppercase tracking-wider"
                                >{{ t.role }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
