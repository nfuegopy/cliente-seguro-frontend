<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

// Componentes de PrimeVue
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import CreateForm from "./Partials/CreateForm.vue";
import EditForm from "./Partials/EditForm.vue";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    menus: Array,
    grupoMenus: Array,
    flash: Object,
    errors: Object, // <-- Importante para recibir errores de validación
});

const confirm = useConfirm();
const toast = useToast();

// --- LOG DETALLADO DE ERRORES DE LARAVEL ---
watch(
    () => props.errors,
    (newErrors) => {
        if (newErrors && Object.keys(newErrors).length > 0) {
            console.error(
                "LOG (Index.vue): Errores de validación recibidos de Laravel ->",
                newErrors
            );
        }
    },
    { immediate: true, deep: true }
);

// --- Lógica de la Tabla (DataTable) ---
const globalFilter = ref(null);
const filters = ref({
    global: { value: globalFilter, matchMode: "contains" },
});
const menuItems = computed(() => props.menus);

// --- Lógica de los Modales ---
const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const editingMenu = ref(null);

watch(
    () => props.flash,
    (newFlash) => {
        if (newFlash && newFlash.message) {
            toast.add({
                severity: newFlash.type,
                summary: "Mensaje",
                detail: newFlash.message,
                life: 3000,
            });
        }
    },
    { immediate: true }
);

const openEditDialog = (menu) => {
    console.log("LOG (Index.vue): Abriendo modal de edición para ->", menu);
    editingMenu.value = menu;
    showEditDialog.value = true;
};

const hideEditDialog = () => {
    showEditDialog.value = false;
    editingMenu.value = null;
};

// --- CRUD Operations ---
const handleDelete = (menu) => {
    confirm.require({
        message: `¿Está seguro de que desea eliminar el menú: ${menu.nombre}?`,
        header: "Confirmar Eliminación",
        icon: "pi pi-info-circle",
        rejectClass: "p-button-secondary p-button-outlined",
        acceptClass: "p-button-danger",
        acceptLabel: "Sí, Eliminar",
        rejectLabel: "Cancelar",
        accept: () => {
            router.delete(route("admin.menu.destroy", menu.id), {
                onSuccess: () => {
                    toast.add({
                        severity: "success",
                        summary: "Éxito",
                        detail: "Menú eliminado correctamente.",
                        life: 3000,
                    });
                },
                onError: (errors) => {
                    toast.add({
                        severity: "error",
                        summary: "Error",
                        detail: errors.message || "Error al eliminar el menú.",
                        life: 3000,
                    });
                },
            });
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
    router.reload({ only: ["menus"] });
};
</script>

<template>
    <Head title="Administración de Menús" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Menús
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-end mb-4">
                        <Button
                            label="Nuevo Menú"
                            icon="pi pi-plus"
                            @click="showCreateDialog = true"
                            class="p-button-primary"
                        />
                    </div>

                    <DataTable
                        :value="menuItems"
                        paginator
                        :rows="10"
                        :rowsPerPageOptions="[5, 10, 20]"
                        v-model:filters="filters"
                        :globalFilterFields="['nombre', 'url']"
                        tableStyle="min-width: 50rem"
                    >
                        <template #header>
                            <div class="flex justify-between items-center">
                                <h5
                                    class="m-0 text-gray-800 dark:text-gray-200"
                                >
                                    Gestión de Menús
                                </h5>
                                <InputText
                                    v-model="globalFilter"
                                    placeholder="Buscar Menú..."
                                    class="p-inputtext-sm"
                                />
                            </div>
                        </template>
                        <Column
                            field="nombre"
                            header="Nombre"
                            sortable
                            style="width: 20%"
                        ></Column>
                        <Column
                            field="url"
                            header="Ruta (Ziggy)"
                            sortable
                            style="width: 25%"
                        >
                            <template #body="{ data }">
                                <span
                                    class="font-mono text-xs p-1 bg-gray-100 dark:bg-gray-700 rounded"
                                    >{{ data.url }}</span
                                >
                            </template>
                        </Column>
                        <Column
                            field="grupoMenu.nombre"
                            header="Grupo Menú"
                            sortable
                            style="width: 20%"
                        ></Column>
                        <Column
                            field="icono"
                            header="Icono (PrimeIcons)"
                            style="width: 15%"
                        >
                            <template #body="{ data }">
                                <i :class="data.icono" class="text-xl"></i>
                                <span class="ml-2">{{ data.icono }}</span>
                            </template>
                        </Column>
                        <Column header="Acciones" style="width: 10%">
                            <template #body="{ data }">
                                <Button
                                    icon="pi pi-pencil"
                                    class="p-button-rounded p-button-success p-button-text mr-2"
                                    @click="openEditDialog(data)"
                                    v-tooltip.top="'Editar'"
                                />
                                <Button
                                    icon="pi pi-trash"
                                    class="p-button-rounded p-button-danger p-button-text"
                                    @click="handleDelete(data)"
                                    v-tooltip.top="'Eliminar'"
                                />
                            </template>
                        </Column>
                    </DataTable>

                    <CreateForm
                        :visible="showCreateDialog"
                        :grupoMenus="grupoMenus"
                        @update:visible="showCreateDialog = $event"
                        @success="handleSuccess"
                    />

                    <EditForm
                        :visible="showEditDialog"
                        :menu="editingMenu"
                        :grupoMenus="grupoMenus"
                        @update:visible="hideEditDialog"
                        @success="handleSuccess"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
