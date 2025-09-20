<script setup>
import { ref } from "vue";
import TopBar from "@/Layouts/Partials/TopBar.vue";
import SideBar from "@/Layouts/Partials/SideBar.vue";
import ConfirmDialog from "primevue/confirmdialog"; // <-- 1. Importa el componente
import Toast from "primevue/toast"; // <-- Y el de notificaciones

const isSidebarPinned = ref(true);

const toggleSidebarPin = () => {
    isSidebarPinned.value = !isSidebarPinned.value;
};
</script>

<template>
    <div class="min-h-screen">
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

        <Toast />
        <ConfirmDialog />
    </div>
</template>
