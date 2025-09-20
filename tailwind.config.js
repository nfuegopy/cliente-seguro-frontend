// tailwind.config.js

import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            // --- AÑADE ESTA SECCIÓN DE COLORES ---
            colors: {
                "dark-primary": "#1E293B", // El azul oscuro casi negro del fondo
                "dark-secondary": "#334155", // El gris oscuro para paneles
                "blue-accent": "#3B82F6", // El azul vibrante para acentos y botones
                "text-light": "#CBD5E1", // El gris claro para texto secundario
                "text-primary": "#F8FAFC", // El blanco/casi blanco para texto principal
            },
            // --- FIN DE LA SECCIÓN DE COLORES ---
        },
    },

    plugins: [forms],
};
