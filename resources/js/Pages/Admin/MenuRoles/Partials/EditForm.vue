<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    permiso: Object,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    permitir_listar: false,
    permitir_guardar: false,
    permitir_editar: false,
    permitir_eliminar: false,
});

watch(
    () => props.permiso,
    (newPermiso) => {
        if (newPermiso) {
            form.defaults(newPermiso); // Carga todos los valores iniciales
            form.reset(); // Aplica los valores iniciales al formulario
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    // Usamos patch para que solo envíe los campos que han cambiado.
    form.patch(route("admin.menuroles.update", props.permiso.id), {
        onSuccess: () => {
            emit("success", "Permiso actualizado exitosamente.");
            closeModal();
        },
    });
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Editar Permisos"
        :style="{ width: '40rem' }"
    >
        <form v-if="permiso" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="mb-6 bg-gray-100 p-4 rounded-lg">
                <p><strong>Rol:</strong> {{ permiso.rol?.nombre }}</p>
                <p><strong>Menú:</strong> {{ permiso.menu?.nombre }}</p>
            </div>

            <div class="field mt-2">
                <InputLabel
                    value="Permisos Específicos"
                    class="font-bold mb-2"
                />
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div class="flex items-center">
                        <Checkbox
                            v-model="form.permitir_listar"
                            inputId="permiso_listar_edit"
                            :binary="true"
                        />
                        <label for="permiso_listar_edit" class="ml-2">
                            Permitir Listar / Ver
                        </label>
                    </div>
                    <div class="flex items-center">
                        <Checkbox
                            v-model="form.permitir_guardar"
                            inputId="permiso_guardar_edit"
                            :binary="true"
                        />
                        <label for="permiso_guardar_edit" class="ml-2">
                            Permitir Guardar
                        </label>
                    </div>
                    <div class="flex items-center">
                        <Checkbox
                            v-model="form.permitir_editar"
                            inputId="permiso_editar_edit"
                            :binary="true"
                        />
                        <label for="permiso_editar_edit" class="ml-2">
                            Permitir Editar
                        </label>
                    </div>
                    <div class="flex items-center">
                        <Checkbox
                            v-model="form.permitir_eliminar"
                            inputId="permiso_eliminar_edit"
                            :binary="true"
                        />
                        <label for="permiso_eliminar_edit" class="ml-2">
                            Permitir Eliminar
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-8">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    @click="closeModal"
                />
                <Button
                    type="submit"
                    label="Actualizar Permisos"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
