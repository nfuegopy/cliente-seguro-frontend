<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Checkbox from "primevue/checkbox";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    asignacion: Object,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    es_requerido: true,
    orden: 0,
});

// Rellena el formulario cuando el prop 'asignacion' cambia
watch(
    () => props.asignacion,
    (newVal) => {
        if (newVal) {
            form.es_requerido = newVal.es_requerido;
            form.orden = newVal.orden;
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(
        route(
            "admin.formularios.producto-formulario-campos.update",
            props.asignacion.id
        ),
        {
            onSuccess: () => {
                emit("success", "Asignación actualizada.");
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
        header="Editar Asignación"
        :style="{ width: '40rem' }"
    >
        <form
            v-if="asignacion"
            @submit.prevent="submit"
            class="p-fluid mt-4 space-y-4"
        >
            <div class="field">
                <InputLabel value="Producto de Seguro (No editable)" />
                <InputText
                    :value="asignacion.productoSeguro.nombre_producto"
                    disabled
                />
            </div>

            <div class="field">
                <InputLabel value="Campo de Formulario (No editable)" />
                <InputText
                    :value="asignacion.campoFormulario.etiqueta"
                    disabled
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="field">
                    <InputLabel for="orden_edit" value="Orden" />
                    <InputNumber id="orden_edit" v-model="form.orden" />
                </div>
                <div class="field flex items-center gap-2 pt-5">
                    <Checkbox
                        v-model="form.es_requerido"
                        inputId="es_requerido_edit"
                        :binary="true"
                    />
                    <label for="es_requerido_edit">Es Requerido</label>
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
