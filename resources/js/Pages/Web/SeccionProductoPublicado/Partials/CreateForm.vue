<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import InputNumber from "primevue/inputnumber";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    seccionesWeb: Array,
    productosSeguro: Array,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    seccion_web_id: null,
    producto_seguro_id: null,
    orden: 0,
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.web.seccion-producto-publicado.store"), {
        onSuccess: () => {
            emit("success", "Producto publicado.");
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
        header="Publicar Producto en Sección"
        :style="{ width: '40rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4 space-y-4">
            <div class="field">
                <InputLabel for="seccion_web_id" value="Sección Web" />
                <Dropdown
                    id="seccion_web_id"
                    v-model="form.seccion_web_id"
                    :options="seccionesWeb"
                    optionLabel="titulo"
                    optionValue="id"
                    placeholder="Seleccione una Sección"
                    filter
                />
            </div>

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
                <InputLabel for="orden" value="Orden" />
                <InputNumber id="orden" v-model="form.orden" />
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    @click="closeModal"
                />
                <Button
                    type="submit"
                    label="Guardar Publicación"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
