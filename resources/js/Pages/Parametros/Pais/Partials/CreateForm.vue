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
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.parametros.pais.store"), {
        onSuccess: () => {
            emit("success", "País creado.");
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
        header="Crear País"
        :style="{ width: '30rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
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
