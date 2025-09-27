<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <-- Importa el Facade de Log
use Inertia\Inertia;
use Inertia\Response;

class UsuarioController extends Controller
{
    // ... (el método index no cambia)
    public function index(): Response
    {
        $responseUsuarios = ApiHelper::getAll('/usuarios');
        $usuarios = $responseUsuarios->json();
        $responseRoles = ApiHelper::getAll('/roles');
        $roles = $responseRoles->json();
        $responseTiposDoc = ApiHelper::getAll('/tipos-documento');
        $tiposDocumento = $responseTiposDoc->json();
        return Inertia::render('Admin/Usuario/Index', [
            'usuarios' => is_array($usuarios) ? $usuarios : [],
            'roles' => is_array($roles) ? $roles : [],
            'tiposDocumento' => is_array($tiposDocumento) ? $tiposDocumento : [],
        ]);
    }


    public function store(Request $request)
    {
        // 1. Validamos los datos como siempre.
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'rol_id' => 'required|integer',
            'persona' => 'required|array',
            'persona.nombre' => 'required|string',
            'persona.apellido' => 'required|string',
            'persona.telefono' => 'nullable|string',
            'persona.direccion' => 'nullable|string',
            'persona.ciudad_id' => 'nullable|integer',
            'persona.documentos' => 'required|array|min:1',
            'persona.documentos.*.tipo_documento_id' => 'required|integer',
            'persona.documentos.*.numero' => 'required|string',
            'persona.documentos.*.fecha_vencimiento' => 'nullable|date',
        ]);

        // --- 2. LOG DETALLADO ANTES DE ENVIAR ---
        // Revisa este log en storage/logs/laravel.log para ver el payload exacto.
        Log::info('Intentando crear usuario con el siguiente payload:', $validatedData);

        try {
            // --- 3. ENVOLVEMOS LA LLAMADA EN UN TRY-CATCH ---
            ApiHelper::create('/usuarios', $validatedData);

            return redirect()->route('admin.usuario.index')->with('flash', [
                'type' => 'success',
                'message' => 'Usuario creado exitosamente.',
            ]);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // --- 4. CAPTURAMOS EL ERROR Y LO MOSTRAMOS ---
            $errorData = $e->response->json();
            $errorMessage = $errorData['message'] ?? 'Ocurrió un error en la API externa.';

            Log::error('Error 500 desde la API al crear usuario.', [
                'api_error' => $errorData,
                'payload_enviado' => $validatedData
            ]);

            // Devolvemos el error a Inertia para que se muestre en el formulario.
            return back()->withErrors(['api_error' => 'Error de la API: ' . $errorMessage]);
        }
    }

    // ... (los métodos update y destroy no necesitan cambios por ahora)
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => 'sometimes|required|email',
            'password' => 'nullable|string|min:8',
            'rol_id' => 'sometimes|required|integer',
        ]);
        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }
        ApiHelper::update('/usuarios', $id, $data);
        return redirect()->route('admin.usuario.index')->with('flash', [
            'type' => 'success',
            'message' => 'Usuario actualizado exitosamente.',
        ]);
    }

    public function destroy(string $id)
    {
        ApiHelper::delete('/usuarios', $id);
        return redirect()->route('admin.usuario.index')->with('flash', [
            'type' => 'success',
            'message' => 'Usuario eliminado exitosamente.',
        ]);
    }
}
