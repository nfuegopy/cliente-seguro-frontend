<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import InputNumber from "primevue/inputnumber";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    productosSeguro: Array,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    producto_seguro_id: null,
    nombre_nivel: "",
    descripcion: "",
    prima_anual_base: 0,
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.negocio.nivelescobertura.store"), {
        onSuccess: () => {
            emit("success", "Nivel creado.");
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
        header="Crear Nivel de Cobertura"
        :style="{ width: '40rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
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
            <div class="field mb-4">
                <InputLabel
                    for="nombre_nivel"
                    value="Nombre del Nivel (Ej: Básico, Premium, etc.)"
                />
                <InputText id="nombre_nivel" v-model="form.nombre_nivel" />
            </div>
            <div class="field mb-4">
                <InputLabel for="prima_anual_base" value="Prima Anual Base" />
                <InputNumber
                    id="prima_anual_base"
                    v-model="form.prima_anual_base"
                />
            </div>
            <div class="field mb-4">
                <InputLabel for="descripcion" value="Descripción (Opcional)" />
                <Textarea
                    id="descripcion"
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
                    label="Guardar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
