<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class SeccionesWebController extends Controller
{
    /**
     * Muestra la página principal de gestión de Secciones Web.
     */
    public function index(): Response
    {
        Log::info('SeccionesWebController: Cargando la página de gestión (index).');
        try {
            $response = ApiHelper::getAll('/secciones-web');
            $secciones = $response->json();
            Log::info('SeccionesWebController: Se obtuvieron las secciones desde la API correctamente.');
        } catch (\Exception $e) {
            Log::error('SeccionesWebController: Error al obtener las secciones desde la API.', ['error' => $e->getMessage()]);
            $secciones = [];
        }

        return Inertia::render('Web/SeccionesWeb/Index', [
            'secciones' => is_array($secciones) ? $secciones : [],
        ]);
    }

    /**
     * Almacena una nueva sección web.
     */
    public function store(Request $request)
    {
        Log::info('SeccionesWebController: Recibida petición para crear una nueva sección.', $request->all());

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            // Validamos que sea imagen O svg. 5MB max.
            'imagen' => 'required|file|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'orden' => 'nullable|integer',
            'activo' => 'boolean',
            'texto_boton' => 'nullable|string|max:50',
        ]);

        try {
            // Preparamos los datos. Convertimos el booleano a string 'true'/'false'
            // porque al enviar archivos (multipart), los booleanos a veces viajan como '1'/'0'
            $request->merge([
                'activo' => $request->boolean('activo') ? 'true' : 'false'
            ]);

            ApiHelper::postWithFile('/secciones-web', $request, 'imagen');
            Log::info('SeccionesWebController: La sección fue enviada a la API para su creación.');

        } catch (\Exception $e) {
            Log::error('SeccionesWebController: Falló la llamada a la API para crear la sección.', ['error' => $e->getMessage()]);
            return back()->withErrors(['api_error' => 'No se pudo crear la sección: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.web.secciones-web.index')->with('flash', [
            'type' => 'success',
            'message' => 'Sección web creada exitosamente.',
        ]);
    }

    /**
     * Actualiza una sección web existente.
     */
    public function update(Request $request, string $id)
    {
        Log::info("SeccionesWebController: Se ha recibido una petición de actualización para el ID: {$id}", $request->all());

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            // En update, la imagen es opcional (nullable)
            'imagen' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'orden' => 'nullable|integer',
            'activo' => 'boolean',
            'texto_boton' => 'nullable|string|max:50',
        ]);

        try {
            // Conversión explícita de booleano a string para asegurar compatibilidad con el DTO
            // Usamos merge para sobrescribir el valor en el request antes de enviarlo
            $request->merge([
                'activo' => $request->boolean('activo') ? 'true' : 'false'
            ]);

            ApiHelper::postWithFile("/secciones-web/{$id}", $request, 'imagen');
            Log::info("SeccionesWebController: La sección ID {$id} fue enviada a la API para su actualización.");

        } catch (\Exception $e) {
            Log::error("SeccionesWebController: Falló la llamada a la API para actualizar la sección ID {$id}.", ['error' => $e->getMessage()]);
            return back()->withErrors(['api_error' => 'No se pudo actualizar la sección: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.web.secciones-web.index')->with('flash', [
            'type' => 'success',
            'message' => 'Sección web actualizada exitosamente.',
        ]);
    }

    /**
     * Elimina una sección web.
     */
    public function destroy(string $id)
    {
        Log::info("SeccionesWebController: Recibida petición para eliminar la sección ID: {$id}");

        try {
            ApiHelper::delete('/secciones-web', $id);
            Log::info("SeccionesWebController: La petición de eliminación para la sección ID {$id} fue enviada a la API.");
        } catch (\Exception $e) {
            Log::error("SeccionesWebController: Falló la llamada a la API para eliminar la sección ID {$id}.", ['error' => $e->getMessage()]);
            return back()->withErrors(['api_error' => 'No se pudo eliminar la sección: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.web.secciones-web.index')->with('flash', [
            'type' => 'success',
            'message' => 'Sección web eliminada exitosamente.',
        ]);
    }
}
