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
            $response = ApiHelper::getPublicAll('/secciones-web/activos');
            $secciones = $response->json();


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

            Log::info("PublicPageController: Sección encontrada para '{$slug}'. Renderizando vista.", ['seccion' => $seccionActual['titulo']]);

            return Inertia::render('Public/PaginaDinamica', [
                'seccion' => $seccionActual,
            ]);

        } catch (\Exception $e) {
            Log::error("PublicPageController: Error al intentar obtener datos de la API.", ['error' => $e->getMessage()]);
            abort(500, 'No se pudo contactar al servicio de contenido.');
        }
    }
}
