<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import FileUpload from "primevue/fileupload";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    visible: Boolean,
    seccion: Object,
});
const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    _method: "PATCH",
    titulo: "",
    descripcion: "",
    orden: 0,
    enlace_url: "",
    texto_boton: "",
    imagen: null,
});

watch(
    () => props.seccion,
    (newVal) => {
        if (newVal) {
            form.titulo = newVal.titulo;
            form.descripcion = newVal.descripcion;
            form.orden = newVal.orden;
            form.enlace_url = newVal.enlace_url;
            form.texto_boton = newVal.texto_boton;
            form.imagen = null;
        }
    },
    { immediate: true, deep: true }
);

const closeModal = () => emit("update:visible", false);

const onFileSelect = (event) => {
    form.imagen = event.files[0];
};

const submit = () => {
    console.log("Intentando actualizar la sección ID:", props.seccion.id);
    console.log("Datos que se enviarán:", form.data());
    form.post(route("admin.web.secciones-web.update", props.seccion.id), {
        onSuccess: () => {
            emit("success", "Sección actualizada.");
            closeModal();
        },
        onError: (errors) => {
            console.error("Error desde Inertia al actualizar:", errors);
        },
    });
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Editar Sección Web"
        :style="{ width: '50rem' }"
    >
        <form
            v-if="seccion"
            @submit.prevent="submit"
            class="p-fluid mt-4 space-y-6"
        >
            <div class="field">
                <InputLabel for="titulo_edit" value="Título" />
                <InputText
                    id="titulo_edit"
                    v-model="form.titulo"
                    :invalid="!!form.errors.titulo"
                />
                <InputError :message="form.errors.titulo" />
            </div>
            <div class="field">
                <InputLabel for="descripcion_edit" value="Descripción" />
                <Textarea
                    id="descripcion_edit"
                    v-model="form.descripcion"
                    rows="4"
                    :invalid="!!form.errors.descripcion"
                />
                <InputError :message="form.errors.descripcion" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="field">
                    <InputLabel for="orden_edit" value="Orden" />
                    <InputNumber id="orden_edit" v-model="form.orden" />
                </div>
                <div class="field">
                    <InputLabel
                        for="texto_boton_edit"
                        value="Texto del Botón"
                    />
                    <InputText
                        id="texto_boton_edit"
                        v-model="form.texto_boton"
                    />
                </div>
                <div class="field">
                    <InputLabel
                        for="enlace_url_edit"
                        value="URL del Enlace (Nombre de Ruta)"
                    />
                    <InputText
                        id="enlace_url_edit"
                        v-model="form.enlace_url"
                        placeholder="ej: seguros.vehicular"
                    />
                </div>
            </div>
            <div class="field">
                <InputLabel
                    for="imagen_edit"
                    value="Cambiar Imagen (Opcional)"
                />
                <img
                    :src="seccion.imagen_url"
                    :alt="seccion.titulo"
                    class="w-48 h-auto object-cover rounded-md my-2"
                />
                <FileUpload
                    name="imagen"
                    @select="onFileSelect"
                    :multiple="false"
                    accept="image/*"
                    :maxFileSize="5000000"
                    chooseLabel="Seleccionar Nueva Imagen"
                    :class="{ 'p-invalid': !!form.errors.imagen }"
                >
                    <template #empty>
                        <p>
                            Arrastra una nueva imagen para reemplazar la actual.
                        </p>
                    </template>
                </FileUpload>
                <InputError :message="form.errors.imagen" />
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
