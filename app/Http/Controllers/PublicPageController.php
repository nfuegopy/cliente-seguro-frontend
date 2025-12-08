<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class PublicPageController extends Controller
{
    /**
     * Muestra una página pública basada en un slug dinámico.
     *
     * @param string $slug El identificador de la página desde la URL.
     * @return \Inertia\Response
     */
    public function show(string $slug): Response
    {
        Log::info("PublicPageController: Buscando sección para el slug '{$slug}'.");

        try {
            // 1. Obtenemos todas las secciones activas
            $responseSecciones = ApiHelper::getPublicAll('/secciones-web/activos');
            $secciones = $responseSecciones->json();

            // 2. Encontramos la sección actual por el slug
            $seccionActual = null;
            foreach ($secciones as $seccion) {
                if (Str::slug($seccion['titulo']) === $slug) {
                    $seccionActual = $seccion;
                    break;
                }
            }

            if (!$seccionActual) {
                Log::warning("PublicPageController: No se encontró ninguna sección para el slug '{$slug}'.");
                abort(404);
            }

            // --- INICIO DE LA LÓGICA CORREGIDA ---

            // 3. Obtener TODOS los "links" de productos publicados (USANDO LA NUEVA RUTA)
            $responseProductosPublicados = ApiHelper::getPublicAll('/seccion-producto-publicado/publicos');
            $todosProductosPublicados = $responseProductosPublicados->json() ?? [];

            // 4. Obtener TODOS los "links" de campos de formulario (USANDO LA NUEVA RUTA)
            $responseCamposFormulario = ApiHelper::getPublicAll('/producto-formulario-campos/publicos');
            $todosCamposDefinidos = $responseCamposFormulario->json() ?? [];

            // 5. Filtrar los productos que pertenecen a ESTA sección
            $productosDeLaSeccionIds = array_filter(
                $todosProductosPublicados,
                fn($p) => $p['seccion_web_id'] === $seccionActual['id']
            );

            $productosConFormulario = [];

            // 6. Para cada producto de esta sección, buscar sus campos
            foreach ($productosDeLaSeccionIds as $productoLink) {
                $productoInfo = $productoLink['productoSeguro']; // La API ya trae esto (eager loading)
                $productoId = $productoInfo['id'];

                // Filtramos los campos que pertenecen a ESTE producto
                $camposParaEsteProducto = array_filter(
                    $todosCamposDefinidos,
                    fn($c) => $c['producto_seguro_id'] === $productoId
                );

                // Ordenamos los campos según la definición de la BD
                usort($camposParaEsteProducto, fn($a, $b) => $a['orden'] <=> $b['orden']);

                // Añadimos los campos encontrados al objeto del producto
                $productoInfo['formulario'] = array_values($camposParaEsteProducto);
                $productosConFormulario[] = $productoInfo;
            }

            // --- FIN DE LA LÓGICA CORREGIDA ---

            Log::info("PublicPageController: Sección encontrada para '{$slug}'. Renderizando vista.", ['seccion' => $seccionActual['titulo']]);

            Log::debug("PublicPageController: Datos enviados al Frontend:", [
                'productos' => $productosConFormulario
            ]);

            return Inertia::render('Public/PaginaDinamica', [
                'seccion' => $seccionActual,
                'productosConFormulario' => $productosConFormulario // <-- 7. Pasamos los datos a la vista
            ]);

        } catch (\Exception $e) {
            Log::error("PublicPageController: Error al intentar obtener datos de la API.", ['error' => $e->getMessage()]);
            abort(500, 'No se pudo contactar al servicio de contenido.');
        }
    }
}
