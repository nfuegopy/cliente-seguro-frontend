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
    ciudad: Object,
    departamentos: Array,
    paises: Array,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre: "",
    departamento_id: null,
});

// --- LÓGICA DE FILTRADO ---
const selectedPaisId = ref(null);

const filteredDepartamentos = computed(() => {
    if (!selectedPaisId.value) return [];
    return props.departamentos.filter(
        (depto) => depto.pais_id === selectedPaisId.value
    );
});

watch(selectedPaisId, (newVal, oldVal) => {
    // Solo reseteamos si el cambio fue hecho por el usuario, no al cargar
    if (newVal !== oldVal && oldVal !== null) {
        form.departamento_id = null;
    }
});

watch(
    () => props.ciudad,
    (newCiudad) => {
        if (newCiudad) {
            form.nombre = newCiudad.nombre;
            form.departamento_id = newCiudad.departamento_id;

            // Al cargar, encontramos a qué país pertenece el departamento actual
            const deptoActual = props.departamentos.find(
                (d) => d.id === newCiudad.departamento_id
            );
            if (deptoActual) {
                selectedPaisId.value = deptoActual.pais_id;
            }
        }
    },
    { immediate: true, deep: true }
);
// --- FIN DE LÓGICA DE FILTRADO ---

const closeModal = () => emit("update:visible", false);

const submit = () => {
    form.patch(route("admin.parametros.ciudad.update", props.ciudad.id), {
        onSuccess: () => {
            emit("success", "Ciudad actualizada.");
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
        header="Editar Ciudad"
        :style="{ width: '30rem' }"
    >
        <form v-if="ciudad" @submit.prevent="submit" class="p-fluid mt-4">
            <div class="field mb-4">
                <InputLabel for="nombre_edit" value="Nombre" />
                <InputText id="nombre_edit" v-model="form.nombre" />
            </div>

            <div class="field mb-4">
                <InputLabel for="pais_filtro_edit" value="País (Filtro)" />
                <Dropdown
                    id="pais_filtro_edit"
                    v-model="selectedPaisId"
                    :options="paises"
                    optionLabel="nombre"
                    optionValue="id"
                    placeholder="Primero seleccione un País"
                    filter
                />
            </div>

            <div class="field mb-4">
                <InputLabel for="departamento_id_edit" value="Departamento" />
                <Dropdown
                    id="departamento_id_edit"
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
                    label="Actualizar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
