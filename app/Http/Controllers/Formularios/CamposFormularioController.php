<?php

namespace App\Http\Controllers\Formularios;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CamposFormularioController extends Controller
{
    /**
     * Muestra la página de gestión de Campos de Formulario.
     */
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Campos de Formulario.');

        $response = ApiHelper::getAll('/campos-formulario');
        $camposFormulario = $response->json();

        return Inertia::render('Formularios/CamposFormulario/Index', [
            'camposFormulario' => is_array($camposFormulario) ? $camposFormulario : [],
        ]);
    }

    /**
     * Almacena un nuevo campo de formulario.
     */
    public function store(Request $request)
    {
        Log::info('Recibida petición para crear un nuevo Campo de Formulario:', $request->all());

        $request->validate([
            'entidad_destino' => 'required|string|max:100',
            'key_tecnica' => 'required|string|max:100',
            'etiqueta' => 'required|string|max:150',
            'tipo_html' => 'required|string|max:50',
            'placeholder' => 'nullable|string|max:255',
            'api_endpoint_options' => 'nullable|string|max:255',
        ]);

        ApiHelper::create('/campos-formulario', $request->all());

        return redirect()->route('admin.formularios.campos-formulario.index')->with('flash', [
            'type' => 'success',
            'message' => 'Campo de Formulario creado exitosamente.',
        ]);
    }

    /**
     * Actualiza un campo de formulario existente.
     */
    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar el Campo de Formulario ID: {$id}", $request->all());

        $request->validate([
            'entidad_destino' => 'required|string|max:100',
            'key_tecnica' => 'required|string|max:100',
            'etiqueta' => 'required|string|max:150',
            'tipo_html' => 'required|string|max:50',
            'placeholder' => 'nullable|string|max:255',
            'api_endpoint_options' => 'nullable|string|max:255',
        ]);

        ApiHelper::update('/campos-formulario', $id, $request->all());

        return redirect()->route('admin.formularios.campos-formulario.index')->with('flash', [
            'type' => 'success',
            'message' => 'Campo de Formulario actualizado exitosamente.',
        ]);
    }

    /**
     * Elimina un campo de formulario.
     */
    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar el Campo de Formulario ID: {$id}");

        ApiHelper::delete('/campos-formulario', $id);

        return redirect()->route('admin.formularios.campos-formulario.index')->with('flash', [
            'type' => 'success',
            'message' => 'Campo de Formulario eliminado exitosamente.',
        ]);
    }
}
