<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import CreateForm from "./Partials/CreateForm.vue";
import EditForm from "./Partials/EditForm.vue";

const props = defineProps({
    tiposSeguro: Array,
});
const confirm = useConfirm();
const toast = useToast();
const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingTipoSeguro = ref(null);
const openEditDialog = (tipoSeguro) => {
    editingTipoSeguro.value = tipoSeguro;
    showEditDialog.value = true;
};
const handleDelete = (tipoSeguro) => {
    confirm.require({
        message: `¿Está seguro de eliminar "${tipoSeguro.nombre}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route(
                    "admin.negocio.parametros.tiposeguro.destroy",
                    tipoSeguro.id
                ),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Tipo de Seguro eliminado.",
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
    router.reload({ only: ["tiposSeguro"] });
};
</script>

<template>
    <Head title="Tipos de Seguro" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Gestión de Tipos de Seguro
                        </h1>
                        <Button
                            label="Nuevo Tipo de Seguro"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>
                    <DataTable :value="tiposSeguro" paginator :rows="10">
                        <Column field="nombre" header="Nombre" sortable />
                        <Column field="descripcion" header="Descripción" />
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
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />
        <EditForm
            :visible="showEditDialog"
            :tipo-seguro="editingTipoSeguro"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
