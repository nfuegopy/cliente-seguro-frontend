<?php

namespace App\Http\Controllers\Negocio;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BasesCondicionesController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Bases y Condiciones.');

        $responseCondiciones = ApiHelper::getAll('/bases-condiciones');
        $basesCondiciones = $responseCondiciones->json();

        // Necesitamos los productos para el Dropdown en el formulario
        $responseProductos = ApiHelper::getAll('/productos-seguro');
        $productosSeguro = $responseProductos->json();

        return Inertia::render('Negocio/BasesCondiciones/Index', [
            'basesCondiciones' => is_array($basesCondiciones) ? $basesCondiciones : [],
            'productosSeguro' => is_array($productosSeguro) ? $productosSeguro : [],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Recibida petición para crear una nueva Base y Condición:', $request->all());

        $request->validate([
            'producto_seguro_id' => 'required|integer',
            'contenido' => 'required|string',
            'version' => 'required|string|max:20',
            'fecha_publicacion' => 'required|date',
        ]);

        ApiHelper::create('/bases-condiciones', $request->all());

        return redirect()->route('admin.negocio.basescondiciones.index')->with('flash', [
            'type' => 'success',
            'message' => 'Base y Condición creada exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar la Base y Condición ID: {$id}");

        $request->validate([
            'producto_seguro_id' => 'required|integer',
            'contenido' => 'required|string',
            'version' => 'required|string|max:20',
            'fecha_publicacion' => 'required|date',
        ]);

        ApiHelper::update('/bases-condiciones', $id, $request->all());

        return redirect()->route('admin.negocio.basescondiciones.index')->with('flash', [
            'type' => 'success',
            'message' => 'Base y Condición actualizada exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar la Base y Condición ID: {$id}");

        ApiHelper::delete('/bases-condiciones', $id);

        return redirect()->route('admin.negocio.basescondiciones.index')->with('flash', [
            'type' => 'success',
            'message' => 'Base y Condición eliminada exitosamente.',
        ]);
    }
}
