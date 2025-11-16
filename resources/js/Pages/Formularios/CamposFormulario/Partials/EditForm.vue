<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    campo: Object,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    entidad_destino: "",
    key_tecnica: "",
    etiqueta: "",
    tipo_html: null,
    placeholder: "",
    api_endpoint_options: "",
});

// Rellena el formulario cuando el prop 'campo' cambia
watch(
    () => props.campo,
    (newVal) => {
        if (newVal) {
            form.entidad_destino = newVal.entidad_destino;
            form.key_tecnica = newVal.key_tecnica;
            form.etiqueta = newVal.etiqueta;
            form.tipo_html = newVal.tipo_html;
            form.placeholder = newVal.placeholder;
            form.api_endpoint_options = newVal.api_endpoint_options;
        }
    },
    { immediate: true, deep: true }
);

//
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
    form.patch(
        route("admin.formularios.campos-formulario.update", props.campo.id),
        {
            onSuccess: () => {
                emit("success", "Campo actualizado.");
                closeModal();
            },
        }
    );
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Editar Campo"
        :style="{ width: '45rem' }"
    >
        <form
            vIf="campo"
            @submit.prevent="submit"
            class="p-fluid mt-4 space-y-4"
        >
            <div class="field">
                <InputLabel
                    for="etiqueta_edit"
                    value="Etiqueta (Nombre visible)"
                />
                <InputText id="etiqueta_edit" v-model="form.etiqueta" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="field">
                    <InputLabel
                        for="entidad_destino_edit"
                        value="Entidad Destino"
                    />
                    <InputText
                        id="entidad_destino_edit"
                        v-model="form.entidad_destino"
                        placeholder="Ej: persona, detalles_auto"
                    />
                </div>
                <div class="field">
                    <InputLabel for="key_tecnica_edit" value="Key Técnica" />
                    <InputText
                        id="key_tecnica_edit"
                        v-model="form.key_tecnica"
                        placeholder="Ej: nombre, anio, matricula"
                    />
                </div>
            </div>

            <div class="field">
                <InputLabel for="tipo_html_edit" value="Tipo de Campo (HTML)" />
                <Dropdown
                    id="tipo_html_edit"
                    v-model="form.tipo_html"
                    :options="tipoHtmlOptions"
                    placeholder="Seleccione un tipo"
                />
            </div>

            <div class="field">
                <InputLabel
                    for="placeholder_edit"
                    value="Placeholder (Opcional)"
                />
                <InputText id="placeholder_edit" v-model="form.placeholder" />
            </div>

            <div class="field">
                <InputLabel
                    for="api_endpoint_options_edit"
                    value="Endpoint API para Opciones (Solo para 'select')"
                />
                <InputText
                    id="api_endpoint_options_edit"
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
                    label="Actualizar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
