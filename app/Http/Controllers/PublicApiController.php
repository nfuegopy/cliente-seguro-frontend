<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicApiController extends Controller
{
    public function proxy(string $endpoint)
    {
        // 1. Limpieza de seguridad: Solo permitimos endpoints de lectura específicos
        // Esto evita que alguien intente llamar a endpoints sensibles.
        $allowedEndpoints = [
            'vehiculo-marcas',
            'vehiculo-modelos', // Si necesitas modelos filtrados más adelante
            'departamento',
            'ciudad'
        ];

        // Verificamos si el endpoint solicitado empieza con alguno de los permitidos
        $isAllowed = false;
        foreach ($allowedEndpoints as $allowed) {
            if (str_starts_with($endpoint, $allowed)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            return response()->json(['error' => 'Endpoint no permitido para acceso público.'], 403);
        }

        try {
            // 2. Usamos el Helper para llamar a NestJS
            // Nota: Usamos getPublicAll porque no necesitamos token de usuario,
            // pero internamente ApiHelper usará la API Key del sistema si es necesario.
            $response = ApiHelper::getPublicAll('/' . $endpoint, request()->query());

            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error("Error en PublicApiController proxy: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener datos externos'], 500);
        }
    }
}
