<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { useTheme } from "@/Composables/useTheme"; // <-- 1. Importamos el nuevo composable
import Button from "primevue/button";

// 2. Obtenemos el modo actual y la función para cambiarlo
const { mode, toggleMode } = useTheme();

const emit = defineEmits(["toggle-sidebar"]);
</script>

<template>
    <header
        class="bg-dark-primary text-text-primary shadow-md p-4 flex justify-between items-center z-10 flex-shrink-0 h-16"
    >
        <div class="flex items-center">
            <button
                @click="emit('toggle-sidebar')"
                class="p-2 rounded-md hover:bg-dark-secondary focus:outline-none md:hidden"
            >
                <i class="pi pi-bars text-xl"></i>
            </button>
        </div>

        <div class="flex items-center gap-4">
            <!-- 3. Botón para Cambiar Tema (Claro/Oscuro) -->
            <Button
                @click="toggleMode"
                :icon="mode === 'light' ? 'pi pi-moon' : 'pi pi-sun'"
                text
                rounded
                aria-label="Toggle Theme"
                class="!text-text-light hover:!bg-dark-secondary"
            />

            <!-- Menú de Usuario -->
            <div class="relative">
                <Dropdown align="right" width="56">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button
                                type="button"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-text-light bg-dark-primary hover:text-text-primary focus:outline-none transition ease-in-out duration-150"
                            >
                                <div class="text-right">
                                    <div class="font-semibold">
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div
                                        class="text-xs text-text-light"
                                        v-if="$page.props.auth.userFromApi"
                                    >
                                        {{
                                            $page.props.auth.userFromApi.rol
                                                .nombre
                                        }}
                                    </div>
                                </div>
                                <i class="pi pi-chevron-down ml-2"></i>
                            </button>
                        </span>
                    </template>
                    <template #content>
                        <DropdownLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Cerrar Sesión
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </div>
    </header>
</template>
