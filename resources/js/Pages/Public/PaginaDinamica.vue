<script setup>
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { route } from "ziggy-js"; // Importante para que funcione route('welcome')
import gsap from "gsap";
import axios from "axios"; //Import de axio para utilizar dentro de las rutas publicas
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

const selectOptions = ref({});

// Animaciones de entrada
onMounted(() => {
    gsap.from(".fade-in-up", {
        y: 30,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        ease: "power2.out",
    });

    props.productosConFormulario.forEach((producto) => {
        producto.formulario.forEach(async (campoLink) => {
            const campo = campoLink.campoFormulario;

            // Si es un SELECT y tiene un endpoint definido en la BD
            if (campo.tipo_html === "select" && campo.api_endpoint_options) {
                try {
                    // Quitamos la barra inicial si la tiene para que coincida con la ruta de Laravel
                    const cleanEndpoint = campo.api_endpoint_options.startsWith(
                        "/"
                    )
                        ? campo.api_endpoint_options.substring(1)
                        : campo.api_endpoint_options;

                    // Llamamos a NUESTRO proxy de Laravel
                    // URL Final: /clienteseguro/api/public/vehiculo-marcas
                    const response = await axios.get(
                        route("api.public.proxy", { endpoint: cleanEndpoint })
                    );

                    // Guardamos las opciones usando la key_tecnica (ej: 'marca_id') como índice
                    selectOptions.value[campo.key_tecnica] = response.data;
                } catch (error) {
                    console.error(
                        `Error cargando opciones para ${campo.etiqueta}:`,
                        error
                    );
                }
            }
        });
    });
});

const submitForm = (productoId) => {
    // 1. Buscamos la configuración del producto que el usuario está intentando enviar
    const productoActual = props.productosConFormulario.find(
        (p) => p.id === productoId
    );

    if (!productoActual) return;

    // 2. Preparamos el esqueleto del JSON que NestJS espera.
    // Fíjate que inicializamos ambos detalles vacíos.
    const payload = {
        producto_seguro_id: productoId,
        persona: {},
        usuario: {},
        detalles_auto: null, // Inicialmente null
        detalles_medico: null, // Inicialmente null
        otros_datos: {},
    };

    // Objetos temporales para ir llenando datos si existen
    const tempAuto = {};
    const tempMedico = {};

    // 3. Magia: Clasificamos cada campo según lo que diga la Base de Datos
    productoActual.formulario.forEach((link) => {
        const campo = link.campoFormulario;
        const valor = form[campo.key_tecnica];

        // Solo guardamos si el usuario escribió algo
        if (valor !== undefined && valor !== null && valor !== "") {
            switch (campo.entidad_destino) {
                case "persona":
                    payload.persona[campo.key_tecnica] = valor;
                    break;
                case "usuario":
                    payload.usuario[campo.key_tecnica] = valor;
                    break;
                case "detalles_poliza_auto":
                    // Si encontramos un dato de auto, lo guardamos en el temporal
                    tempAuto[campo.key_tecnica] = valor;
                    break;
                case "detalles_poliza_medica":
                    // Si encontramos un dato médico, lo guardamos en el temporal
                    tempMedico[campo.key_tecnica] = valor;
                    break;
                default:
                    payload.otros_datos[campo.key_tecnica] = valor;
            }
        }
    });

    // 4. Decisión final: Si llenamos datos de auto, activamos ese objeto en el payload
    if (Object.keys(tempAuto).length > 0) {
        payload.detalles_auto = tempAuto;
    }
    // Si llenamos datos médicos, activamos ese objeto
    if (Object.keys(tempMedico).length > 0) {
        payload.detalles_medico = tempMedico;
    }

    // 5. Enviamos a Laravel (quien luego se lo pasará a NestJS)
    console.log("Enviando Payload Inteligente:", payload);

    // Usamos router.post en lugar de form.post para tener control total del objeto JSON
    router.post(route("public.solicitud.store"), payload, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            // Aquí podrías usar tu Toast de PrimeVue
            alert("¡Solicitud recibida! Estamos procesando tu presupuesto.");
        },
        onError: (errors) => {
            console.error("Errores:", errors);
            alert("Por favor revisa los campos requeridos.");
        },
    });
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
                                            :options="
                                                selectOptions[
                                                    campoLink.campoFormulario
                                                        .key_tecnica
                                                ] || []
                                            "
                                            optionLabel="nombre"
                                            optionValue="id"
                                            :placeholder="`Seleccionar ${campoLink.campoFormulario.etiqueta}`"
                                            :disabled="
                                                !selectOptions[
                                                    campoLink.campoFormulario
                                                        .key_tecnica
                                                ]
                                            "
                                            filter
                                            class="w-full custom-dropdown"
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
/* Estilos para Inputs normales */
:deep(.p-inputtext) {
    background-color: #f8fafc;
    border-color: #e2e8f0;
    color: #1e293b;
}

/* === SOLUCIÓN DEFINITIVA PARA EL DROPDOWN === */

/* 1. El contenedor del Dropdown (cuando está cerrado) */
:deep(.custom-dropdown) {
    background-color: #000000 !important; /* Fondo blanco puro */
    border: 1px solid #cbd5e1 !important; /* Borde gris visible */
    border-radius: 0.75rem;
}

/* 2. El texto seleccionado (Lo que te fallaba) */
:deep(.custom-dropdown .p-dropdown-label) {
    color: #090707 !important; /* Negro absoluto para máximo contraste */
    font-weight: 600 !important;
}

/* 3. El texto del Placeholder (Cuando no has seleccionado nada) */
:deep(.custom-dropdown .p-dropdown-label.p-placeholder) {
    color: #64748b !important; /* Gris medio */
    font-weight: normal !important;
}

/* 4. El icono de la flecha */
:deep(.custom-dropdown .p-dropdown-trigger) {
    color: #64748b !important;
    background: transparent !important;
}

/* 5. El Panel Desplegable (La lista de opciones) */
:deep(.p-dropdown-panel) {
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
}

/* 6. Los ítems de la lista */
:deep(.p-dropdown-item) {
    color: #334155 !important; /* Gris oscuro */
    background-color: transparent !important;
}

/* 7. Ítem al pasar el mouse (Hover) */
:deep(.p-dropdown-item:hover) {
    background-color: #f1f5f9 !important; /* Gris muy claro */
    color: #0f172a !important;
}

/* 8. Ítem seleccionado en la lista (Highlight) */
:deep(.p-dropdown-item.p-highlight) {
    background-color: #f0fdfa !important; /* Fondo verde azulado claro */
    color: #0d9488 !important; /* Texto verde azulado */
    font-weight: bold;
}
</style>
