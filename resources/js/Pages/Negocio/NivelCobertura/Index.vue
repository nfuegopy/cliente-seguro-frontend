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
    nivelesCobertura: Array,
    productosSeguro: Array,
});
const confirm = useConfirm();
const toast = useToast();
const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingNivel = ref(null);

const getProductoNombre = (id) => {
    const producto = props.productosSeguro.find((p) => p.id === id);
    return producto ? producto.nombre_producto : "Desconocido";
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("es-PY", {
        style: "currency",
        currency: "PYG",
    }).format(value);
};

const openEditDialog = (nivel) => {
    editingNivel.value = nivel;
    showEditDialog.value = true;
};

const handleDelete = (nivel) => {
    confirm.require({
        message: `¿Seguro de eliminar el nivel "${nivel.nombre_nivel}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route("admin.negocio.nivelescobertura.destroy", nivel.id),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Nivel de Cobertura eliminado.",
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
    router.reload({ only: ["nivelesCobertura"] });
};
</script>

<template>
    <Head title="Niveles de Cobertura" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Gestión de Niveles de Cobertura
                        </h1>
                        <Button
                            label="Nuevo Nivel"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>
                    <DataTable :value="nivelesCobertura" paginator :rows="10">
                        <Column
                            field="nombre_nivel"
                            header="Nombre del Nivel"
                            sortable
                        />
                        <Column
                            header="Producto Asociado"
                            sortable
                            field="producto_seguro_id"
                        >
                            <template #body="{ data }">
                                {{ getProductoNombre(data.producto_seguro_id) }}
                            </template>
                        </Column>
                        <Column
                            field="prima_anual_base"
                            header="Prima Anual Base"
                            sortable
                        >
                            <template #body="slotProps">
                                {{
                                    formatCurrency(
                                        slotProps.data.prima_anual_base
                                    )
                                }}
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
            :productos-seguro="productosSeguro"
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />
        <EditForm
            :visible="showEditDialog"
            :nivel="editingNivel"
            :productos-seguro="productosSeguro"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
