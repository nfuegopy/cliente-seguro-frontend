import { ref, onMounted } from "vue";

export function useTheme() {
    // El estado solo guarda 'light' o 'dark'. Lo lee del localStorage.
    const mode = ref(localStorage.getItem("theme_mode") || "light");

    // Función que aplica la clase correcta al elemento <html>
    const applyMode = (currentMode) => {
        if (currentMode === "dark") {
            document.documentElement.classList.add("dark-mode");
        } else {
            document.documentElement.classList.remove("dark-mode");
        }
        // Guarda la preferencia en el navegador para la próxima visita
        localStorage.setItem("theme_mode", currentMode);
    };

    // Función para alternar entre los modos
    const toggleMode = () => {
        const newMode = mode.value === "light" ? "dark" : "light";
        mode.value = newMode; // Actualiza el estado reactivo
        applyMode(newMode); // Aplica el cambio al DOM
    };

    // Al cargar la aplicación, nos aseguramos de que se aplique el tema guardado.
    onMounted(() => {
        applyMode(mode.value);
    });

    // Devolvemos el modo actual y la función para alternarlo.
    return {
        mode,
        toggleMode,
    };
}
