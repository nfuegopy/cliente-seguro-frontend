<script setup>
import { route } from "ziggy-js";
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CreateForm from "./Partials/CreateForm.vue";
import EditForm from "./Partials/EditForm.vue";

import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    gruposMenu: Array,
});

const confirm = useConfirm();
const toast = useToast();

const isCreateModalVisible = ref(false);
const isEditModalVisible = ref(false);
const selectedGrupo = ref(null);

const openEditModal = (grupo) => {
    selectedGrupo.value = grupo;
    isEditModalVisible.value = true;
};

const deleteGrupo = (grupo) => {
    confirm.require({
        message: `¿Estás seguro de que quieres eliminar "${grupo.nombre}"?`,
        header: "Confirmación de Eliminación",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Sí, eliminar",
        rejectLabel: "Cancelar",
        accept: () => {
            router.delete(route("admin.grupo-menu.destroy", grupo.id), {
                onSuccess: () => {
                    toast.add({
                        severity: "success",
                        summary: "Éxito",
                        detail: "Grupo de Menú eliminado",
                        life: 3000,
                    });
                },
            });
        },
    });
};
</script>

<template>
    <Head title="Grupos de Menú" />

    <AuthenticatedLayout>
        <div class="p-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-dark-primary">
                        Grupos de Menú
                    </h1>
                    <Button
                        @click="isCreateModalVisible = true"
                        label="Crear Nuevo Grupo"
                        icon="pi pi-plus"
                    />
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable
                        :value="gruposMenu"
                        paginator
                        :rows="10"
                        tableStyle="min-width: 50rem"
                    >
                        <Column field="id" header="ID" sortable></Column>
                        <Column
                            field="nombre"
                            header="Nombre"
                            sortable
                        ></Column>
                        <Column
                            field="descripcion"
                            header="Descripción"
                        ></Column>
                        <Column
                            header="Acciones"
                            style="width: 15%; text-align: center"
                        >
                            <template #body="slotProps">
                                <div class="flex justify-center gap-2">
                                    <Button
                                        @click="openEditModal(slotProps.data)"
                                        icon="pi pi-pencil"
                                        severity="info"
                                        text
                                        rounded
                                    />
                                    <Button
                                        @click="deleteGrupo(slotProps.data)"
                                        icon="pi pi-trash"
                                        severity="danger"
                                        text
                                        rounded
                                    />
                                </div>
                            </template>
                        </Column>
                        <template #empty>
                            <div class="p-4 text-center text-gray-500">
                                No se encontraron grupos de menú.
                            </div>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>

        <Dialog
            v-model:visible="isCreateModalVisible"
            modal
            header="Crear Nuevo Grupo"
            :style="{ width: '30rem' }"
        >
            <CreateForm @close="isCreateModalVisible = false" />
        </Dialog>

        <Dialog
            v-model:visible="isEditModalVisible"
            modal
            header="Editar Grupo"
            :style="{ width: '30rem' }"
        >
            <EditForm
                v-if="selectedGrupo"
                :grupo="selectedGrupo"
                @close="isEditModalVisible = false"
            />
        </Dialog>
    </AuthenticatedLayout>
</template>
