<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    publicacion: Object,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    orden: 0,
});

// Rellena el formulario cuando el prop 'publicacion' cambia
watch(
    () => props.publicacion,
    (newVal) => {
        if (newVal) {
            form.orden = newVal.orden;
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(
        route(
            "admin.web.seccion-producto-publicado.update",
            props.publicacion.id
        ),
        {
            onSuccess: () => {
                emit("success", "Orden actualizado.");
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
        header="Editar Orden de Publicación"
        :style="{ width: '40rem' }"
    >
        <form
            v-if="publicacion"
            @submit.prevent="submit"
            class="p-fluid mt-4 space-y-4"
        >
            <div class="field">
                <InputLabel value="Sección Web (No editable)" />
                <InputText :value="publicacion.seccionWeb.titulo" disabled />
            </div>

            <div class="field">
                <InputLabel value="Producto de Seguro (No editable)" />
                <InputText
                    :value="publicacion.productoSeguro.nombre_producto"
                    disabled
                />
            </div>

            <div class="field">
                <InputLabel for="orden_edit" value="Orden" />
                <InputNumber id="orden_edit" v-model="form.orden" />
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    @click="closeModal"
                />
                <Button
                    type="submit"
                    label="Actualizar Orden"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
