<?php

namespace App\Http\Controllers\Negocio\Parametros;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class VehiculoModeloController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Modelos de Vehículos.');

        // 1. Obtener la lista de modelos.
        $responseModelos = ApiHelper::getAll('/vehiculo-modelos');
        $vehiculoModelos = $responseModelos->json();

        // 2. Obtener la lista de marcas para el formulario (Dropdown).
        $responseMarcas = ApiHelper::getAll('/vehiculo-marcas');
        $vehiculoMarcas = $responseMarcas->json();

        return Inertia::render('Negocio/Parametros/VehiculoModelo/Index', [
            'vehiculoModelos' => is_array($vehiculoModelos) ? $vehiculoModelos : [],
            'vehiculoMarcas' => is_array($vehiculoMarcas) ? $vehiculoMarcas : [],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Recibida petición para crear un nuevo Modelo de Vehículo:', $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
            'marca_id' => 'required|integer',
        ]);

        ApiHelper::create('/vehiculo-modelos', $request->all());

        return redirect()->route('admin.negocio.parametros.vehiculomodelo.index')->with('flash', [
            'type' => 'success',
            'message' => 'Modelo de Vehículo creado exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar el Modelo de Vehículo ID: {$id}", $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
            'marca_id' => 'required|integer',
        ]);

        ApiHelper::update('/vehiculo-modelos', $id, $request->all());

        return redirect()->route('admin.negocio.parametros.vehiculomodelo.index')->with('flash', [
            'type' => 'success',
            'message' => 'Modelo de Vehículo actualizado exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar el Modelo de Vehículo ID: {$id}");

        ApiHelper::delete('/vehiculo-modelos', $id);

        return redirect()->route('admin.negocio.parametros.vehiculomodelo.index')->with('flash', [
            'type' => 'success',
            'message' => 'Modelo de Vehículo eliminado exitosamente.',
        ]);
    }
}
