<?php

namespace App\Http\Controllers\Negocio;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class NivelCoberturaController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Niveles de Cobertura.');

        $responseNiveles = ApiHelper::getAll('/niveles-cobertura');
        $nivelesCobertura = $responseNiveles->json();

        $responseProductos = ApiHelper::getAll('/productos-seguro');
        $productosSeguro = $responseProductos->json();

        return Inertia::render('Negocio/NivelCobertura/Index', [
            'nivelesCobertura' => is_array($nivelesCobertura) ? $nivelesCobertura : [],
            'productosSeguro' => is_array($productosSeguro) ? $productosSeguro : [],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Recibida petición para crear un nuevo Nivel de Cobertura:', $request->all());

        $request->validate([
            'producto_seguro_id' => 'required|integer',
            'nombre_nivel' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'prima_anual_base' => 'required|numeric',
        ]);

        ApiHelper::create('/niveles-cobertura', $request->all());

        return redirect()->route('admin.negocio.nivelescobertura.index')->with('flash', [
            'type' => 'success',
            'message' => 'Nivel de Cobertura creado exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar el Nivel de Cobertura ID: {$id}", $request->all());

        $request->validate([
            'producto_seguro_id' => 'required|integer',
            'nombre_nivel' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'prima_anual_base' => 'required|numeric',
        ]);

        ApiHelper::update('/niveles-cobertura', $id, $request->all());

        return redirect()->route('admin.negocio.nivelescobertura.index')->with('flash', [
            'type' => 'success',
            'message' => 'Nivel de Cobertura actualizado exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar el Nivel de Cobertura ID: {$id}");

        ApiHelper::delete('/niveles-cobertura', $id);

        return redirect()->route('admin.negocio.nivelescobertura.index')->with('flash', [
            'type' => 'success',
            'message' => 'Nivel de Cobertura eliminado exitosamente.',
        ]);
    }
}
