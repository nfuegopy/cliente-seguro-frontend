<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import Checkbox from "primevue/checkbox";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    aseguradoras: Array,
    tiposSeguro: Array,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre_producto: "",
    descripcion_corta: "",
    activo: true,
    publicar_en_web: false, // <-- AÑADIR CAMPO
    aseguradora_id: null,
    tipo_de_seguro_id: null,
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.negocio.productosseguro.store"), {
        onSuccess: () => {
            emit("success", "Producto creado.");
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
        header="Crear Producto de Seguro"
        :style="{ width: '45rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="nombre_producto" value="Nombre del Producto" />
                <InputText
                    id="nombre_producto"
                    v-model="form.nombre_producto"
                />
            </div>
            <div class="field mb-4">
                <InputLabel for="aseguradora_id" value="Aseguradora" />
                <Dropdown
                    id="aseguradora_id"
                    v-model="form.aseguradora_id"
                    :options="aseguradoras"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione una Aseguradora"
                    filter
                />
            </div>
            <div class="field mb-4">
                <InputLabel for="tipo_de_seguro_id" value="Tipo de Seguro" />
                <Dropdown
                    id="tipo_de_seguro_id"
                    v-model="form.tipo_de_seguro_id"
                    :options="tiposSeguro"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione un Tipo"
                    filter
                />
            </div>
            <div class="field mb-4">
                <InputLabel for="descripcion_corta" value="Descripción Corta" />
                <Textarea
                    id="descripcion_corta"
                    v-model="form.descripcion_corta"
                    rows="3"
                />
            </div>

            <div class="flex gap-8 mb-4">
                <div class="field flex items-center gap-2">
                    <Checkbox
                        v-model="form.activo"
                        inputId="activo"
                        :binary="true"
                    />
                    <label for="activo">Activo (En sistema)</label>
                </div>
                <div class="field flex items-center gap-2">
                    <Checkbox
                        v-model="form.publicar_en_web"
                        inputId="publicar_en_web"
                        :binary="true"
                    />
                    <label for="publicar_en_web">Publicar (En Web)</label>
                </div>
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
