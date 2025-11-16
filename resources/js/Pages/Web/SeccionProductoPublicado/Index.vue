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

// Formularios
import CreateForm from "./Partials/CreateForm.vue";
import EditForm from "./Partials/EditForm.vue";

const props = defineProps({
    publicaciones: Array, // Los enlaces
    seccionesWeb: Array, // Catálogo de Secciones
    productosSeguro: Array, // Catálogo de Productos
});

const confirm = useConfirm();
const toast = useToast();

const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingPublicacion = ref(null);

const openEditDialog = (publicacion) => {
    editingPublicacion.value = publicacion;
    showEditDialog.value = true;
};

const handleDelete = (publicacion) => {
    confirm.require({
        message: `¿Seguro de desvincular el producto "${publicacion.productoSeguro.nombre_producto}" de la sección "${publicacion.seccionWeb.titulo}"?`,
        header: "Confirmar Eliminación",
        acceptClass: "p-button-danger",
        accept: () => {
            router.delete(
                route(
                    "admin.web.seccion-producto-publicado.destroy",
                    publicacion.id
                ),
                {
                    onSuccess: () =>
                        toast.add({
                            severity: "success",
                            summary: "Éxito",
                            detail: "Publicación eliminada.",
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
    router.reload({ only: ["publicaciones"] });
};
</script>

<template>
    <Head title="Publicación de Productos" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-dark-primary">
                            Publicación de Productos en Secciones Web
                        </h1>
                        <Button
                            label="Publicar Producto"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                        />
                    </div>

                    <DataTable :value="publicaciones" paginator :rows="10">
                        <Column
                            field="seccionWeb.titulo"
                            header="Sección Web"
                            sortable
                        ></Column>
                        <Column
                            field="productoSeguro.nombre_producto"
                            header="Producto de Seguro"
                            sortable
                        ></Column>
                        <Column field="orden" header="Orden" sortable></Column>
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
                                        v-tooltip.top="'Editar Orden'"
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
            :secciones-web="seccionesWeb"
            :productos-seguro="productosSeguro"
            @update:visible="showCreateDialog = $event"
            @success="handleSuccess"
        />

        <EditForm
            :visible="showEditDialog"
            :publicacion="editingPublicacion"
            @update:visible="showEditDialog = $event"
            @success="handleSuccess"
        />
    </AuthenticatedLayout>
</template>
