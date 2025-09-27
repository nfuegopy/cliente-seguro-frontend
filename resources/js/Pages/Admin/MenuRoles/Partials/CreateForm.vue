<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import Checkbox from "primevue/checkbox";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    menus: Array,
    roles: Array,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    rol_id: null,
    menu_id: null,
    permitir_listar: true,
    permitir_guardar: false,
    permitir_editar: false,
    permitir_eliminar: false,
});

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.post(route("admin.menuroles.store"), {
        onSuccess: () => {
            emit("success", "Permiso asignado exitosamente.");
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
        header="Asignar Permiso a Rol"
        :style="{ width: '40rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="rol_id" value="Rol" />
                <Dropdown
                    id="rol_id"
                    v-model="form.rol_id"
                    :options="roles"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione un Rol"
                    filter
                />
            </div>

            <div class="field mb-4">
                <InputLabel for="menu_id" value="Menú" />
                <Dropdown
                    id="menu_id"
                    v-model="form.menu_id"
                    :options="menus"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione un Menú"
                    filter
                />
            </div>

            <div class="field mt-6">
                <InputLabel
                    value="Permisos Específicos"
                    class="font-bold mb-2"
                />
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div class="flex items-center">
                        <Checkbox
                            v-model="form.permitir_listar"
                            inputId="permiso_listar"
                            :binary="true"
                        />
                        <label for="permiso_listar" class="ml-2">
                            Permitir Listar / Ver
                        </label>
                    </div>
                    <div class="flex items-center">
                        <Checkbox
                            v-model="form.permitir_guardar"
                            inputId="permiso_guardar"
                            :binary="true"
                        />
                        <label for="permiso_guardar" class="ml-2">
                            Permitir Guardar
                        </label>
                    </div>
                    <div class="flex items-center">
                        <Checkbox
                            v-model="form.permitir_editar"
                            inputId="permiso_editar"
                            :binary="true"
                        />
                        <label for="permiso_editar" class="ml-2">
                            Permitir Editar
                        </label>
                    </div>
                    <div class="flex items-center">
                        <Checkbox
                            v-model="form.permitir_eliminar"
                            inputId="permiso_eliminar"
                            :binary="true"
                        />
                        <label for="permiso_eliminar" class="ml-2">
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
                    label="Guardar Permiso"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
