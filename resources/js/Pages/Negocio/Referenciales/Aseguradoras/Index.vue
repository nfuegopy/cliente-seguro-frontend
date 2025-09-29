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
    aseguradoras: Array,
});
const confirm = useConfirm();
const toast = useToast();
const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingAseguradora = ref(null);

const openEditDialog = (aseguradora) => {
    editingAseguradora.value = aseguradora;
    showEditDialog.value = true;
};

const handleDelete = (aseguradora) => {
    confirm.require({
        message: `¿Está seguro de eliminar la aseguradora "${aseguradora.nombre}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route(
                    "admin.negocio.referenciales.aseguradoras.destroy",
                    aseguradora.id
                ),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Aseguradora eliminada.",
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
    router.reload({ only: ["aseguradoras"] });
};
</script>

<template>
    <Head title="Aseguradoras" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Gestión de Aseguradoras
                        </h1>
                        <Button
                            label="Nueva Aseguradora"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>
                    <DataTable
                        :value="aseguradoras"
                        paginator
                        :rows="10"
                        responsiveLayout="scroll"
                    >
                        <Column
                            field="nombre"
                            header="Nombre"
                            sortable
                        ></Column>
                        <Column field="nit" header="NIT"></Column>
                        <Column field="telefono" header="Teléfono"></Column>
                        <Column field="email" header="Email"></Column>
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
            :aseguradora="editingAseguradora"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
