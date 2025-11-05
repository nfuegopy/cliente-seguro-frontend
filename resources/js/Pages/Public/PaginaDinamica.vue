<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"; // <-- 1. Importar useForm
import { route } from "ziggy-js";
import WelcomeLayout from "@/Components/Welcome/WelcomeLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "primevue/button";

// --- 2. Importar componentes de formulario ---
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import Checkbox from "primevue/checkbox";
import Textarea from "primevue/textarea"; // <-- AÑADIDO
import InputLabel from "@/Components/InputLabel.vue";
import { ref } from "vue";

const props = defineProps({
    seccion: Object,
    productosConFormulario: Array, // <-- 3. Recibir la nueva prop
});

// 4. Crear un estado para manejar los datos del formulario
// Usaremos un objeto donde cada 'key_tecnica' será una propiedad
const form = useForm({
    // Aquí se llenarán dinámicamente los campos
});

// 5. Lógica para manejar el envío (simulado por ahora)
const submitForm = (productoId) => {
    // Aquí iría la lógica para enviar `form.data()`
    // al endpoint POST /polizas de la API.
    alert(
        `Enviando cotización para Producto ID: ${productoId}\nDatos: ${JSON.stringify(
            form.data(),
            null,
            2
        )}`
    );
};
</script>

<template>
    <Head :title="seccion.titulo" />

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
                            class="!font-bold !bg-teal-500 hover:!bg-teal-600 !border-teal-500"
                        />
                    </Link>
                    <Link :href="route('login')">
                        <Button
                            label="Acceso Interno"
                            type="button"
                            outlined
                            size="large"
                            class="!font-bold !border-white !text-white hover:!bg-white/10"
                        />
                    </Link>
                </div>
            </div>
        </div>

        <div
            class="min-h-screen flex items-center justify-center text-white p-6 bg-gray-900 pt-32"
        >
            <div class="max-w-4xl w-full">
                <div class="text-center">
                    <img
                        :src="seccion.imagen_url"
                        :alt="seccion.titulo"
                        class="max-w-md mx-auto rounded-lg shadow-2xl mb-8 transform transition-transform hover:scale-105 duration-300"
                    />
                    <h1
                        class="text-4xl xl:text-5xl font-extrabold mb-4 text-teal-400"
                    >
                        {{ seccion.titulo }}
                    </h1>
                    <p class="text-lg text-gray-300 leading-relaxed mb-12">
                        {{ seccion.descripcion }}
                    </p>
                </div>

                <div
                    v-if="
                        productosConFormulario &&
                        productosConFormulario.length > 0
                    "
                    class="space-y-12"
                >
                    <div
                        v-for="producto in productosConFormulario"
                        :key="producto.id"
                        class="bg-gray-800 p-6 md:p-8 rounded-lg shadow-2xl"
                    >
                        <h2 class="text-2xl font-bold mb-2 text-white">
                            {{ producto.nombre_producto }}
                        </h2>
                        <p class="text-gray-300 mb-6">
                            {{ producto.descripcion_corta }}
                        </p>

                        <form @submit.prevent="submitForm(producto.id)">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div
                                    v-for="campoLink in producto.formulario"
                                    :key="campoLink.id"
                                    :class="{
                                        'md:col-span-2':
                                            campoLink.campoFormulario
                                                .tipo_html === 'textarea',
                                        'flex items-center gap-2 pt-5':
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
                                        <InputLabel
                                            :for="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            :value="
                                                campoLink.campoFormulario
                                                    .etiqueta
                                            "
                                            class="!text-gray-200"
                                        />
                                    </template>

                                    <template v-else>
                                        <InputLabel
                                            :for="
                                                campoLink.campoFormulario
                                                    .key_tecnica
                                            "
                                            :value="
                                                campoLink.campoFormulario
                                                    .etiqueta
                                            "
                                            class="!text-gray-200"
                                        />

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
                                            class="w-full mt-1 bg-gray-700 border-gray-600 text-white"
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
                                            class="w-full mt-1"
                                            :pt="{
                                                input: {
                                                    root: {
                                                        class: 'bg-gray-700 border-gray-600 text-white w-full',
                                                    },
                                                },
                                            }"
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
                                            class="w-full mt-1"
                                            :pt="{
                                                input: {
                                                    class: 'bg-gray-700 border-gray-600 text-white w-full',
                                                },
                                            }"
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
                                            rows="3"
                                            class="w-full mt-1 bg-gray-700 border-gray-600 text-white"
                                        />

                                        <InputText
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
                                            :placeholder="`PENDIENTE: Cargar ${campoLink.campoFormulario.api_endpoint_options}`"
                                            disabled
                                            class="w-full mt-1 bg-gray-700 border-gray-600 text-white"
                                        />
                                    </template>
                                </div>
                            </div>
                            <div class="text-right mt-8">
                                <Button
                                    type="submit"
                                    label="Cotizar Ahora"
                                    size="large"
                                    class="!bg-teal-500 hover:!bg-teal-600 !border-teal-500 !font-semibold"
                                    :loading="form.processing"
                                />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </WelcomeLayout>
</template>
