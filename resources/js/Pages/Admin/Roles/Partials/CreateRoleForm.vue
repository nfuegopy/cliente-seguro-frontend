<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";

// El componente emitirá un evento 'close' para que la página principal (Index.vue) cierre el modal
const emit = defineEmits(["close"]);

const form = useForm({
    nombre: "",
    descripcion: "",
});

const submit = () => {
    form.post(route("admin.roles.store"), {
        onSuccess: () => emit("close"), // Si la creación es exitosa, emite el evento para cerrar.
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex flex-col gap-2">
                <label for="nombre-create" class="font-semibold text-gray-700"
                    >Nombre del Rol</label
                >
                <InputText
                    id="nombre-create"
                    v-model="form.nombre"
                    required
                    autofocus
                />
                <small v-if="form.errors.nombre" class="text-red-600 mt-1">{{
                    form.errors.nombre
                }}</small>
            </div>
            <div class="flex flex-col gap-2">
                <label
                    for="descripcion-create"
                    class="font-semibold text-gray-700"
                    >Descripción</label
                >
                <Textarea
                    id="descripcion-create"
                    v-model="form.descripcion"
                    rows="3"
                />
                <small
                    v-if="form.errors.descripcion"
                    class="text-red-600 mt-1"
                    >{{ form.errors.descripcion }}</small
                >
            </div>
        </div>

        <div class="flex justify-end gap-2 p-4 border-t border-gray-200 mt-4">
            <Button
                label="Cancelar"
                severity="secondary"
                @click="emit('close')"
            />
            <Button
                type="submit"
                label="Guardar Rol"
                icon="pi pi-check"
                :loading="form.processing"
            />
        </div>
    </form>
</template>
