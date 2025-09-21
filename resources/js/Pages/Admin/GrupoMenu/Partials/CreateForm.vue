<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";

const emit = defineEmits(["close"]);

const form = useForm({
    nombre: "",
    descripcion: "",
    icono: "",
});

const submit = () => {
    form.post(route("admin.grupo-menu.store"), {
        onSuccess: () => emit("close"),
    });
};
</script>
<template>
    <form @submit.prevent="submit">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex flex-col gap-2">
                <label for="nombre" class="font-semibold text-gray-700"
                    >Nombre del Grupo</label
                >
                <InputText
                    id="nombre"
                    v-model="form.nombre"
                    required
                    autofocus
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="descripcion" class="font-semibold text-gray-700"
                    >Descripción</label
                >
                <Textarea
                    id="descripcion"
                    v-model="form.descripcion"
                    rows="3"
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="icono" class="font-semibold text-gray-700"
                    >Icono (Ej: pi pi-cog)</label
                >
                <InputText id="icono" v-model="form.icono" />
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
                label="Guardar"
                icon="pi pi-check"
                :loading="form.processing"
            />
        </div>
    </form>
</template>
