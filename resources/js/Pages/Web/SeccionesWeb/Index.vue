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
    secciones: Array,
});

const confirm = useConfirm();
const toast = useToast();

const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingSeccion = ref(null);

const openEditDialog = (seccion) => {
    editingSeccion.value = seccion;
    showEditDialog.value = true;
};

const handleDelete = (seccion) => {
    confirm.require({
        message: `¿Está seguro de eliminar la sección "${seccion.titulo}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route("admin.web.secciones-web.destroy", seccion.id),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Sección eliminada.",
                            life: 3000,
                        }),
                }
            );
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
    router.reload({ only: ["secciones"] });
};
</script>

<template>
    <Head title="Secciones Web" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Gestión de Secciones Web
                        </h1>
                        <Button
                            label="Nueva Sección"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>
                    <DataTable :value="secciones" paginator :rows="10">
                        <Column field="orden" header="Orden" sortable />
                        <Column field="titulo" header="Título" sortable />
                        <Column header="Imagen">
                            <template #body="{ data }">
                                <img
                                    :src="data.imagen_url"
                                    :alt="data.titulo"
                                    class="w-24 h-16 object-cover rounded-md"
                                />
                            </template>
                        </Column>
                        <Column header="Activo">
                            <template #body="{ data }">
                                <Tag
                                    :value="data.activo ? 'Sí' : 'No'"
                                    :severity="
                                        data.activo ? 'success' : 'danger'
                                    "
                                />
                            </template>
                        </Column>
                        <Column header="Acciones" style="width: 10%">
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
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />
        <EditForm
            :visible="showEditDialog"
            :seccion="editingSeccion"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
