<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import InputNumber from "primevue/inputnumber";
import Checkbox from "primevue/checkbox";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    productosSeguro: Array,
    camposFormulario: Array,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    producto_seguro_id: null,
    campo_formulario_id: null,
    es_requerido: true,
    orden: 0,
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.formularios.producto-formulario-campos.store"), {
        onSuccess: () => {
            emit("success", "Campo asignado.");
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
        header="Asignar Campo a Producto"
        :style="{ width: '40rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4 space-y-4">
            <div class="field">
                <InputLabel
                    for="producto_seguro_id"
                    value="Producto de Seguro"
                />
                <Dropdown
                    id="producto_seguro_id"
                    v-model="form.producto_seguro_id"
                    :options="productosSeguro"
                    optionLabel="nombre_producto"
                    optionValue="id"
                    placeholder="Seleccione un Producto"
                    filter
                />
            </div>

            <div class="field">
                <InputLabel
                    for="campo_formulario_id"
                    value="Campo de Formulario"
                />
                <Dropdown
                    id="campo_formulario_id"
                    v-model="form.campo_formulario_id"
                    :options="camposFormulario"
                    optionLabel="etiqueta"
                    optionValue="id"
                    placeholder="Seleccione un Campo"
                    filter
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="field">
                    <InputLabel for="orden" value="Orden" />
                    <InputNumber id="orden" v-model="form.orden" />
                </div>
                <div class="field flex items-center gap-2 pt-5">
                    <Checkbox
                        v-model="form.es_requerido"
                        inputId="es_requerido"
                        :binary="true"
                    />
                    <label for="es_requerido">Es Requerido</label>
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
                    label="Guardar Asignación"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
