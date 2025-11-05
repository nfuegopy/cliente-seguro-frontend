<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import FileUpload from "primevue/fileupload";
import Checkbox from "primevue/checkbox"; // <-- 1. IMPORTAR CHECKBOX
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const emit = defineEmits(["update:visible", "success"]);
const props = defineProps({ visible: Boolean });

const form = useForm({
    titulo: "",
    descripcion: "",
    orden: 0,
    activo: true, // <-- 2. AÑADIR CAMPO ACTIVO
    // enlace_url: "", // <-- 3. ELIMINAR ENLACE_URL
    texto_boton: "Ver Detalles",
    imagen: null,
});

const closeModal = () => emit("update:visible", false);

const onFileSelect = (event) => {
    form.imagen = event.files[0];
};

const submit = () => {
    console.log("Intentando crear una nueva sección...");
    console.log("Datos que se enviarán:", form.data());
    form.post(route("admin.web.secciones-web.store"), {
        onSuccess: () => {
            emit("success", "Sección creada exitosamente.");
            closeModal();
            form.reset();
        },
        onError: (errors) => {
            console.error("Error desde Inertia al crear:", errors);
        },
    });
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Crear Nueva Sección Web"
        :style="{ width: '50rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4 space-y-6">
            <div class="field">
                <InputLabel for="titulo" value="Título" />
                <InputText
                    id="titulo"
                    v-model="form.titulo"
                    :invalid="!!form.errors.titulo"
                />
                <InputError :message="form.errors.titulo" />
            </div>
            <div class="field">
                <InputLabel for="descripcion" value="Descripción" />
                <Textarea
                    id="descripcion"
                    v-model="form.descripcion"
                    rows="4"
                    :invalid="!!form.errors.descripcion"
                />
                <InputError :message="form.errors.descripcion" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="field">
                    <InputLabel for="orden" value="Orden" />
                    <InputNumber id="orden" v-model="form.orden" />
                </div>
                <div class="field">
                    <InputLabel for="texto_boton" value="Texto del Botón" />
                    <InputText id="texto_boton" v-model="form.texto_boton" />
                </div>
            </div>
            <div class="field">
                <InputLabel for="imagen" value="Imagen" />
                <FileUpload
                    name="imagen"
                    @select="onFileSelect"
                    :multiple="false"
                    accept="image/*"
                    :maxFileSize="5000000"
                    chooseLabel="Seleccionar Imagen"
                    :class="{ 'p-invalid': !!form.errors.imagen }"
                >
                    <template #empty>
                        <p>Arrastra y suelta la imagen aquí.</p>
                    </template>
                </FileUpload>
                <InputError :message="form.errors.imagen" />
            </div>

            <div class="field flex items-center gap-2">
                <Checkbox
                    v-model="form.activo"
                    inputId="activo_create"
                    :binary="true"
                />
                <label for="activo_create">Publicar en la web</label>
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
