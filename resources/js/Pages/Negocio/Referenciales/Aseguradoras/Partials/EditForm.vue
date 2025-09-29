<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    aseguradora: Object,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre: "",
    nit: "",
    telefono: "",
    email: "",
    direccion: "",
});

watch(
    () => props.aseguradora,
    (newVal) => {
        if (newVal) {
            form.nombre = newVal.nombre;
            form.nit = newVal.nit;
            form.telefono = newVal.telefono;
            form.email = newVal.email;
            form.direccion = newVal.direccion;
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(
        route(
            "admin.negocio.referenciales.aseguradoras.update",
            props.aseguradora.id
        ),
        {
            onSuccess: () => {
                emit("success", "Aseguradora actualizada.");
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
        header="Editar Aseguradora"
        :style="{ width: '40rem' }"
    >
        <form v-if="aseguradora" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="field mb-4 col-span-2">
                    <InputLabel
                        for="nombre_edit"
                        value="Nombre de la Aseguradora"
                    />
                    <InputText id="nombre_edit" v-model="form.nombre" />
                </div>
                <div class="field mb-4">
                    <InputLabel for="nit_edit" value="NIT" />
                    <InputText id="nit_edit" v-model="form.nit" />
                </div>
                <div class="field mb-4">
                    <InputLabel for="telefono_edit" value="Teléfono" />
                    <InputText id="telefono_edit" v-model="form.telefono" />
                </div>
                <div class="field mb-4 col-span-2">
                    <InputLabel for="email_edit" value="Email" />
                    <InputText
                        id="email_edit"
                        v-model="form.email"
                        type="email"
                    />
                </div>
                <div class="field mb-4 col-span-2">
                    <InputLabel for="direccion_edit" value="Dirección" />
                    <InputText id="direccion_edit" v-model="form.direccion" />
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
                    label="Actualizar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
