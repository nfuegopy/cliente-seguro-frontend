<?php

namespace App\Http\Controllers\Formularios;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ProductoFormularioCamposController extends Controller
{
    /**
     * Muestra la página de gestión de campos asignados a productos.
     */
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Asignación de Campos a Productos.');

        // 1. Obtenemos las asignaciones existentes
        $responseAsignaciones = ApiHelper::getAll('/producto-formulario-campos');
        $asignaciones = $responseAsignaciones->json();

        // 2. Obtenemos el catálogo de Productos de Seguro para el Dropdown
        $responseProductos = ApiHelper::getAll('/productos-seguro');
        $productosSeguro = $responseProductos->json();

        // 3. Obtenemos el catálogo de Campos de Formulario para el Dropdown
        $responseCampos = ApiHelper::getAll('/campos-formulario');
        $camposFormulario = $responseCampos->json();

        return Inertia::render('Formularios/ProductoFormularioCampos/Index', [
            'asignaciones' => is_array($asignaciones) ? $asignaciones : [],
            'productosSeguro' => is_array($productosSeguro) ? $productosSeguro : [],
            'camposFormulario' => is_array($camposFormulario) ? $camposFormulario : [],
        ]);
    }

    /**
     * Almacena una nueva asignación de campo a producto.
     */
    public function store(Request $request)
    {
        Log::info('Recibida petición para crear una nueva Asignación de Campo:', $request->all());

        $validatedData = $request->validate([
            'producto_seguro_id' => 'required|integer',
            'campo_formulario_id' => 'required|integer',
            'es_requerido' => 'boolean',
            'orden' => 'required|integer|min:0',
        ]);

        // --- APLICAMOS LA MISMA CORRECCIÓN BOOLEANA ---
        // El DTO de NestJS espera un booleano
        $apiData = $validatedData;
        $apiData['es_requerido'] = $request->boolean('es_requerido', true);
        // --- FIN DE LA CORRECCIÓN ---

        ApiHelper::create('/producto-formulario-campos', $apiData);

        return redirect()->route('admin.formularios.producto-formulario-campos.index')->with('flash', [
            'type' => 'success',
            'message' => 'Campo asignado al producto exitosamente.',
        ]);
    }

    /**
     * Actualiza una asignación (generalmente 'orden' o 'es_requerido').
     */
    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar la Asignación ID: {$id}", $request->all());

        // Para la actualización, solo permitiremos cambiar 'orden' y 'es_requerido'
        $validatedData = $request->validate([
            'es_requerido' => 'boolean',
            'orden' => 'required|integer|min:0',
        ]);

        // --- APLICAMOS LA MISMA CORRECCIÓN BOOLEANA ---
        $apiData = $validatedData;
        $apiData['es_requerido'] = $request->boolean('es_requerido');
        // --- FIN DE LA CORRECCIÓN ---

        // El DTO de NestJS permite actualizar solo estos campos
        ApiHelper::update('/producto-formulario-campos', $id, $apiData);

        return redirect()->route('admin.formularios.producto-formulario-campos.index')->with('flash', [
            'type' => 'success',
            'message' => 'Asignación actualizada exitosamente.',
        ]);
    }

    /**
     * Elimina una asignación de campo.
     */
    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar la Asignación ID: {$id}");

        ApiHelper::delete('/producto-formulario-campos', $id);

        return redirect()->route('admin.formularios.producto-formulario-campos.index')->with('flash', [
            'type' => 'success',
            'message' => 'Asignación de campo eliminada exitosamente.',
        ]);
    }
}
