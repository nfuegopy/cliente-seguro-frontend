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
    /**
     * Muestra la página de gestión de Productos de Seguro.
     */
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

    /**
     * Almacena un nuevo producto de seguro.
     */
    public function store(Request $request)
    {
        Log::info('Recibida petición para crear un nuevo Producto de Seguro:', $request->all());

        // 1. Validar los datos que llegan de Inertia
        $validatedData = $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'descripcion_corta' => 'nullable|string|max:500',
            'activo' => 'boolean', // Valida (true, false, 1, 0)
            'publicar_en_web' => 'boolean', // Valida (true, false, 1, 0)
            'aseguradora_id' => 'required|integer',
            'tipo_de_seguro_id' => 'required|integer',
        ]);

        // --- INICIO DE LA CORRECCIÓN (Enviar true/false) ---
        // 2. Usar $validatedData como base
        $apiData = $validatedData;

        // 3. Sobreescribir los campos booleanos usando el helper $request->boolean()
        //    Esto asegura que enviemos un booleano (true/false) a la API, no un entero (1/0).
        //    Tu API (DTO de NestJS) espera un booleano.
        $apiData['activo'] = $request->boolean('activo', true); // Default a true si no viene
        $apiData['publicar_en_web'] = $request->boolean('publicar_en_web', false); // Default a false si no viene
        // --- FIN DE LA CORRECCIÓN ---

        // 4. Enviar los datos corregidos a la API
        ApiHelper::create('/productos-seguro', $apiData);

        return redirect()->route('admin.negocio.productosseguro.index')->with('flash', [
            'type' => 'success',
            'message' => 'Producto de Seguro creado exitosamente.',
        ]);
    }

    /**
     * Actualiza un producto de seguro existente.
     */
    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar el Producto de Seguro ID: {$id}", $request->all());

        // 1. Validar los datos que llegan de Inertia
        $validatedData = $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'descripcion_corta' => 'nullable|string|max:500',
            'activo' => 'boolean', // Valida (true, false, 1, 0)
            'publicar_en_web' => 'boolean', // Valida (true, false, 1, 0)
            'aseguradora_id' => 'required|integer',
            'tipo_de_seguro_id' => 'required|integer',
        ]);

        // --- INICIO DE LA CORRECCIÓN (Enviar true/false) ---
        // 2. Usar $validatedData como base
        $apiData = $validatedData;

        // 3. Sobreescribir los campos booleanos con booleanos reales de PHP
        //    Esto soluciona el error 400 "must be a boolean value"
        $apiData['activo'] = $request->boolean('activo');
        $apiData['publicar_en_web'] = $request->boolean('publicar_en_web');
        // --- FIN DE LA CORRECCIÓN ---

        // 4. Enviar los datos corregidos a la API
        ApiHelper::update('/productos-seguro', $id, $apiData);

        return redirect()->route('admin.negocio.productosseguro.index')->with('flash', [
            'type' => 'success',
            'message' => 'Producto de Seguro actualizado exitosamente.',
        ]);
    }

    /**
     * Elimina un producto de seguro.
     */
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
