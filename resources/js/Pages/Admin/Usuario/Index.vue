<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";

import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Tag from "primevue/tag";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

import CreateForm from "./Partials/CreateForm.vue";
import EditForm from "./Partials/EditForm.vue";

const props = defineProps({
    usuarios: Array,
    roles: Array,
    tiposDocumento: Array,
});

const confirm = useConfirm();
const toast = useToast();

// --- 1. NUEVO: Estado para controlar las filas expandidas ---
const expandedRows = ref([]);

const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingUsuario = ref(null);

const openEditDialog = (usuario) => {
    editingUsuario.value = usuario;
    showEditDialog.value = true;
};

const handleDelete = (usuario) => {
    confirm.require({
        message: `¿Está seguro de eliminar al usuario "${usuario.email}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(route("admin.usuario.destroy", usuario.id), {
                onSuccess: () =>
                    toast.add({
                        severity: "success",
                        summary: "Éxito",
                        detail: "Usuario eliminado.",
                        life: 3000,
                    }),
            });
        },
    });
};

const handleSuccess = (message) => {
    toast.add({
        severity: "success",
        summary: "Éxito",
        detail: message,
        life: 3000,
    });
    router.reload({ only: ["usuarios"] });
};
</script>

<template>
    <Head title="Gestión de Usuarios" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Gestión de Usuarios
                        </h1>
                        <Button
                            label="Nuevo Usuario"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>

                    <DataTable
                        :value="usuarios"
                        v-model:expandedRows="expandedRows"
                        dataKey="id"
                        paginator
                        :rows="10"
                    >
                        <Column :expander="true" headerStyle="width: 3rem" />
                        <Column header="Nombre Completo">
                            <template #body="{ data }">
                                {{ data.persona?.nombre }}
                                {{ data.persona?.apellido }}
                            </template>
                        </Column>
                        <Column field="email" header="Email" sortable></Column>
                        <Column
                            field="rol.nombre"
                            header="Rol"
                            sortable
                        ></Column>
                        <Column header="Activo" style="text-align: center">
                            <template #body="{ data }">
                                <Tag
                                    v-if="data.activo"
                                    value="Sí"
                                    severity="success"
                                />
                                <Tag v-else value="No" severity="danger" />
                            </template>
                        </Column>
                        <Column
                            header="Acciones"
                            style="width: 10%; text-align: center"
                        >
                            <template #body="{ data }">
                                <div class="flex justify-center gap-2">
                                    <Button
                                        icon="pi pi-pencil"
                                        severity="info"
                                        text
                                        rounded
                                        @click="openEditDialog(data)"
                                    />
                                    <Button
                                        icon="pi pi-trash"
                                        severity="danger"
                                        text
                                        rounded
                                        @click="handleDelete(data)"
                                    />
                                </div>
                            </template>
                        </Column>

                        <template #expansion="slotProps">
                            <div
                                class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg"
                            >
                                <h5 class="font-bold mb-2">
                                    Documentos de
                                    {{ slotProps.data.persona.nombre }}
                                </h5>
                                <DataTable
                                    :value="slotProps.data.persona.documentos"
                                >
                                    <Column
                                        field="tipoDocumento.nombre"
                                        header="Tipo"
                                    ></Column>
                                    <Column
                                        field="numero"
                                        header="Número"
                                    ></Column>
                                    <Column
                                        field="fecha_vencimiento"
                                        header="Vencimiento"
                                    ></Column>
                                </DataTable>
                            </div>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>

        <CreateForm
            :visible="showCreateDialog"
            :roles="roles"
            :tipos-documento="tiposDocumento"
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />

        <EditForm
            :visible="showEditDialog"
            :usuario="editingUsuario"
            :roles="roles"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
