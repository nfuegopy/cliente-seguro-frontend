<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    modelo: Object,
    marcas: Array,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre: "",
    marca_id: null,
});

watch(
    () => props.modelo,
    (newVal) => {
        if (newVal) {
            form.nombre = newVal.nombre;
            form.marca_id = newVal.marca.id; // La API anida el objeto, accedemos al id
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(
        route(
            "admin.negocio.parametros.vehiculomodelo.update",
            props.modelo.id
        ),
        {
            onSuccess: () => {
                emit("success", "Modelo actualizado.");
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
        header="Editar Modelo de Vehículo"
        :style="{ width: '30rem' }"
    >
        <form v-if="modelo" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="marca_id_edit" value="Marca" />
                <Dropdown
                    id="marca_id_edit"
                    v-model="form.marca_id"
                    :options="marcas"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione una Marca"
                    filter
                />
            </div>
            <div class="field mb-4">
                <InputLabel for="nombre_edit" value="Nombre del Modelo" />
                <InputText id="nombre_edit" v-model="form.nombre" />
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
