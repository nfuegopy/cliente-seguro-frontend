import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

import PrimeVue from "primevue/config";
import Aura from "@primeuix/themes/aura";
import "primeicons/primeicons.css";

// --- ¡ASEGÚRATE DE TENER ESTAS LÍNEAS! ---
import ConfirmationService from "primevue/confirmationservice";
import ToastService from "primevue/toastservice";
import ConfirmDialog from "primevue/confirmdialog";
import Toast from "primevue/toast";

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
            .use(ZiggyVue, window.Ziggy);

        app.use(PrimeVue, {
            theme: {
                preset: Aura,
                options: {
                    darkModeSelector: ".dark-mode",
                },
            },
            ripple: true,
        });

        // --- ¡Y ASEGÚRATE DE QUE ESTÉN REGISTRADOS AQUÍ! ---
        app.use(ConfirmationService); // <-- Esta línea es crucial para el diálogo de confirmación.
        app.use(ToastService);
        app.component("ConfirmDialog", ConfirmDialog);
        app.component("Toast", Toast);

        app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
