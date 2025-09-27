<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";

const emit = defineEmits(["update:visible", "success"]);
const props = defineProps({ visible: Boolean });

// CAMBIO: El formulario ahora usa 'nombre'.
const form = useForm({
    codigo: "",
    nombre: "",
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.parametros.tipodocumento.store"), {
        onSuccess: () => {
            emit("success", "Tipo de Documento creado.");
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
        header="Crear Tipo de Documento"
        :style="{ width: '30rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="codigo" value="Código" />
                <InputText id="codigo" v-model="form.codigo" />
            </div>
            <div class="field mb-4">
                <InputLabel for="nombre" value="Nombre" />
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
