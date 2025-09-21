<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RolesController extends Controller
{
    /**
     * Muestra la lista de roles.
     */
    public function index(): Response
    {
        try {
            $response = ApiHelper::getAll('/roles');
            $roles = $response->json();
        } catch (\Exception $e) {
            $roles = [];
        }

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Guarda un nuevo rol.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        try {
            ApiHelper::create('/roles', $request->all());
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'No se pudo crear el rol.']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Actualiza un rol existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        try {
            ApiHelper::update('/roles', $id, $request->all());
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'No se pudo actualizar el rol.']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Elimina un rol.
     */
    public function destroy($id)
    {
        try {
            ApiHelper::delete('/roles', $id);
        } catch (\Exception $e) {
            return back()->withErrors(['api_error' => 'No se pudo eliminar el rol.']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado exitosamente.');
    }
}
