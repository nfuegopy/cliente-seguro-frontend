<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";

// Componentes de PrimeVue
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

// Formularios (los crearemos a continuación)
import CreateForm from "./Partials/CreateForm.vue";
import EditForm from "./Partials/EditForm.vue";

const props = defineProps({
    camposFormulario: Array,
});

const confirm = useConfirm();
const toast = useToast();

const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingCampo = ref(null);

const openEditDialog = (campo) => {
    editingCampo.value = campo;
    showEditDialog.value = true;
};

const handleDelete = (campo) => {
    confirm.require({
        message: `¿Está seguro de eliminar el campo "${campo.etiqueta}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route("admin.formularios.campos-formulario.destroy", campo.id),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Campo eliminado.",
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
    router.reload({ only: ["camposFormulario"] });
};
</script>

<template>
    <Head title="Campos de Formulario" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Catálogo de Campos de Formulario
                        </h1>
                        <Button
                            label="Nuevo Campo"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>

                    <DataTable :value="camposFormulario" paginator :rows="10">
                        <Column
                            field="etiqueta"
                            header="Etiqueta (Visible)"
                            sortable
                        ></Column>
                        <Column
                            field="key_tecnica"
                            header="Key Técnica"
                            sortable
                        ></Column>
                        <Column
                            field="entidad_destino"
                            header="Entidad Destino"
                            sortable
                        ></Column>
                        <Column
                            field="tipo_html"
                            header="Tipo HTML"
                            sortable
                        ></Column>
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
            :campo="editingCampo"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
