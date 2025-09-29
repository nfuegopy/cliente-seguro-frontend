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
    productosSeguro: Array,
    aseguradoras: Array,
    tiposSeguro: Array,
});

const confirm = useConfirm();
const toast = useToast();

const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingProducto = ref(null);

const openEditDialog = (producto) => {
    editingProducto.value = producto;
    showEditDialog.value = true;
};

const handleDelete = (producto) => {
    confirm.require({
        message: `¿Está seguro de eliminar el producto "${producto.nombre_producto}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route("admin.negocio.productosseguro.destroy", producto.id),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Producto eliminado.",
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
    router.reload({ only: ["productosSeguro"] });
};
</script>

<template>
    <Head title="Productos de Seguro" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Gestión de Productos de Seguro
                        </h1>
                        <Button
                            label="Nuevo Producto"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>

                    <DataTable :value="productosSeguro" paginator :rows="10">
                        <Column
                            field="nombre_producto"
                            header="Producto"
                            sortable
                        />
                        <Column
                            field="aseguradora.nombre"
                            header="Aseguradora"
                            sortable
                        />
                        <Column
                            field="tipo_de_seguro.nombre"
                            header="Tipo de Seguro"
                            sortable
                        />
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
                    </DataTable>
                </div>
            </div>
        </div>

        <CreateForm
            :visible="showCreateDialog"
            :aseguradoras="aseguradoras"
            :tipos-seguro="tiposSeguro"
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />

        <EditForm
            :visible="showEditDialog"
            :producto="editingProducto"
            :aseguradoras="aseguradoras"
            :tipos-seguro="tiposSeguro"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
