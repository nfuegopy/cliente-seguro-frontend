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
    menu: Object,
    grupoMenus: Array,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    _method: "PUT",
    nombre: "",
    url: "",
    icono: "",
    grupo_menu_id: null,
});

const closeModal = () => {
    emit("update:visible", false);
};

watch(
    () => props.menu,
    (newMenu) => {
        if (newMenu) {
            // --- LOG DETALLADO ---
            console.log(
                "LOG (EditForm): Cargando datos en el formulario.",
                newMenu
            );
            form.nombre = newMenu.nombre;
            form.url = newMenu.url;
            form.icono = newMenu.icono;
            form.grupo_menu_id = newMenu.grupo_menu_id;
            form.clearErrors();
        }
    },
    { immediate: true, deep: true }
);

watch(
    () => props.visible,
    (newVal) => {
        if (!newVal) {
            form.clearErrors();
        }
    }
);

const submit = () => {
    if (!props.menu) return;

    // --- LOG DETALLADO ---
    console.log("LOG (EditForm): Intentando actualizar. Payload:", form.data());

    form.post(route("admin.menu.update", props.menu.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log("LOG (EditForm): Actualización exitosa.");
            emit("success", "Menú actualizado exitosamente.");
            closeModal();
        },
        onError: (errors) => {
            // --- LOG DETALLADO DE ERRORES ---
            console.error(
                "LOG (EditForm): Error en la actualización. Errores recibidos:",
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
        header="Editar Menú"
        :style="{ width: '50vw' }"
    >
        <form v-if="menu" @submit.prevent="submit">
            <div class="p-fluid">
                <div class="field mb-4">
                    <InputLabel for="nombre_edit" value="Nombre del Menú" />
                    <InputText
                        id="nombre_edit"
                        v-model="form.nombre"
                        :class="{ 'p-invalid': form.errors.nombre }"
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.nombre" class="mt-2" />
                </div>

                <div class="field mb-4">
                    <InputLabel
                        for="url_edit"
                        value="Ruta (Nombre de ruta Ziggy/Laravel)"
                    />
                    <InputText
                        id="url_edit"
                        v-model="form.url"
                        :class="{ 'p-invalid': form.errors.url }"
                        autocomplete="off"
                    />
                    <InputError :message="form.errors.url" class="mt-2" />
                </div>

                <div class="field mb-4">
                    <InputLabel for="icono_edit" value="Icono" />
                    <Dropdown
                        id="icono_edit"
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
                    <InputLabel for="grupo_menu_id_edit" value="Grupo Menú" />
                    <Dropdown
                        id="grupo_menu_id_edit"
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
                    label="Actualizar"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
