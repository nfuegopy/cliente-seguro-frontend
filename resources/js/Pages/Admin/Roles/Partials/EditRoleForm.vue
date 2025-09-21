<script setup>
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import { onMounted } from "vue";

const props = defineProps({
    role: Object, // Recibe el objeto del rol a editar
});

const emit = defineEmits(["close"]);

const form = useForm({
    nombre: "",
    descripcion: "",
});

// onMounted se asegura de que el formulario se llene con los datos del rol tan pronto como se muestre el modal
onMounted(() => {
    form.nombre = props.role.nombre;
    form.descripcion = props.role.descripcion;
});

const submit = () => {
    // Usamos el método PATCH de Inertia para actualizar
    form.patch(route("admin.roles.update", props.role.id), {
        onSuccess: () => emit("close"),
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex flex-col gap-2">
                <label for="nombre-edit" class="font-semibold text-gray-700"
                    >Nombre del Rol</label
                >
                <InputText
                    id="nombre-edit"
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
                    for="descripcion-edit"
                    class="font-semibold text-gray-700"
                    >Descripción</label
                >
                <Textarea
                    id="descripcion-edit"
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
                label="Actualizar Rol"
                icon="pi pi-check"
                :loading="form.processing"
            />
        </div>
    </form>
</template>
