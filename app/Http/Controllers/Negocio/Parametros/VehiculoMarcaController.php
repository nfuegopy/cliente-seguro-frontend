<?php

namespace App\Http\Controllers\Negocio\Parametros;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class VehiculoMarcaController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Marcas de Vehículos.');

        $response = ApiHelper::getAll('/vehiculo-marcas');
        $vehiculoMarcas = $response->json();

        return Inertia::render('Negocio/Parametros/VehiculoMarca/Index', [
            'vehiculoMarcas' => is_array($vehiculoMarcas) ? $vehiculoMarcas : [],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Recibida petición para crear una nueva Marca de Vehículo:', $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        ApiHelper::create('/vehiculo-marcas', $request->all());

        return redirect()->route('admin.negocio.parametros.vehiculomarca.index')->with('flash', [
            'type' => 'success',
            'message' => 'Marca de Vehículo creada exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar la Marca de Vehículo ID: {$id}", $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        ApiHelper::update('/vehiculo-marcas', $id, $request->all());

        return redirect()->route('admin.negocio.parametros.vehiculomarca.index')->with('flash', [
            'type' => 'success',
            'message' => 'Marca de Vehículo actualizada exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar la Marca de Vehículo ID: {$id}");

        ApiHelper::delete('/vehiculo-marcas', $id);

        return redirect()->route('admin.negocio.parametros.vehiculomarca.index')->with('flash', [
            'type' => 'success',
            'message' => 'Marca de Vehículo eliminada exitosamente.',
        ]);
    }
}
