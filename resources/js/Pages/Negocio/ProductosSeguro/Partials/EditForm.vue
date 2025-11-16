<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import Checkbox from "primevue/checkbox";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    producto: Object,
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

watch(
    () => props.producto,
    (newVal) => {
        if (newVal) {
            form.nombre_producto = newVal.nombre_producto;
            form.descripcion_corta = newVal.descripcion_corta;
            form.activo = newVal.activo;
            form.publicar_en_web = newVal.publicar_en_web; // <-- AÑADIR CAMPO
            form.aseguradora_id = newVal.aseguradora.id;
            form.tipo_de_seguro_id = newVal.tipo_de_seguro.id;
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(
        route("admin.negocio.productosseguro.update", props.producto.id),
        {
            onSuccess: () => {
                emit("success", "Producto actualizado.");
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
        header="Editar Producto de Seguro"
        :style="{ width: '45rem' }"
    >
        <form v-if="producto" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel
                    for="nombre_producto_edit"
                    value="Nombre del Producto"
                />
                <InputText
                    id="nombre_producto_edit"
                    v-model="form.nombre_producto"
                />
            </div>
            <div class="field mb-4">
                <InputLabel for="aseguradora_id_edit" value="Aseguradora" />
                <Dropdown
                    id="aseguradora_id_edit"
                    v-model="form.aseguradora_id"
                    :options="aseguradoras"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione una Aseguradora"
                    filter
                />
            </div>
            <div class="field mb-4">
                <InputLabel
                    for="tipo_de_seguro_id_edit"
                    value="Tipo de Seguro"
                />
                <Dropdown
                    id="tipo_de_seguro_id_edit"
                    v-model="form.tipo_de_seguro_id"
                    :options="tiposSeguro"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione un Tipo"
                    filter
                />
            </div>
            <div class="field mb-4">
                <InputLabel
                    for="descripcion_corta_edit"
                    value="Descripción Corta"
                />
                <Textarea
                    id="descripcion_corta_edit"
                    v-model="form.descripcion_corta"
                    rows="3"
                />
            </div>

            <div class="flex gap-8 mb-4">
                <div class="field flex items-center gap-2">
                    <Checkbox
                        v-model="form.activo"
                        inputId="activo_edit"
                        :binary="true"
                    />
                    <label for="activo_edit">Activo (En sistema)</label>
                </div>
                <div class="field flex items-center gap-2">
                    <Checkbox
                        v-model="form.publicar_en_web"
                        inputId="publicar_en_web_edit"
                        :binary="true"
                    />
                    <label for="publicar_en_web_edit">Publicar (En Web)</label>
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
                    label="Actualizar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
