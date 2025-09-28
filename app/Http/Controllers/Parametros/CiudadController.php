<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CiudadController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Ciudades.');

        // 1. Obtener la lista de ciudades.
        $responseCiudades = ApiHelper::getAll('/ciudad');
        $ciudades = $responseCiudades->json();

        // 2. Obtener la lista de departamentos.
        $responseDeptos = ApiHelper::getAll('/departamento');
        $departamentos = $responseDeptos->json();

        // 3. OBTENER LA LISTA DE PAÍSES PARA EL FILTRO.
        $responsePaises = ApiHelper::getAll('/pais');
        $paises = $responsePaises->json();

        return Inertia::render('Parametros/Ciudad/Index', [
            'ciudades' => is_array($ciudades) ? $ciudades : [],
            'departamentos' => is_array($departamentos) ? $departamentos : [],
            'paises' => is_array($paises) ? $paises : [], // <-- ENVIAR PAÍSES
        ]);
    }

    // ... los métodos store, update y destroy no necesitan cambios ...
    public function store(Request $request)
    {
        Log::info('Recibida petición para crear una nueva Ciudad:', $request->all());
        $request->validate([
            'nombre' => 'required|string|max:100',
            'departamento_id' => 'required|integer',
        ]);
        ApiHelper::create('/ciudad', $request->all());
        return redirect()->route('admin.parametros.ciudad.index')->with('flash', [
            'type' => 'success',
            'message' => 'Ciudad creada exitosamente.',
        ]);
    }
    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar la Ciudad ID: {$id}", $request->all());
        $request->validate([
            'nombre' => 'required|string|max:100',
            'departamento_id' => 'required|integer',
        ]);
        ApiHelper::update('/ciudad', $id, $request->all());
        return redirect()->route('admin.parametros.ciudad.index')->with('flash', [
            'type' => 'success',
            'message' => 'Ciudad actualizada exitosamente.',
        ]);
    }
    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar la Ciudad ID: {$id}");
        ApiHelper::delete('/ciudad', $id);
        return redirect()->route('admin.parametros.ciudad.index')->with('flash', [
            'type' => 'success',
            'message' => 'Ciudad eliminada exitosamente.',
        ]);
    }
}
