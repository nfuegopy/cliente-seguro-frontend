<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <-- 1. Importa el Facade de Log
use Inertia\Inertia;
use Inertia\Response;

class TipoDocumentoController extends Controller
{
    public function index(): Response
    {
        $response = ApiHelper::getAll('/tipos-documento');
        $tiposDocumento = $response->json();

        return Inertia::render('Parametros/TipoDocumento/Index', [
            'tiposDocumento' => is_array($tiposDocumento) ? $tiposDocumento : [],
        ]);
    }

    public function store(Request $request)
    {
        // --- LOG DETALLADO ---
        Log::info('Recibida petición para crear Tipo de Documento:', $request->all());

        // 2. Valida 'nombre' en lugar de 'descripcion'.
        $request->validate([
            'codigo' => 'required|string|max:10',
            'nombre' => 'required|string|max:100',
        ]);

        ApiHelper::create('/tipos-documento', $request->all());

        return redirect()->route('admin.parametros.tipodocumento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Tipo de Documento creado exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        // --- LOG DETALLADO ---
        Log::info("Recibida petición para actualizar Tipo de Documento ID: {$id}", $request->all());

        // 3. Valida 'nombre' en lugar de 'descripcion'.
        $request->validate([
            'codigo' => 'required|string|max:10',
            'nombre' => 'required|string|max:100',
        ]);

        ApiHelper::update('/tipos-documento', $id, $request->all());

        return redirect()->route('admin.parametros.tipodocumento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Tipo de Documento actualizado exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        // --- LOG DETALLADO ---
        Log::info("Recibida petición para eliminar Tipo de Documento ID: {$id}");

        ApiHelper::delete('/tipos-documento', $id);

        return redirect()->route('admin.parametros.tipodocumento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Tipo de Documento eliminado exitosamente.',
        ]);
    }
}
