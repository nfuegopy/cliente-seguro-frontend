<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log; // <-- Importante: Facade para logging

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
            'imagen' => 'required|image|max:5120', // 5MB max
            'orden' => 'nullable|integer',
            'enlace_url' => 'nullable|string', // Se valida como string porque puede ser un nombre de ruta
            'texto_boton' => 'nullable|string|max:50',
        ]);

        try {
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
            'imagen' => 'nullable|image|max:5120',
            'orden' => 'nullable|integer',
            'enlace_url' => 'nullable|string',
            'texto_boton' => 'nullable|string|max:50',
        ]);

        try {
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
