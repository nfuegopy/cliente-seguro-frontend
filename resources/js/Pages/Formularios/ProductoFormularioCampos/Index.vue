<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";

// Componentes de PrimeVue
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Tag from "primevue/tag";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

// Formularios
import CreateForm from "./Partials/CreateForm.vue";
import EditForm from "./Partials/EditForm.vue";

const props = defineProps({
    asignaciones: Array, // Los enlaces
    productosSeguro: Array, // Catálogo de Productos
    camposFormulario: Array, // Catálogo de Campos
});

const confirm = useConfirm();
const toast = useToast();

const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingAsignacion = ref(null);

const openEditDialog = (asignacion) => {
    editingAsignacion.value = asignacion;
    showEditDialog.value = true;
};

const handleDelete = (asignacion) => {
    confirm.require({
        message: `¿Seguro de quitar el campo "${asignacion.campoFormulario.etiqueta}" del producto "${asignacion.productoSeguro.nombre_producto}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route(
                    "admin.formularios.producto-formulario-campos.destroy",
                    asignacion.id
                ),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Asignación eliminada.",
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
    router.reload({ only: ["asignaciones"] });
};
</script>

<template>
    <Head title="Asignación de Campos" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Asignación de Campos a Productos
                        </h1>
                        <Button
                            label="Asignar Campo"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>

                    <DataTable
                        :value="asignaciones"
                        paginator
                        :rows="10"
                        sortField="productoSeguro.nombre_producto"
                        :sortOrder="1"
                    >
                        <Column
                            field="productoSeguro.nombre_producto"
                            header="Producto de Seguro"
                            sortable
                        ></Column>
                        <Column
                            field="campoFormulario.etiqueta"
                            header="Campo de Formulario"
                            sortable
                        ></Column>
                        <Column
                            field="orden"
                            header="Orden"
                            sortable
                            style="width: 10%"
                        ></Column>
                        <Column
                            field="es_requerido"
                            header="Requerido"
                            sortable
                            style="width: 10%"
                        >
                            <template #body="{ data }">
                                <Tag
                                    :value="data.es_requerido ? 'Sí' : 'No'"
                                    :severity="
                                        data.es_requerido ? 'success' : 'danger'
                                    "
                                />
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
                                        v-tooltip.top="'Editar Orden/Requerido'"
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
            :productos-seguro="productosSeguro"
            :campos-formulario="camposFormulario"
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />

        <EditForm
            :visible="showEditDialog"
            :asignacion="editingAsignacion"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
