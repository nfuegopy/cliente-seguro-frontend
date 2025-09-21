<script setup>
import { ref } from "vue";
import TopBar from "@/Layouts/Partials/TopBar.vue";
import SideBar from "@/Layouts/Partials/SideBar.vue";

// --- ¡AÑADE ESTAS IMPORTACIONES! ---
import ConfirmDialog from "primevue/confirmdialog";
import Toast from "primevue/toast";

// Estado que controla si el sidebar está fijado. Por defecto, sí.
const isSidebarPinned = ref(true);

// Función que cambia el estado de fijado. Se la pasamos al SideBar.
const toggleSidebarPin = () => {
    isSidebarPinned.value = !isSidebarPinned.value;
};
</script>

<template>
    <div class="min-h-screen">
        <ConfirmDialog />
        <Toast />

        <div class="flex h-screen">
            <SideBar
                :is-pinned="isSidebarPinned"
                @toggle-pin="toggleSidebarPin"
            />

            <div class="flex-1 flex flex-col overflow-hidden">
                <TopBar />
                <main class="flex-1 overflow-y-auto bg-gray-200">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
