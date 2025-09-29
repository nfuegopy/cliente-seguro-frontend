<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import InputNumber from "primevue/inputnumber";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    nivel: Object,
    productosSeguro: Array,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    producto_seguro_id: null,
    nombre_nivel: "",
    descripcion: "",
    prima_anual_base: 0,
});

watch(
    () => props.nivel,
    (newVal) => {
        if (newVal) {
            form.producto_seguro_id = newVal.producto_seguro_id;
            form.nombre_nivel = newVal.nombre_nivel;
            form.descripcion = newVal.descripcion;
            form.prima_anual_base = parseFloat(newVal.prima_anual_base);
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(route("admin.negocio.nivelescobertura.update", props.nivel.id), {
        onSuccess: () => {
            emit("success", "Nivel actualizado.");
            closeModal();
        },
    });
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Editar Nivel de Cobertura"
        :style="{ width: '40rem' }"
    >
        <form v-if="nivel" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel
                    for="producto_seguro_id_edit"
                    value="Producto de Seguro"
                />
                <Dropdown
                    id="producto_seguro_id_edit"
                    v-model="form.producto_seguro_id"
                    :options="productosSeguro"
                    optionLabel="nombre_producto"
                    optionValue="id"
                    placeholder="Seleccione un Producto"
                    filter
                />
            </div>
            <div class="field mb-4">
                <InputLabel
                    for="nombre_nivel_edit"
                    value="Nombre del Nivel (Ej: Básico, Premium, etc.)"
                />
                <InputText id="nombre_nivel_edit" v-model="form.nombre_nivel" />
            </div>
            <div class="field mb-4">
                <InputLabel
                    for="prima_anual_base_edit"
                    value="Prima Anual Base"
                />
                <InputNumber
                    id="prima_anual_base_edit"
                    v-model="form.prima_anual_base"
                />
            </div>
            <div class="field mb-4">
                <InputLabel
                    for="descripcion_edit"
                    value="Descripción (Opcional)"
                />
                <Textarea
                    id="descripcion_edit"
                    v-model="form.descripcion"
                    rows="3"
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
