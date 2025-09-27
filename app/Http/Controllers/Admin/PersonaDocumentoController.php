<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonaDocumentoController extends Controller
{
    /**
     * Muestra los documentos. Podría ser una vista independiente o no usarse
     * si se gestiona dentro de otro módulo como 'Personas'.
     */
    public function index(): Response
    {
        $response = ApiHelper::getAll('/persona-documentos');
        $personaDocumentos = $response->json();

        return Inertia::render('Admin/PersonaDocumento/Index', [
            'personaDocumentos' => is_array($personaDocumentos) ? $personaDocumentos : [],
        ]);
    }

    /**
     * Guarda un nuevo documento para una persona.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|max:50',
            'fecha_vencimiento' => 'nullable|date',
            'persona_id' => 'required|integer',
            'tipo_documento_id' => 'required|integer',
        ]);

        ApiHelper::create('/persona-documentos', $request->all());

        // Usualmente redirigirías a la página de la persona, aquí volvemos al index como placeholder.
        return redirect()->route('admin.personadocumento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Documento de persona guardado.',
        ]);
    }

    /**
     * Actualiza un documento existente.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'numero' => 'required|string|max:50',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        ApiHelper::update('/persona-documentos', $id, $request->all());

         return redirect()->route('admin.personadocumento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Documento de persona actualizado.',
        ]);
    }

    /**
     * Elimina un documento de una persona.
     */
    public function destroy(string $id)
    {
        ApiHelper::delete('/persona-documentos', $id);

        return redirect()->route('admin.personadocumento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Documento de persona eliminado.',
        ]);
    }
}
