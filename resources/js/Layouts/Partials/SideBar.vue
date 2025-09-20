<script setup>
import { ref, onMounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import axios from "axios";

// --- NUEVA LÓGICA DE INTERACCIÓN ---

// El componente recibe del layout si debe estar fijado o no.
const props = defineProps({
    isPinned: Boolean,
});

// El componente le avisa al layout cuando el usuario cambia el estado de fijado.
const emit = defineEmits(["toggle-pin"]);

// Estado interno para saber si el mouse está encima del sidebar.
const isHovering = ref(false);

// Propiedad computada: el sidebar se considera "expandido" si está fijado O si el mouse está encima.
const isExpanded = computed(() => props.isPinned || isHovering.value);

// --- LÓGICA EXISTENTE PARA CARGAR EL MENÚ ---

const menuGroups = ref([]);
const openMenuGroupId = ref(null);

const toggleMenuGroup = (groupId) => {
    openMenuGroupId.value = openMenuGroupId.value === groupId ? null : groupId;
};

onMounted(async () => {
    try {
        const response = await axios.get(route("api.menu"));
        menuGroups.value = response.data;
        if (response.data.length > 0) {
            openMenuGroupId.value = response.data[0].id;
        }
    } catch (error) {
        console.error("Error al cargar el menú:", error);
    }
});
</script>

<template>
    <aside
        @mouseenter="isHovering = true"
        @mouseleave="isHovering = false"
        class="bg-dark-secondary text-text-primary shadow-lg flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col"
        :class="{ 'w-64': isExpanded, 'w-20': !isExpanded }"
    >
        <div
            class="p-4 flex items-center justify-between border-b border-gray-700 h-16"
        >
            <span v-show="isExpanded" class="font-bold">Menú Principal</span>
            <button
                @click="emit('toggle-pin')"
                class="p-2 rounded-full hover:bg-dark-primary"
            >
                <i :class="isPinned ? 'pi pi-lock' : 'pi pi-lock-open'"></i>
            </button>
        </div>

        <nav class="flex-1 p-2 overflow-y-auto">
            <div v-for="group in menuGroups" :key="group.id" class="mb-2">
                <button
                    @click="toggleMenuGroup(group.id)"
                    class="w-full flex items-center justify-between p-3 rounded-md hover:bg-dark-primary"
                >
                    <div class="flex items-center">
                        <i :class="group.icono" class="text-xl"></i>
                        <span v-show="isExpanded" class="font-semibold ml-3">{{
                            group.nombre
                        }}</span>
                    </div>
                    <i
                        v-show="isExpanded"
                        class="pi pi-chevron-down transition-transform duration-300"
                        :class="{ 'rotate-180': openMenuGroupId === group.id }"
                    ></i>
                </button>

                <ul
                    v-show="isExpanded && openMenuGroupId === group.id"
                    class="mt-1 ml-4 border-l border-gray-600"
                >
                    <li v-for="option in group.opciones" :key="option.id">
                        <Link
                            :href="option.url"
                            class="w-full text-left flex items-center p-2 my-1 rounded-md text-text-light hover:bg-blue-accent hover:text-white"
                            :class="{
                                'bg-blue-accent text-white font-semibold':
                                    route().current(option.url.substring(1)),
                            }"
                        >
                            <i :class="option.icono" class="mr-3"></i>
                            <span v-show="isExpanded">{{ option.nombre }}</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>
</template>
