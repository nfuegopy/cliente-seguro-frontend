<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import Editor from "primevue/editor";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    condicion: Object,
    productosSeguro: Array,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    producto_seguro_id: null,
    contenido: "",
    version: "",
    fecha_publicacion: null,
});

watch(
    () => props.condicion,
    (newVal) => {
        if (newVal) {
            form.producto_seguro_id = newVal.producto_seguro_id;
            form.contenido = newVal.contenido;
            form.version = newVal.version;
            form.fecha_publicacion = new Date(newVal.fecha_publicacion);
        }
    },
    { immediate: true, deep: true }
);

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
    form.transform(() => dataToSubmit).patch(
        route("admin.negocio.basescondiciones.update", props.condicion.id),
        {
            onSuccess: () => {
                emit("success", "Condición actualizada.");
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
        header="Editar Base y Condición"
        :style="{ width: '60vw' }"
    >
        <form v-if="condicion" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="field">
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
                <div class="field">
                    <InputLabel for="version_edit" value="Versión" />
                    <InputText id="version_edit" v-model="form.version" />
                </div>
                <div class="field">
                    <InputLabel
                        for="fecha_publicacion_edit"
                        value="Fecha de Publicación"
                    />
                    <Calendar
                        id="fecha_publicacion_edit"
                        v-model="form.fecha_publicacion"
                        dateFormat="dd/mm/yy"
                    />
                </div>
            </div>
            <div class="field">
                <InputLabel
                    for="contenido_edit"
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
                    label="Actualizar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
