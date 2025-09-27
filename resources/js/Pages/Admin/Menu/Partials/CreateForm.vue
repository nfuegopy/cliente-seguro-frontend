<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import { useToast } from "primevue/usetoast";
import { usePrimeIcons } from "@/Composables/usePrimeIcons";

const toast = useToast();
const { icons } = usePrimeIcons();

const props = defineProps({
    visible: Boolean,
    grupoMenus: Array,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    nombre: "",
    url: "",
    icono: "",
    grupo_menu_id: null,
});

watch(
    () => props.visible,
    (newVal) => {
        if (newVal) {
            console.log(
                "LOG (CreateForm): Modal abierto. Reseteando formulario."
            );
            form.reset();
            form.clearErrors();
        }
    }
);

const closeModal = () => {
    emit("update:visible", false);
};

const submit = () => {
    // --- LOG DETALLADO ---
    console.log("LOG (CreateForm): Intentando enviar. Payload:", form.data());

    form.post(route("admin.menu.store"), {
        preserveScroll: true,
        onSuccess: () => {
            console.log("LOG (CreateForm): Envío exitoso.");
            emit("success", "Menú creado exitosamente.");
            closeModal();
        },
        onError: (errors) => {
            // --- LOG DETALLADO DE ERRORES ---
            console.error(
                "LOG (CreateForm): Error en el envío. Errores recibidos:",
                errors
            );
            if (Object.keys(errors).length > 0) {
                toast.add({
                    severity: "error",
                    summary: "Error de Validación",
                    detail: "Por favor, corrija los errores en el formulario.",
                    life: 3000,
                });
            }
        },
    });
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Crear Nuevo Menú"
        :style="{ width: '50vw' }"
    >
        <form @submit.prevent="submit">
            <div class="p-fluid">
                <div class="field mb-4">
                    <InputLabel for="nombre" value="Nombre del Menú" />
                    <InputText
                        id="nombre"
                        v-model="form.nombre"
                        :class="{ 'p-invalid': form.errors.nombre }"
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.nombre" class="mt-2" />
                </div>

                <div class="field mb-4">
                    <InputLabel
                        for="url"
                        value="Ruta (Nombre de ruta Ziggy/Laravel)"
                    />
                    <InputText
                        id="url"
                        v-model="form.url"
                        :class="{ 'p-invalid': form.errors.url }"
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.url" class="mt-2" />
                </div>

                <div class="field mb-4">
                    <InputLabel for="icono" value="Icono" />
                    <Dropdown
                        id="icono"
                        v-model="form.icono"
                        :options="icons"
                        filter
                        placeholder="Seleccione un ícono"
                        :class="{ 'p-invalid': form.errors.icono }"
                    >
                        <template #value="slotProps">
                            <div
                                v-if="slotProps.value"
                                class="flex items-center"
                            >
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
                    <InputError :message="form.errors.icono" class="mt-2" />
                </div>

                <div class="field mb-4">
                    <InputLabel for="grupo_menu_id" value="Grupo Menú" />
                    <Dropdown
                        id="grupo_menu_id"
                        v-model="form.grupo_menu_id"
                        :options="grupoMenus"
                        optionLabel="nombre"
                        optionValue="id"
                        placeholder="Seleccione un Grupo de Menú"
                        :class="{ 'p-invalid': form.errors.grupo_menu_id }"
                        :filter="true"
                    />
                    <InputError
                        :message="form.errors.grupo_menu_id"
                        class="mt-2"
                    />
                </div>
            </div>

            <div class="flex justify-content-end gap-2 mt-5">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    @click="closeModal"
                    :disabled="form.processing"
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
