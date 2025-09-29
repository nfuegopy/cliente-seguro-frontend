<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";

const emit = defineEmits(["update:visible", "success"]);
const props = defineProps({ visible: Boolean });

const form = useForm({
    nombre: "",
    nit: "",
    telefono: "",
    email: "",
    direccion: "",
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.negocio.referenciales.aseguradoras.store"), {
        onSuccess: () => {
            emit("success", "Aseguradora creada.");
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
        header="Crear Aseguradora"
        :style="{ width: '40rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="field mb-4 col-span-2">
                    <InputLabel for="nombre" value="Nombre de la Aseguradora" />
                    <InputText id="nombre" v-model="form.nombre" />
                </div>
                <div class="field mb-4">
                    <InputLabel for="nit" value="NIT" />
                    <InputText id="nit" v-model="form.nit" />
                </div>
                <div class="field mb-4">
                    <InputLabel for="telefono" value="Teléfono" />
                    <InputText id="telefono" v-model="form.telefono" />
                </div>
                <div class="field mb-4 col-span-2">
                    <InputLabel for="email" value="Email" />
                    <InputText id="email" v-model="form.email" type="email" />
                </div>
                <div class="field mb-4 col-span-2">
                    <InputLabel for="direccion" value="Dirección" />
                    <InputText id="direccion" v-model="form.direccion" />
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
                    label="Guardar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
