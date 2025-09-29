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
    marca: Object,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre: "",
});

watch(
    () => props.marca,
    (newVal) => {
        if (newVal) {
            form.nombre = newVal.nombre;
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(
        route("admin.negocio.parametros.vehiculomarca.update", props.marca.id),
        {
            onSuccess: () => {
                emit("success", "Marca actualizada.");
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
        header="Editar Marca de Vehículo"
        :style="{ width: '30rem' }"
    >
        <form v-if="marca" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="nombre_edit" value="Nombre" />
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
