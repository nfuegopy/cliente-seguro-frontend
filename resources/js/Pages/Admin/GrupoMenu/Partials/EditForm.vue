<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown"; // <-- 1. Importar Dropdown
import { usePrimeIcons } from "@/Composables/usePrimeIcons"; // <-- 2. Importar íconos
import { onMounted } from "vue";

const props = defineProps({ grupo: Object });
const emit = defineEmits(["close"]);
const { icons } = usePrimeIcons(); // <-- 3. Obtener la lista de íconos

const form = useForm({
    nombre: "",
    descripcion: "",
    icono: "",
});

onMounted(() => {
    form.nombre = props.grupo.nombre;
    form.descripcion = props.grupo.descripcion;
    form.icono = props.grupo.icono;
});

const submit = () => {
    form.patch(route("admin.grupo-menu.update", props.grupo.id), {
        onSuccess: () => emit("close"),
    });
};
</script>
<template>
    <form @submit.prevent="submit">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex flex-col gap-2">
                <label for="nombre-edit" class="font-semibold text-gray-700"
                    >Nombre del Grupo</label
                >
                <InputText
                    id="nombre-edit"
                    v-model="form.nombre"
                    required
                    autofocus
                />
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
            </div>
            <div class="flex flex-col gap-2">
                <label for="icono-edit" class="font-semibold text-gray-700"
                    >Icono</label
                >
                <Dropdown
                    id="icono-edit"
                    v-model="form.icono"
                    :options="icons"
                    filter
                    placeholder="Seleccione un ícono"
                >
                    <template #value="slotProps">
                        <div v-if="slotProps.value" class="flex items-center">
                            <i :class="slotProps.value" class="mr-2"></i>
                            <div>{{ slotProps.value }}</div>
                        </div>
                        <span v-else>
                            {{ slotProps.placeholder }}
                        </span>
                    </template>
                    <template #option="slotProps">
                        <div class="flex items-center">
                            <i :class="slotProps.option" class="mr-2"></i>
                            <div>{{ slotProps.option }}</div>
                        </div>
                    </template>
                </Dropdown>
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
                label="Actualizar"
                icon="pi pi-check"
                :loading="form.processing"
            />
        </div>
    </form>
</template>
