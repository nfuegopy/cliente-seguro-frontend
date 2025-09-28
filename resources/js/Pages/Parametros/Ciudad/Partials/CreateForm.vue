<script setup>
import { ref, computed, watch } from "vue"; // <-- Importa ref, computed y watch
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    visible: Boolean,
    departamentos: Array,
    paises: Array, // <-- Recibimos países
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre: "",
    departamento_id: null,
});

// --- LÓGICA DE FILTRADO ---
const selectedPaisId = ref(null);

// Un computed property que recalcula la lista de deptos cuando el país cambia
const filteredDepartamentos = computed(() => {
    if (!selectedPaisId.value) {
        return []; // Si no hay país seleccionado, la lista está vacía
    }
    // Filtramos la lista completa de departamentos
    return props.departamentos.filter(
        (depto) => depto.pais_id === selectedPaisId.value
    );
});

// Un "observador" que resetea el valor del depto si el país cambia
watch(selectedPaisId, () => {
    form.departamento_id = null; // Resetea el departamento para evitar inconsistencias
});
// --- FIN DE LÓGICA DE FILTRADO ---

const closeModal = () => {
    emit("update:visible", false);
    selectedPaisId.value = null; // Limpia el estado al cerrar
    form.reset();
};

const submit = () => {
    form.post(route("admin.parametros.ciudad.store"), {
        onSuccess: () => {
            emit("success", "Ciudad creada.");
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
        header="Crear Ciudad"
        :style="{ width: '30rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="nombre" value="Nombre" />
                <InputText id="nombre" v-model="form.nombre" />
            </div>

            <div class="field mb-4">
                <InputLabel for="pais_filtro" value="País (Filtro)" />
                <Dropdown
                    id="pais_filtro"
                    v-model="selectedPaisId"
                    :options="paises"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Primero seleccione un País"
                    filter
                />
            </div>

            <div class="field mb-4">
                <InputLabel for="departamento_id" value="Departamento" />
                <Dropdown
                    id="departamento_id"
                    v-model="form.departamento_id"
                    :options="filteredDepartamentos"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Seleccione un Departamento"
                    filter
                    :disabled="!selectedPaisId"
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
                    label="Guardar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
