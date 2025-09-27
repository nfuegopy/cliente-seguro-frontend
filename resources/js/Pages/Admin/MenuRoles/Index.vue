<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";

// Componentes y hooks de PrimeVue
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

// Formularios
import CreateForm from "./Partials/CreateForm.vue";
import EditForm from "./Partials/EditForm.vue";

// Recibimos los datos del controlador de Laravel.
const props = defineProps({
    menuroles: Array,
    menus: Array,
    roles: Array,
});

const confirm = useConfirm();
const toast = useToast();

const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingPermiso = ref(null);

const openEditDialog = (permiso) => {
    editingPermiso.value = permiso;
    showEditDialog.value = true;
};

const handleDelete = (permiso) => {
    const rolNombre = permiso.rol ? permiso.rol.nombre : "N/A";
    const menuNombre = permiso.menu ? permiso.menu.nombre : "N/A";

    confirm.require({
        message: `¿Seguro que quieres revocar los permisos de "${rolNombre}" sobre "${menuNombre}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(route("admin.menuroles.destroy", permiso.id), {
                onSuccess: () =>
                    toast.add({
                        severity: "success",
                        summary: "Éxito",
                        detail: "Permiso eliminado.",
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
    router.reload({ only: ["menuroles"] });
};
</script>

<template>
    <Head title="Permisos de Menú por Rol" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Gestión de Permisos
                        </h1>
                        <Button
                            label="Asignar Permiso"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>

                    <DataTable
                        :value="menuroles"
                        paginator
                        :rows="10"
                        tableStyle="min-w-full"
                    >
                        <Column
                            field="rol.nombre"
                            header="Rol"
                            sortable
                        ></Column>
                        <Column
                            field="menu.nombre"
                            header="Menú"
                            sortable
                        ></Column>
                        <Column
                            header="Listar"
                            style="width: 8%; text-align: center"
                        >
                            <template #body="{ data }">
                                <i
                                    v-if="data.permitir_listar"
                                    class="pi pi-check-circle text-green-500"
                                ></i>
                                <i
                                    v-else
                                    class="pi pi-times-circle text-red-500"
                                ></i>
                            </template>
                        </Column>
                        <Column
                            header="Guardar"
                            style="width: 8%; text-align: center"
                        >
                            <template #body="{ data }">
                                <i
                                    v-if="data.permitir_guardar"
                                    class="pi pi-check-circle text-green-500"
                                ></i>
                                <i
                                    v-else
                                    class="pi pi-times-circle text-red-500"
                                ></i>
                            </template>
                        </Column>
                        <Column
                            header="Editar"
                            style="width: 8%; text-align: center"
                        >
                            <template #body="{ data }">
                                <i
                                    v-if="data.permitir_editar"
                                    class="pi pi-check-circle text-green-500"
                                ></i>
                                <i
                                    v-else
                                    class="pi pi-times-circle text-red-500"
                                ></i>
                            </template>
                        </Column>
                        <Column
                            header="Eliminar"
                            style="width: 8%; text-align: center"
                        >
                            <template #body="{ data }">
                                <i
                                    v-if="data.permitir_eliminar"
                                    class="pi pi-check-circle text-green-500"
                                ></i>
                                <i
                                    v-else
                                    class="pi pi-times-circle text-red-500"
                                ></i>
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
                    </DataTable>
                </div>
            </div>
        </div>

        <CreateForm
            :visible="showCreateDialog"
            :menus="menus"
            :roles="roles"
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />

        <EditForm
            :visible="showEditDialog"
            :permiso="editingPermiso"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
