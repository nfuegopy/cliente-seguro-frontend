<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SeccionProductoPublicadoController extends Controller
{
    /**
     * Muestra la página de gestión de productos publicados en secciones.
     */
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Publicación de Productos.');

        // 1. Obtenemos los enlaces ya creados
        $responsePublicados = ApiHelper::getAll('/seccion-producto-publicado');
        $publicaciones = $responsePublicados->json();

        // 2. Obtenemos el catálogo de Secciones Web para el Dropdown
        $responseSecciones = ApiHelper::getAll('/secciones-web');
        $seccionesWeb = $responseSecciones->json();

        // 3. Obtenemos el catálogo de Productos de Seguro para el Dropdown
        $responseProductos = ApiHelper::getAll('/productos-seguro');
        $productosSeguro = $responseProductos->json();

        return Inertia::render('Web/SeccionProductoPublicado/Index', [
            'publicaciones' => is_array($publicaciones) ? $publicaciones : [],
            'seccionesWeb' => is_array($seccionesWeb) ? $seccionesWeb : [],
            'productosSeguro' => is_array($productosSeguro) ? $productosSeguro : [],
        ]);
    }

    /**
     * Almacena una nueva publicación de producto.
     */
    public function store(Request $request)
    {
        Log::info('Recibida petición para crear una nueva Publicación de Producto:', $request->all());

        $request->validate([
            'seccion_web_id' => 'required|integer',
            'producto_seguro_id' => 'required|integer',
            'orden' => 'nullable|integer',
        ]);

        ApiHelper::create('/seccion-producto-publicado', $request->all());

        return redirect()->route('admin.web.seccion-producto-publicado.index')->with('flash', [
            'type' => 'success',
            'message' => 'Producto publicado en la sección exitosamente.',
        ]);
    }

    /**
     * Actualiza el orden de una publicación.
     */
    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar la Publicación ID: {$id}", $request->all());

        // Para este módulo, generalmente solo querremos actualizar el orden.
        $request->validate([
            'orden' => 'required|integer',
        ]);

        // La API permite actualizar todo, pero solo enviaremos el 'orden' desde el formulario de edición.
        ApiHelper::update('/seccion-producto-publicado', $id, [
            'orden' => $request->input('orden'),
        ]);

        return redirect()->route('admin.web.seccion-producto-publicado.index')->with('flash', [
            'type' => 'success',
            'message' => 'Orden de publicación actualizado exitosamente.',
        ]);
    }

    /**
     * Elimina una publicación de producto.
     */
    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar la Publicación ID: {$id}");

        ApiHelper::delete('/seccion-producto-publicado', $id);

        return redirect()->route('admin.web.seccion-producto-publicado.index')->with('flash', [
            'type' => 'success',
            'message' => 'Publicación de producto eliminada exitosamente.',
        ]);
    }
}
