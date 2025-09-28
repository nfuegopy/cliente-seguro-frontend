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
    departamento: Object,
    paises: Array,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre: "",
    pais_id: null,
});

watch(
    () => props.departamento,
    (newVal) => {
        if (newVal) {
            form.nombre = newVal.nombre;
            form.pais_id = newVal.pais_id;
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(
        route("admin.parametros.departamento.update", props.departamento.id),
        {
            onSuccess: () => {
                emit("success", "Departamento actualizado.");
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
        header="Editar Departamento"
        :style="{ width: '30rem' }"
    >
        <form v-if="departamento" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="nombre_edit" value="Nombre" />
                <InputText id="nombre_edit" v-model="form.nombre" />
            </div>
            <div class="field mb-4">
                <InputLabel for="pais_id_edit" value="País" />
                <Dropdown
                    id="pais_id_edit"
                    v-model="form.pais_id"
                    :options="paises"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione un País"
                    filter
                />
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
