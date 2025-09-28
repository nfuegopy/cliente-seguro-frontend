<?php

namespace App\Http\Controllers\Parametros;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class DepartamentoController extends Controller
{
    public function index(): Response
    {
        Log::info('Cargando la página de gestión de Departamentos.');

        // 1. Obtener la lista de departamentos existentes.
        $responseDeptos = ApiHelper::getAll('/departamento');
        $departamentos = $responseDeptos->json();

        // 2. Obtener la lista completa de Países para el formulario (Dropdown).
        $responsePaises = ApiHelper::getAll('/pais');
        $paises = $responsePaises->json();

        // 3. Enviar ambos a la vista de Vue.
        return Inertia::render('Parametros/Departamento/Index', [
            'departamentos' => is_array($departamentos) ? $departamentos : [],
            'paises' => is_array($paises) ? $paises : [],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Recibida petición para crear un nuevo Departamento:', $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
            'pais_id' => 'required|integer', // <-- Validación de la relación
        ]);

        ApiHelper::create('/departamento', $request->all());

        return redirect()->route('admin.parametros.departamento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Departamento creado exitosamente.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info("Recibida petición para actualizar el Departamento ID: {$id}", $request->all());

        $request->validate([
            'nombre' => 'required|string|max:100',
            'pais_id' => 'required|integer', // <-- Validación de la relación
        ]);

        ApiHelper::update('/departamento', $id, $request->all());

        return redirect()->route('admin.parametros.departamento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Departamento actualizado exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        Log::info("Recibida petición para eliminar el Departamento ID: {$id}");

        ApiHelper::delete('/departamento', $id);

        return redirect()->route('admin.parametros.departamento.index')->with('flash', [
            'type' => 'success',
            'message' => 'Departamento eliminado exitosamente.',
        ]);
    }
}
