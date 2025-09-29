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
import ViewContent from "./Partials/ViewContent.vue";

const props = defineProps({
    basesCondiciones: Array,
    productosSeguro: Array,
});
const confirm = useConfirm();
const toast = useToast();
const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const showViewDialog = ref(false);
const editingCondicion = ref(null);
const viewingCondicion = ref(null);

const getProductoNombre = (id) => {
    const producto = props.productosSeguro.find((p) => p.id === id);
    return producto ? producto.nombre_producto : "Desconocido";
};
const openEditDialog = (condicion) => {
    editingCondicion.value = condicion;
    showEditDialog.value = true;
};
const openViewDialog = (condicion) => {
    viewingCondicion.value = condicion;
    showViewDialog.value = true;
};
const handleDelete = (condicion) => {
    confirm.require({
        message: `¿Seguro de eliminar la condición v${condicion.version} del producto ID ${condicion.producto_seguro_id}?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route("admin.negocio.basescondiciones.destroy", condicion.id),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Condición eliminada.",
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
    router.reload({ only: ["basesCondiciones"] });
};
</script>

<template>
    <Head title="Bases y Condiciones" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Gestión de Bases y Condiciones
                        </h1>
                        <Button
                            label="Nueva Condición"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>
                    <DataTable :value="basesCondiciones" paginator :rows="10">
                        <Column
                            header="Producto Asociado"
                            sortable
                            field="producto_seguro_id"
                        >
                            <template #body="{ data }">
                                {{ getProductoNombre(data.producto_seguro_id) }}
                            </template>
                        </Column>
                        <Column field="version" header="Versión" sortable />
                        <Column
                            field="fecha_publicacion"
                            header="Publicación"
                            sortable
                        />
                        <Column
                            header="Acciones"
                            style="width: 15%; text-align: center"
                        >
                            <template #body="{ data }">
                                <div class="flex justify-center gap-2">
                                    <Button
                                        icon="pi pi-eye"
                                        severity="success"
                                        text
                                        rounded
                                        @click="openViewDialog(data)"
                                        v-tooltip.top="'Ver Contenido'"
                                    />
                                    <Button
                                        icon="pi pi-pencil"
                                        severity="info"
                                        text
                                        rounded
                                        @click="openEditDialog(data)"
                                        v-tooltip.top="'Editar'"
                                    />
                                    <Button
                                        icon="pi pi-trash"
                                        severity="danger"
                                        text
                                        rounded
                                        @click="handleDelete(data)"
                                        v-tooltip.top="'Eliminar'"
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
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />
        <EditForm
            :visible="showEditDialog"
            :condicion="editingCondicion"
            :productos-seguro="productosSeguro"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
        <ViewContent
            :visible="showViewDialog"
            :condicion="viewingCondicion"
            :productos-seguro="productosSeguro"
            @update:visible="showViewDialog = $event"
        />
    </AuthenticatedLayout>
</template>
