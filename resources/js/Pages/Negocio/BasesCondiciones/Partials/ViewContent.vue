<script setup>
import { computed } from "vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";

const props = defineProps({
    visible: Boolean,
    condicion: Object,
    productosSeguro: Array, // <-- 1. Recibimos la lista de productos
});

const emit = defineEmits(["update:visible"]);

// 2. Creamos una propiedad computada para obtener el nombre del producto
const productoNombre = computed(() => {
    if (!props.condicion || !props.productosSeguro) return "Cargando...";

    const producto = props.productosSeguro.find(
        (p) => p.id === props.condicion.producto_seguro_id
    );
    return producto
        ? producto.nombre_producto
        : `ID: ${props.condicion.producto_seguro_id}`;
});

// Mantenemos la lógica para formatear el contenido y evitar desbordamientos
const formattedContent = computed(() => {
    if (props.condicion && props.condicion.contenido) {
        return props.condicion.contenido.replace(/&nbsp;/g, " ");
    }
    return "";
});

const closeModal = () => emit("update:visible", false);
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="closeModal"
        modal
        header="Visualizar Base y Condición"
        :style="{ width: '60vw' }"
    >
        <div v-if="condicion" class="p-4">
            <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="font-semibold">Producto:</span>
                        {{ productoNombre }}
                    </div>
                    <div>
                        <span class="font-semibold">Versión:</span>
                        {{ condicion.version }}
                    </div>
                    <div>
                        <span class="font-semibold">Fecha de Publicación:</span>
                        {{ condicion.fecha_publicacion }}
                    </div>
                </div>
            </div>

            <div
                class="prose dark:prose-invert max-w-none p-4 border rounded-lg bg-white dark:bg-gray-900 overflow-y-auto max-h-[60vh]"
                v-html="formattedContent"
            ></div>

            <div class="flex justify-end gap-2 mt-6">
                <Button
                    label="Cerrar"
                    severity="secondary"
                    @click="closeModal"
                />
            </div>
        </div>
    </Dialog>
</template>

<style>
.prose {
    line-height: 1.6;
}
.prose h1,
.prose h2,
.prose h3 {
    margin-bottom: 0.5em;
    margin-top: 1em;
}
.prose p {
    margin-bottom: 1em;
}
.prose ul,
.prose ol {
    padding-left: 1.5em;
    margin-bottom: 1em;
}
.prose li {
    margin-bottom: 0.25em;
}
</style>
