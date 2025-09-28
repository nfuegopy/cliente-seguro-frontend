<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <-- 1. Importa el Facade de Log
use Inertia\Inertia;
use Inertia\Response;

class PaisController extends Controller
{
    public function index(): Response
    {
        // Log para la carga de la página (opcional, pero bueno para depuración)
        Log::info('Cargando la página de gestión de Países.');

        $response = ApiHelper::getAll('/pais');
        $paises = $response->json();

        return Inertia::render('Parametros/Pais/Index', [
            'paises' => is_array($paises) ? $paises : [],
        ]);
    }

    public function store(Request $request)
    {
        // --- LOG DETALLADO ---
        Log::info('Recibida petición para crear un nuevo País:', $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        ApiHelper::create('/pais', $request->all());

        return redirect()->route('admin.parametros.pais.index')->with('flash', [
            'type' => 'success',
            'message' => 'País creado exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        // --- LOG DETALLADO ---
        Log::info("Recibida petición para actualizar el País ID: {$id}", $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        ApiHelper::update('/pais', $id, $request->all());

        return redirect()->route('admin.parametros.pais.index')->with('flash', [
            'type' => 'success',
            'message' => 'País actualizado exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        // --- LOG DETALLADO ---
        Log::info("Recibida petición para eliminar el País ID: {$id}");

        ApiHelper::delete('/pais', $id);

        return redirect()->route('admin.parametros.pais.index')->with('flash', [
            'type' => 'success',
            'message' => 'País eliminado exitosamente.',
        ]);
    }
}
