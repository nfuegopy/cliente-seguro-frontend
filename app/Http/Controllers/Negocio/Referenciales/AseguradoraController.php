<?php

namespace App\Http\Controllers\Negocio\Referenciales;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AseguradoraController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Aseguradoras.');

        // Corregido: El endpoint es '/aseguradora' en singular
        $response = ApiHelper::getAll('/aseguradora');
        $aseguradoras = $response->json();

        return Inertia::render('Negocio/Referenciales/Aseguradoras/Index', [
            'aseguradoras' => is_array($aseguradoras) ? $aseguradoras : [],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Recibida petición para crear una nueva Aseguradora:', $request->all());

        // Corregido: Se añaden las nuevas validaciones
        $request->validate([
            'nombre' => 'required|string|max:150',
            'nit' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        ApiHelper::create('/aseguradora', $request->all());

        return redirect()->route('admin.negocio.referenciales.aseguradoras.index')->with('flash', [
            'type' => 'success',
            'message' => 'Aseguradora creada exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar la Aseguradora ID: {$id}", $request->all());

        // Corregido: Se añaden las nuevas validaciones
        $request->validate([
            'nombre' => 'required|string|max:150',
            'nit' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        ApiHelper::update('/aseguradora', $id, $request->all());

        return redirect()->route('admin.negocio.referenciales.aseguradoras.index')->with('flash', [
            'type' => 'success',
            'message' => 'Aseguradora actualizada exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar la Aseguradora ID: {$id}");

        ApiHelper::delete('/aseguradora', $id);

        return redirect()->route('admin.negocio.referenciales.aseguradoras.index')->with('flash', [
            'type' => 'success',
            'message' => 'Aseguradora eliminada exitosamente.',
        ]);
    }
}
