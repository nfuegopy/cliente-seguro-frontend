<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MenuRolesController extends Controller
{
    /**
     * Muestra la página de gestión de permisos.
     */
    public function index(): Response
    {
        // 1. Obtener la lista de permisos existentes (menu-rol).
        $responsePermisos = ApiHelper::getAll('/menu-rol');
        $menuroles = $responsePermisos->json();

        // 2. Obtener la lista completa de Menús para el formulario.
        $responseMenus = ApiHelper::getAll('/menu');
        $menus = $responseMenus->json();

        // 3. Obtener la lista completa de Roles para el formulario.
        $responseRoles = ApiHelper::getAll('/roles');
        $roles = $responseRoles->json();

        // 4. Enviar todos los datos a la vista de Vue.
        return Inertia::render('Admin/MenuRoles/Index', [
            'menuroles' => is_array($menuroles) ? $menuroles : [],
            'menus' => is_array($menus) ? $menus : [],
            'roles' => is_array($roles) ? $roles : [],
        ]);
    }

    /**
     * Guarda un nuevo permiso.
     */
    public function store(Request $request)
    {
        // Valida que los datos necesarios lleguen del frontend.
        $request->validate([
            'menu_id' => 'required|integer',
            'rol_id' => 'required|integer',
            'permitir_listar' => 'boolean',
            'permitir_guardar' => 'boolean',
            'permitir_editar' => 'boolean',
            'permitir_eliminar' => 'boolean',
        ]);

        // Envía la petición de creación a la API.
        ApiHelper::create('/menu-rol', $request->all());

        return redirect()->route('admin.menuroles.index')->with('flash', [
            'type' => 'success',
            'message' => 'Permiso asignado exitosamente.',
        ]);
    }

    /**
     * Actualiza un permiso existente.
     */
    public function update(Request $request, string $id)
    {
        // Para la actualización, solo validamos los permisos que se pueden cambiar.
        $request->validate([
            'permitir_listar' => 'boolean',
            'permitir_guardar' => 'boolean',
            'permitir_editar' => 'boolean',
            'permitir_eliminar' => 'boolean',
        ]);

        // La API es lo suficientemente inteligente como para actualizar solo los campos enviados.
        ApiHelper::update('/menu-rol', $id, $request->all());

         return redirect()->route('admin.menuroles.index')->with('flash', [
            'type' => 'success',
            'message' => 'Permiso actualizado exitosamente.',
        ]);
    }

    /**
     * Elimina un permiso.
     */
    public function destroy(string $id)
    {
        ApiHelper::delete('/menu-rol', $id);

        return redirect()->route('admin.menuroles.index')->with('flash', [
            'type' => 'success',
            'message' => 'Permiso eliminado exitosamente.',
        ]);
    }
}
