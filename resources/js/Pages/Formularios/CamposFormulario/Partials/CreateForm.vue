<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import InputLabel from "@/Components/InputLabel.vue";

const emit = defineEmits(["update:visible", "success"]);
const props = defineProps({ visible: Boolean });

const form = useForm({
    entidad_destino: "",
    key_tecnica: "",
    etiqueta: "",
    tipo_html: null,
    placeholder: "",
    api_endpoint_options: "",
});

// Basado en tu Enum de NestJS
const tipoHtmlOptions = ref([
    "text",
    "number",
    "date",
    "select",
    "checkbox",
    "textarea",
]);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.formularios.campos-formulario.store"), {
        onSuccess: () => {
            emit("success", "Campo creado.");
            closeModal();
            form.reset();
        },
    });
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Crear Nuevo Campo"
        :style="{ width: '45rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4 space-y-4">
            <div class="field">
                <InputLabel for="etiqueta" value="Etiqueta (Nombre visible)" />
                <InputText id="etiqueta" v-model="form.etiqueta" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="field">
                    <InputLabel for="entidad_destino" value="Entidad Destino" />
                    <InputText
                        id="entidad_destino"
                        v-model="form.entidad_destino"
                        placeholder="Ej: persona, detalles_auto"
                    />
                </div>
                <div class="field">
                    <InputLabel for="key_tecnica" value="Key Técnica" />
                    <InputText
                        id="key_tecnica"
                        v-model="form.key_tecnica"
                        placeholder="Ej: nombre, anio, matricula"
                    />
                </div>
            </div>

            <div class="field">
                <InputLabel for="tipo_html" value="Tipo de Campo (HTML)" />
                <Dropdown
                    id="tipo_html"
                    v-model="form.tipo_html"
                    :options="tipoHtmlOptions"
                    placeholder="Seleccione un tipo"
                />
            </div>

            <div class="field">
                <InputLabel for="placeholder" value="Placeholder (Opcional)" />
                <InputText id="placeholder" v-model="form.placeholder" />
            </div>

            <div class="field">
                <InputLabel
                    for="api_endpoint_options"
                    value="Endpoint API para Opciones (Solo para 'select')"
                />
                <InputText
                    id="api_endpoint_options"
                    v-model="form.api_endpoint_options"
                    placeholder="Ej: /vehiculo-marcas"
                />
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    @click="closeModal"
                />
                <Button
                    type="submit"
                    label="Guardar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
