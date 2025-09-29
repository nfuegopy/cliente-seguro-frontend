<?php

namespace App\Http\Controllers\Negocio;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ProductoSeguroController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Productos de Seguro.');

        // 1. Obtener los productos de seguro
        $responseProductos = ApiHelper::getAll('/productos-seguro');
        $productosSeguro = $responseProductos->json();

        // 2. Obtener las aseguradoras para el Dropdown
        $responseAseguradoras = ApiHelper::getAll('/aseguradora');
        $aseguradoras = $responseAseguradoras->json();

        // 3. Obtener los tipos de seguro para el Dropdown
        $responseTipos = ApiHelper::getAll('/tipo-seguro');
        $tiposSeguro = $responseTipos->json();

        return Inertia::render('Negocio/ProductosSeguro/Index', [
            'productosSeguro' => is_array($productosSeguro) ? $productosSeguro : [],
            'aseguradoras' => is_array($aseguradoras) ? $aseguradoras : [],
            'tiposSeguro' => is_array($tiposSeguro) ? $tiposSeguro : [],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Recibida petición para crear un nuevo Producto de Seguro:', $request->all());

        $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'descripcion_corta' => 'nullable|string|max:500',
            'activo' => 'boolean',
            'aseguradora_id' => 'required|integer',
            'tipo_de_seguro_id' => 'required|integer',
        ]);

        ApiHelper::create('/productos-seguro', $request->all());

        return redirect()->route('admin.negocio.productosseguro.index')->with('flash', [
            'type' => 'success',
            'message' => 'Producto de Seguro creado exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar el Producto de Seguro ID: {$id}", $request->all());

        $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'descripcion_corta' => 'nullable|string|max:500',
            'activo' => 'boolean',
            'aseguradora_id' => 'required|integer',
            'tipo_de_seguro_id' => 'required|integer',
        ]);

        ApiHelper::update('/productos-seguro', $id, $request->all());

        return redirect()->route('admin.negocio.productosseguro.index')->with('flash', [
            'type' => 'success',
            'message' => 'Producto de Seguro actualizado exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar el Producto de Seguro ID: {$id}");

        ApiHelper::delete('/productos-seguro', $id);

        return redirect()->route('admin.negocio.productosseguro.index')->with('flash', [
            'type' => 'success',
            'message' => 'Producto de Seguro eliminado exitosamente.',
        ]);
    }
}
