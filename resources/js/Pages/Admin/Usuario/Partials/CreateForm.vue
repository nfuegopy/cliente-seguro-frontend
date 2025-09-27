<script setup>
import { route } from "ziggy-js";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Password from "primevue/password";
import Divider from "primevue/divider";
import Calendar from "primevue/calendar";
import InputNumber from "primevue/inputnumber";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    visible: Boolean,
    roles: Array,
    tiposDocumento: Array,
});

const emit = defineEmits(["update:visible", "success"]);

const form = useForm({
    email: "",
    password: "",
    rol_id: null,
    persona: {
        nombre: "",
        apellido: "",
        telefono: "",
        direccion: "",
        ciudad_id: null,
        documentos: [
            { tipo_documento_id: null, numero: "", fecha_vencimiento: null },
        ],
    },
});

const addDocumento = () => {
    form.persona.documentos.push({
        tipo_documento_id: null,
        numero: "",
        fecha_vencimiento: null,
    });
};

const removeDocumento = (index) => {
    form.persona.documentos.splice(index, 1);
};

const closeModal = () => emit("update:visible", false);

// --- 1. FUNCIÓN PARA FORMATEAR FECHA ---
const formatDate = (date) => {
    if (!date) return null;
    const d = new Date(date);
    const year = d.getFullYear();
    const month = `0${d.getMonth() + 1}`.slice(-2);
    const day = `0${d.getDate()}`.slice(-2);
    return `${year}-${month}-${day}`; // Devuelve 'YYYY-MM-DD'
};

const submit = () => {
    // --- 2. CLONAMOS Y TRANSFORMAMOS LOS DATOS ANTES DE ENVIAR ---
    const dataToSubmit = { ...form.data() };

    dataToSubmit.persona.documentos = dataToSubmit.persona.documentos.map(
        (doc) => ({
            ...doc,
            fecha_vencimiento: formatDate(doc.fecha_vencimiento),
        })
    );

    // Enviamos una versión modificada del formulario
    form.transform(() => dataToSubmit).post(route("admin.usuario.store"), {
        onSuccess: () => {
            emit("success", "Usuario creado exitosamente.");
            closeModal();
            form.reset();
        },
    });
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Crear Nuevo Usuario"
        :style="{ width: '60rem' }"
    >
        <form @submit.prevent="submit" class="p-fluid mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="field">
                    <InputLabel for="email" value="Email" />
                    <InputText
                        id="email"
                        v-model="form.email"
                        :invalid="!!form.errors.email"
                    />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="field">
                    <InputLabel for="password" value="Contraseña" />
                    <Password
                        id="password"
                        v-model="form.password"
                        :invalid="!!form.errors.password"
                        toggleMask
                        :feedback="false"
                    />
                    <InputError :message="form.errors.password" />
                </div>
                <div class="field col-span-2">
                    <InputLabel for="rol_id" value="Rol" />
                    <Dropdown
                        id="rol_id"
                        v-model="form.rol_id"
                        :options="roles"
                        optionLabel="nombre"
                        optionValue="id"
                        placeholder="Seleccione un Rol"
                        filter
                        :invalid="!!form.errors.rol_id"
                    />
                    <InputError :message="form.errors.rol_id" />
                </div>
            </div>

            <Divider align="left" type="solid" class="my-6">
                <b>Datos de la Persona</b>
            </Divider>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="field">
                    <InputLabel for="nombre" value="Nombre" />
                    <InputText
                        id="nombre"
                        v-model="form.persona.nombre"
                        :invalid="!!form.errors['persona.nombre']"
                    />
                    <InputError :message="form.errors['persona.nombre']" />
                </div>
                <div class="field">
                    <InputLabel for="apellido" value="Apellido" />
                    <InputText
                        id="apellido"
                        v-model="form.persona.apellido"
                        :invalid="!!form.errors['persona.apellido']"
                    />
                    <InputError :message="form.errors['persona.apellido']" />
                </div>
                <div class="field">
                    <InputLabel for="telefono" value="Teléfono (Opcional)" />
                    <InputText id="telefono" v-model="form.persona.telefono" />
                </div>
                <div class="field">
                    <InputLabel for="direccion" value="Dirección (Opcional)" />
                    <InputText
                        id="direccion"
                        v-model="form.persona.direccion"
                    />
                </div>
                <div class="field col-span-2">
                    <InputLabel
                        for="ciudad_id"
                        value="ID de Ciudad (Manual/Opcional)"
                    />
                    <InputNumber
                        id="ciudad_id"
                        v-model="form.persona.ciudad_id"
                    />
                </div>
            </div>

            <div
                v-for="(doc, index) in form.persona.documentos"
                :key="index"
                class="grid grid-cols-12 gap-4 mt-4 items-center"
            >
                <div class="field col-span-4">
                    <InputLabel
                        :for="`tipo_doc_${index}`"
                        value="Tipo de Documento"
                    />
                    <Dropdown
                        :id="`tipo_doc_${index}`"
                        v-model="doc.tipo_documento_id"
                        :options="tiposDocumento"
                        optionLabel="nombre"
                        optionValue="id"
                        placeholder="Seleccione"
                        :invalid="
                            !!form.errors[
                                `persona.documentos.${index}.tipo_documento_id`
                            ]
                        "
                    />
                    <InputError
                        :message="
                            form.errors[
                                `persona.documentos.${index}.tipo_documento_id`
                            ]
                        "
                    />
                </div>
                <div class="field col-span-4">
                    <InputLabel :for="`numero_doc_${index}`" value="Número" />
                    <InputText
                        :id="`numero_doc_${index}`"
                        v-model="doc.numero"
                        :invalid="
                            !!form.errors[`persona.documentos.${index}.numero`]
                        "
                    />
                    <InputError
                        :message="
                            form.errors[`persona.documentos.${index}.numero`]
                        "
                    />
                </div>
                <div class="field col-span-3">
                    <InputLabel
                        :for="`vencimiento_doc_${index}`"
                        value="Vencimiento (Opcional)"
                    />
                    <Calendar
                        :id="`vencimiento_doc_${index}`"
                        v-model="doc.fecha_vencimiento"
                        dateFormat="dd/mm/yy"
                    />
                </div>
                <div class="col-span-1 pt-5">
                    <Button
                        icon="pi pi-trash"
                        severity="danger"
                        text
                        @click="removeDocumento(index)"
                        :disabled="form.persona.documentos.length === 1"
                    />
                </div>
            </div>
            <Button
                label="Añadir Documento"
                icon="pi pi-plus"
                severity="secondary"
                outlined
                @click="addDocumento"
                class="mt-4"
            />

            <div class="flex justify-end gap-2 mt-8">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    @click="closeModal"
                />
                <Button
                    type="submit"
                    label="Guardar Usuario"
                    :loading="form.processing"
                />
            </div>
        </form>
    </Dialog>
</template>
