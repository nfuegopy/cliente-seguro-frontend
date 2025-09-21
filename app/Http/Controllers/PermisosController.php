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
        Log::info('*** INICIO - obtenerMenuParaRol ***');
        Log::info('Solicitud recibida para obtener el menú.');

        // 1. Obtenemos el usuario de la sesión que guardamos durante el login.
        $usuarioApi = Session::get('user_from_api');
        Log::info('Usuario de la sesión:', ['usuario' => $usuarioApi]);

        // 2. Verificamos que tengamos un usuario y un rol en la sesión.
        if (!$usuarioApi || empty($usuarioApi['rol']['id'])) {
            Log::warning('Se intentó obtener el menú sin un usuario o rol en la sesión. La sesión es nula o el ID del rol está vacío.');
            Log::info('*** FIN - obtenerMenuParaRol con error de autorización ***');
            return response()->json(['error' => 'No autorizado para obtener el menú.'], 403);
        }

        $rolId = $usuarioApi['rol']['id'];

        try {
            Log::info("Obteniendo menú para el rol ID: {$rolId}");

            // 3. Usamos nuestro ApiHelper para llamar al endpoint de la API de NestJS.
            //    La llamada es GET y, como vimos, requiere la API Key.
            $endpoint = 'roles/' . $rolId . '/menus';
            Log::info("Realizando llamada a la API: GET {$endpoint}");

            $response = ApiHelper::getById('roles', $rolId . '/menus');

            Log::info('Respuesta de la API recibida.');

            // Verificamos si la respuesta fue exitosa y la registramos.
            if ($response->successful()) {
                Log::info('Respuesta de la API exitosa. Status: ' . $response->status());
                Log::debug('Cuerpo de la respuesta de la API:', ['data' => $response->json()]);

            } else {
                Log::error('La API respondió con un error. Status: ' . $response->status());
                Log::error('Cuerpo de la respuesta de la API con error:', ['data' => $response->json()]);
                return response()->json(['error' => 'No se pudo cargar el menú.'], 500);
            }

            // 4. Devolvemos la respuesta JSON de la API directamente al frontend.
            $responseData = $response->json();
            Log::info('Devolviendo respuesta JSON al frontend.');
            Log::debug('JSON final devuelto:', ['json' => $responseData]);

            Log::info('*** FIN - obtenerMenuParaRol exitoso ***');
            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error("Error inesperado al obtener el menú para el rol ID: {$rolId}", [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
            Log::info('*** FIN - obtenerMenuParaRol con excepción ***');
            return response()->json(['error' => 'No se pudo cargar el menú.'], 500);
        }
    }
}
