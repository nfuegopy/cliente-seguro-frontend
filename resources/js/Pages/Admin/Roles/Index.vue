<script setup>
import { route } from "ziggy-js";
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CreateRoleForm from "./Partials/CreateRoleForm.vue";
import EditRoleForm from "./Partials/EditRoleForm.vue";

// Componentes de PrimeVue
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    roles: Array,
});

const confirm = useConfirm();
const toast = useToast();

// Estados para controlar la visibilidad de los modales
const isCreateModalVisible = ref(false);
const isEditModalVisible = ref(false);
const selectedRole = ref(null); // Almacena el rol seleccionado para editar

// Función para abrir el modal de edición
const openEditModal = (role) => {
    selectedRole.value = role;
    isEditModalVisible.value = true;
};

// Función para invocar el diálogo de confirmación de borrado
const deleteRole = (role) => {
    confirm.require({
        message: `¿Estás seguro de que quieres eliminar el rol "${role.nombre}"?`,
        header: "Confirmación de Eliminación",
        icon: "pi pi-info-circle",
        rejectClass: "p-button-secondary p-button-outlined",
        acceptClass: "p-button-danger",
        acceptLabel: "Sí, eliminar",
        rejectLabel: "Cancelar",
        accept: () => {
            router.delete(route("admin.roles.destroy", role.id), {
                onSuccess: () => {
                    toast.add({
                        severity: "success",
                        summary: "Éxito",
                        detail: "Rol eliminado correctamente",
                        life: 3000,
                    });
                },
                onError: (errors) => {
                    toast.add({
                        severity: "error",
                        summary: "Error",
                        detail:
                            "No se pudo eliminar el rol. " +
                            (errors.api_error || ""),
                        life: 5000,
                    });
                },
            });
        },
    });
};
</script>

<template>
    <Head title="Gestión de Roles" />

    <AuthenticatedLayout>
        <div class="p-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-dark-primary">
                        Gestión de Roles
                    </h1>
                    <Button
                        @click="isCreateModalVisible = true"
                        label="Crear Nuevo Rol"
                        icon="pi pi-plus"
                    />
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable
                        :value="roles"
                        paginator
                        :rows="10"
                        tableStyle="min-w-full"
                    >
                        <Column
                            field="id"
                            header="ID"
                            sortable
                            style="width: 10%"
                        ></Column>
                        <Column
                            field="nombre"
                            header="Nombre"
                            sortable
                            style="width: 35%"
                        ></Column>
                        <Column
                            field="descripcion"
                            header="Descripción"
                            style="width: 40%"
                        ></Column>
                        <Column
                            header="Acciones"
                            style="width: 15%; text-align: center"
                        >
                            <template #body="slotProps">
                                <div class="flex justify-center gap-2">
                                    <Button
                                        @click="openEditModal(slotProps.data)"
                                        icon="pi pi-pencil"
                                        severity="info"
                                        text
                                        rounded
                                        aria-label="Editar"
                                    />
                                    <Button
                                        @click="deleteRole(slotProps.data)"
                                        icon="pi pi-trash"
                                        severity="danger"
                                        text
                                        rounded
                                        aria-label="Eliminar"
                                    />
                                </div>
                            </template>
                        </Column>
                        <template #empty>
                            <div class="p-4 text-center text-gray-500">
                                No se encontraron roles.
                            </div>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog
            v-model:visible="isCreateModalVisible"
            modal
            header="Crear Nuevo Rol"
            :style="{ width: '30rem' }"
        >
            <CreateRoleForm @close="isCreateModalVisible = false" />
        </Dialog>

        <Dialog
            v-model:visible="isEditModalVisible"
            modal
            header="Editar Rol"
            :style="{ width: '30rem' }"
        >
            <EditRoleForm
                v-if="selectedRole"
                :role="selectedRole"
                @close="isEditModalVisible = false"
            />
        </Dialog>
    </AuthenticatedLayout>
</template>
