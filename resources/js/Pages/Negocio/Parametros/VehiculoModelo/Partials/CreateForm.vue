<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    marcas: Array,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre: "",
    marca_id: null,
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.negocio.parametros.vehiculomodelo.store"), {
        onSuccess: () => {
            emit("success", "Modelo creado.");
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
        header="Crear Modelo de Vehículo"
        :style="{ width: '30rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="marca_id" value="Marca" />
                <Dropdown
                    id="marca_id"
                    v-model="form.marca_id"
                    :options="marcas"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione una Marca"
                    filter
                />
            </div>
            <div class="field mb-4">
                <InputLabel for="nombre" value="Nombre del Modelo" />
                <InputText id="nombre" v-model="form.nombre" />
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
