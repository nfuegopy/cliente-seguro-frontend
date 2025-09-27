import { ref } from "vue";

export function usePrimeIcons() {
    const icons = ref([
        "pi pi-home",
        "pi pi-user",
        "pi pi-users",
        "pi pi-cog",
        "pi pi-sitemap",
        "pi pi-folder",
        "pi pi-folder-open",
        "pi pi-wrench",
        "pi pi-book",
        "pi pi-file",
        "pi pi-chart-bar",
        "pi pi-chart-line",
        "pi pi-table",
        "pi pi-calendar",
        "pi pi-pencil",
        "pi pi-trash",
        "pi pi-search",
        "pi pi-plus",
        "pi pi-minus",
        "pi pi-check",
        "pi pi-times",
        "pi pi-inbox",
        "pi pi-lock",
        "pi pi-lock-open",
        "pi pi-power-off",
        "pi pi-sign-out",
        "pi pi-sign-in",
        "pi pi-bars",
        "pi pi-th-large",
        "pi pi-arrow-left",
        "pi pi-arrow-right",
        // Puedes agregar más íconos de la documentación oficial de PrimeIcons aquí
    ]);

    return {
        icons,
    };
}
