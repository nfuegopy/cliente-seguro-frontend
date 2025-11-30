<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { route } from "ziggy-js"; // Importante para que funcione route('welcome')
import gsap from "gsap";

// Componentes UI de PrimeVue
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import Checkbox from "primevue/checkbox";
import Textarea from "primevue/textarea";

// --- IMPORTACIONES CORREGIDAS SEGÚN TU ESTRUCTURA ---
import InputLabel from "../../Components/InputLabel.vue";
import SimpleFooter from "../Modern/SimpleFooter.vue";

const props = defineProps({
    seccion: Object,
    productosConFormulario: Array,
});

// Inicializamos el formulario de Inertia
const form = useForm({});

// Animaciones de entrada
onMounted(() => {
    gsap.from(".fade-in-up", {
        y: 30,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        ease: "power2.out",
    });
});

const submitForm = (productoId) => {
    alert(
        `Simulando envío para Producto ID: ${productoId}\nDatos: ${JSON.stringify(
            form.data(),
            null,
            2
        )}`
    );
    // Aquí conectaremos luego con el backend
};
</script>

<template>
    <Head :title="seccion.titulo" />

    <div
        class="bg-slate-50 min-h-screen font-sans text-slate-800 selection:bg-teal-100 selection:text-teal-900 flex flex-col"
    >
        <header
            class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50"
        >
            <div
                class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between"
            >
                <Link
                    :href="route('welcome')"
                    class="flex items-center gap-2 group"
                >
                    <div
                        class="w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center transition-transform group-hover:scale-110"
                    >
                        <i class="pi pi-arrow-left text-xs"></i>
                    </div>
                    <span
                        class="font-semibold text-sm text-slate-600 group-hover:text-slate-900 transition-colors"
                        >Volver al Inicio</span
                    >
                </Link>

                <div class="font-bold text-lg tracking-tight text-slate-900">
                    Seguro<span class="text-teal-600">Simple</span>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full max-w-5xl mx-auto px-6 py-12">
            <div class="text-center mb-16 fade-in-up">
                <div
                    class="inline-block p-4 bg-white rounded-2xl shadow-sm mb-6 ring-1 ring-slate-100"
                >
                    <img
                        :src="seccion.imagen_url"
                        :alt="seccion.titulo"
                        class="w-16 h-16 object-contain"
                    />
                </div>
                <h1
                    class="text-4xl md:text-5xl font-light text-slate-900 mb-4 tracking-tight"
                >
                    {{ seccion.titulo }}
                </h1>
                <p
                    class="text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed"
                >
                    {{ seccion.descripcion }}
                </p>
            </div>

            <div
                v-if="
                    productosConFormulario && productosConFormulario.length > 0
                "
                class="space-y-12 fade-in-up"
            >
                <div
                    v-for="producto in productosConFormulario"
                    :key="producto.id"
                    class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden"
                >
                    <div class="bg-slate-50/50 p-8 border-b border-slate-100">
                        <div
                            class="flex flex-col md:flex-row md:items-center justify-between gap-4"
                        >
                            <div>
                                <h2
                                    class="text-2xl font-bold text-slate-800 mb-1"
                                >
                                    {{ producto.nombre_producto }}
                                </h2>
                                <p class="text-slate-500 text-sm">
                                    {{
                                        producto.descripcion_corta ||
                                        "Completa los datos para cotizar."
                                    }}
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-50 text-teal-700 border border-teal-100"
                            >
                                Cotización Online
                            </span>
                        </div>
                    </div>

                    <div class="p-8 md:p-10">
                        <form @submit.prevent="submitForm(producto.id)">
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6"
                            >
                                <div
                                    v-for="campoLink in producto.formulario"
                                    :key="campoLink.id"
                                    :class="{
                                        'md:col-span-2':
                                            campoLink.campoFormulario
                                                .tipo_html === 'textarea',
                                        'flex items-center gap-3 pt-6':
                                            campoLink.campoFormulario
                                                .tipo_html === 'checkbox',
                                    }"
                                >
                                    <template
                                        v-if="
                                            campoLink.campoFormulario
                                                .tipo_html === 'checkbox'
                                        "
                                    >
                                        <Checkbox
                                            :id="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            v-model="
                                                form[
                                                    campoLink.campoFormulario
                                                        .key_tecnica
                                                ]
                                            "
                                            :binary="true"
                                        />
                                        <label
                                            :for="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            class="text-sm font-medium text-slate-700 cursor-pointer select-none"
                                        >
                                            {{
                                                campoLink.campoFormulario
                                                    .etiqueta
                                            }}
                                        </label>
                                    </template>

                                    <template v-else>
                                        <InputLabel
                                            :for="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            class="block text-sm font-semibold text-slate-700 mb-2"
                                        >
                                            {{
                                                campoLink.campoFormulario
                                                    .etiqueta
                                            }}
                                            <span
                                                v-if="campoLink.es_requerido"
                                                class="text-red-400"
                                                >*</span
                                            >
                                        </InputLabel>

                                        <InputText
                                            v-if="
                                                campoLink.campoFormulario
                                                    .tipo_html === 'text'
                                            "
                                            :id="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            v-model="
                                                form[
                                                    campoLink.campoFormulario
                                                        .key_tecnica
                                                ]
                                            "
                                            :placeholder="
                                                campoLink.campoFormulario
                                                    .placeholder
                                            "
                                            class="w-full !bg-slate-50 !border-slate-200 focus:!border-teal-500 focus:!ring-teal-500 !text-slate-800 !rounded-xl !py-3"
                                        />

                                        <InputNumber
                                            v-else-if="
                                                campoLink.campoFormulario
                                                    .tipo_html === 'number'
                                            "
                                            :id="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            v-model="
                                                form[
                                                    campoLink.campoFormulario
                                                        .key_tecnica
                                                ]
                                            "
                                            :placeholder="
                                                campoLink.campoFormulario
                                                    .placeholder
                                            "
                                            class="w-full"
                                            :inputClass="'w-full !bg-slate-50 !border-slate-200 focus:!border-teal-500 !text-slate-800 !rounded-xl !py-3'"
                                        />

                                        <Calendar
                                            v-else-if="
                                                campoLink.campoFormulario
                                                    .tipo_html === 'date'
                                            "
                                            :id="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            v-model="
                                                form[
                                                    campoLink.campoFormulario
                                                        .key_tecnica
                                                ]
                                            "
                                            :placeholder="
                                                campoLink.campoFormulario
                                                    .placeholder
                                            "
                                            dateFormat="dd/mm/yy"
                                            class="w-full"
                                            :inputClass="'w-full !bg-slate-50 !border-slate-200 focus:!border-teal-500 !text-slate-800 !rounded-xl !py-3'"
                                            showIcon
                                        />

                                        <Textarea
                                            v-else-if="
                                                campoLink.campoFormulario
                                                    .tipo_html === 'textarea'
                                            "
                                            :id="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            v-model="
                                                form[
                                                    campoLink.campoFormulario
                                                        .key_tecnica
                                                ]
                                            "
                                            :placeholder="
                                                campoLink.campoFormulario
                                                    .placeholder
                                            "
                                            rows="4"
                                            class="w-full !bg-slate-50 !border-slate-200 focus:!border-teal-500 !text-slate-800 !rounded-xl"
                                        />

                                        <Dropdown
                                            v-else-if="
                                                campoLink.campoFormulario
                                                    .tipo_html === 'select'
                                            "
                                            :id="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            v-model="
                                                form[
                                                    campoLink.campoFormulario
                                                        .key_tecnica
                                                ]
                                            "
                                            :options="[]"
                                            :placeholder="`Seleccionar ${campoLink.campoFormulario.etiqueta}`"
                                            class="w-full !bg-slate-50 !border-slate-200 !rounded-xl"
                                            disabled
                                        />
                                    </template>
                                </div>
                            </div>

                            <div class="mt-10 flex justify-end">
                                <Button
                                    type="submit"
                                    label="Solicitar Cotización"
                                    icon="pi pi-send"
                                    iconPos="right"
                                    class="!bg-slate-900 hover:!bg-slate-800 !border-slate-900 !text-white !font-bold !py-3 !px-8 !rounded-xl !shadow-lg hover:!shadow-xl transition-all w-full md:w-auto"
                                    :loading="form.processing"
                                />
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-24 fade-in-up">
                <div
                    class="inline-flex justify-center items-center w-20 h-20 bg-white rounded-full shadow-sm mb-6 ring-1 ring-slate-100"
                >
                    <i
                        class="pi pi-exclamation-circle text-3xl text-slate-300"
                    ></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700">
                    Producto no disponible
                </h3>
                <p class="text-slate-500 mt-2">
                    Lo sentimos, esta sección aún no tiene productos
                    configurados.
                </p>
                <Link
                    :href="route('welcome')"
                    class="inline-block mt-6 text-teal-600 font-semibold hover:underline"
                >
                    Volver al inicio
                </Link>
            </div>
        </main>

        <SimpleFooter />
    </div>
</template>

<style scoped>
/* Ajustes finos para inputs de PrimeVue */
:deep(.p-inputtext) {
    transition: all 0.2s;
}
:deep(.p-inputtext:enabled:hover) {
    border-color: #cbd5e1; /* slate-300 */
}
:deep(.p-inputtext:enabled:focus) {
    border-color: #0d9488; /* teal-600 */
    box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.1);
}
</style>
