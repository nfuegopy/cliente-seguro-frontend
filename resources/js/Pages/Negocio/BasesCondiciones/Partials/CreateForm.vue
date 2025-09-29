<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import Editor from "primevue/editor"; // <-- IMPORTANTE: Importar el Editor
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    productosSeguro: Array,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    producto_seguro_id: null,
    contenido: "",
    version: "1.0",
    fecha_publicacion: new Date(),
});

const formatDate = (date) => {
    if (!date) return null;
    const d = new Date(date);
    const year = d.getFullYear();
    const month = `0${d.getMonth() + 1}`.slice(-2);
    const day = `0${d.getDate()}`.slice(-2);
    return `${year}-${month}-${day}`;
};

const closeModal = () => emit("update:visible", false);

const submit = () => {
    const dataToSubmit = {
        ...form.data(),
        fecha_publicacion: formatDate(form.fecha_publicacion),
    };

    form.transform(() => dataToSubmit).post(
        route("admin.negocio.basescondiciones.store"),
        {
            onSuccess: () => {
                emit("success", "Condición creada.");
                closeModal();
                form.reset();
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
        header="Crear Base y Condición"
        :style="{ width: '60vw' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
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
                    <InputLabel for="version" value="Versión" />
                    <InputText id="version" v-model="form.version" />
                </div>
                <div class="field">
                    <InputLabel
                        for="fecha_publicacion"
                        value="Fecha de Publicación"
                    />
                    <Calendar
                        id="fecha_publicacion"
                        v-model="form.fecha_publicacion"
                        dateFormat="dd/mm/yy"
                    />
                </div>
            </div>
            <div class="field">
                <InputLabel
                    for="contenido"
                    value="Contenido de las Bases y Condiciones"
                    class="mb-2"
                />
                <Editor v-model="form.contenido" editorStyle="height: 320px" />
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
