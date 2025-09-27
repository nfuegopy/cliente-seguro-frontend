<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Password from "primevue/password";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    visible: Boolean,
    usuario: Object,
    roles: Array,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    email: "",
    rol_id: null,
    password: "", // El campo para la nueva contraseña
});

// Observador para llenar el formulario cuando se selecciona un usuario
watch(
    () => props.usuario,
    (newUsuario) => {
        if (newUsuario) {
            form.email = newUsuario.email;
            form.rol_id = newUsuario.rol_id;
            form.password = ""; // Siempre se resetea por seguridad
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const submit = () => {
    // Se usa el método PATCH para actualizar
    form.patch(route("admin.usuario.update", props.usuario.id), {
        onSuccess: () => {
            emit("success", "Usuario actualizado exitosamente.");
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
        header="Editar Usuario"
        :style="{ width: '40rem' }"
    >
        <form v-if="usuario" @submit.prevent="submit" class="p-fluid mt-4">
            <div
                class="mb-4 bg-gray-100 dark:bg-gray-700 p-3 rounded-lg text-center"
            >
                <p>
                    Editando a:
                    <strong class="dark:text-white"
                        >{{ usuario.persona?.nombre }}
                        {{ usuario.persona?.apellido }}</strong
                    >
                </p>
            </div>

            <div class="field">
                <InputLabel for="email_edit" value="Email" />
                <InputText
                    id="email_edit"
                    v-model="form.email"
                    :invalid="!!form.errors.email"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="field mt-4">
                <InputLabel for="rol_id_edit" value="Rol" />
                <Dropdown
                    id="rol_id_edit"
                    v-model="form.rol_id"
                    :options="roles"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione un Rol"
                    filter
                    :invalid="!!form.errors.rol_id"
                />
                <InputError :message="form.errors.rol_id" />
            </div>

            <div class="field mt-4">
                <InputLabel for="password_edit" value="Nueva Contraseña" />
                <Password
                    id="password_edit"
                    v-model="form.password"
                    placeholder="Dejar en blanco para no cambiar"
                    :invalid="!!form.errors.password"
                    toggleMask
                    :feedback="false"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="flex justify-end gap-2 mt-8">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    @click="closeModal"
                />
                <Button
                    type="submit"
                    label="Actualizar Usuario"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
