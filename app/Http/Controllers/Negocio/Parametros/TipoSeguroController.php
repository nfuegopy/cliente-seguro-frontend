<?php

// El namespace ahora refleja la estructura correcta.
namespace App\Http\Controllers\Negocio\Parametros;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class TipoSeguroController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Tipos de Seguro.');

        $response = ApiHelper::getAll('/tipo-seguro');
        $tiposSeguro = $response->json();

        // La ruta a la vista de Vue también debe reflejar esta nueva estructura.
        return Inertia::render('Negocio/Parametros/TipoSeguro/Index', [
            'tiposSeguro' => is_array($tiposSeguro) ? $tiposSeguro : [],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Recibida petición para crear un nuevo Tipo de Seguro:', $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        ApiHelper::create('/tipo-seguro', $request->all());

        return redirect()->route('admin.negocio.parametros.tiposeguro.index')->with('flash', [
            'type' => 'success',
            'message' => 'Tipo de Seguro creado exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar el Tipo de Seguro ID: {$id}", $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        ApiHelper::update('/tipo-seguro', $id, $request->all());

        return redirect()->route('admin.negocio.parametros.tiposeguro.index')->with('flash', [
            'type' => 'success',
            'message' => 'Tipo de Seguro actualizado exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar el Tipo de Seguro ID: {$id}");

        ApiHelper::delete('/tipo-seguro', $id);

        return redirect()->route('admin.negocio.parametros.tiposeguro.index')->with('flash', [
            'type' => 'success',
            'message' => 'Tipo de Seguro eliminado exitosamente.',
        ]);
    }
}
