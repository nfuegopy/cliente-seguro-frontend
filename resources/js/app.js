import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

// --- INICIA LA NUEVA Y CORRECTA CONFIGURACIÓN DE PRIMEVUE ---

import PrimeVue from "primevue/config";
import Aura from "@primeuix/themes/aura"; // 1. Importa el PRESET del tema desde el nuevo paquete.
import "primeicons/primeicons.css"; // 2. Importa los íconos (esto no ha cambiado).

// --- TERMINA LA CONFIGURACIÓN DE PRIMEVUE ---

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        // --- 3. REGISTRA PRIMEVUE CON EL NUEVO SISTEMA DE TEMA ---
        app.use(PrimeVue, {
            theme: {
                preset: Aura, // Usa el preset importado.
                options: {
                    darkModeSelector: ".dark-mode", // Opcional: para configurar el modo oscuro en el futuro.
                },
            },
            ripple: true, // Activa el efecto de onda en los clics.
        });

        app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
