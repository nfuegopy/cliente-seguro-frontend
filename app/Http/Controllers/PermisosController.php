<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PermisosController extends Controller
{
    /**
     * Obtiene la estructura del menú para el rol del usuario logueado.
     * Esta función será llamada desde el frontend para construir el Sidebar.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerMenuParaRol(Request $request)
    {
        // 1. Obtenemos el usuario de la sesión que guardamos durante el login.
        $usuarioApi = Session::get('user_from_api');

        // 2. Verificamos que tengamos un usuario y un rol en la sesión.
        if (!$usuarioApi || empty($usuarioApi['rol']['id'])) {
            Log::warning('Se intentó obtener el menú sin un usuario o rol en la sesión.');
            return response()->json(['error' => 'No autorizado para obtener el menú.'], 403);
        }

        $rolId = $usuarioApi['rol']['id'];

        try {
            Log::info("Obteniendo menú para el rol ID: {$rolId}");

            // 3. Usamos nuestro ApiHelper para llamar al endpoint de la API de NestJS.
            //    La llamada es GET y, como vimos, requiere la API Key.
                      $response = ApiHelper::getById('roles', $rolId . '/menus');


            // 4. Devolvemos la respuesta JSON de la API directamente al frontend.
            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error("Error al obtener el menú para el rol ID: {$rolId}", [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'No se pudo cargar el menú.'], 500);
        }
    }
}
