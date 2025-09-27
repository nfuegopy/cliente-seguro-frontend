<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $response = ApiHelper::getAll('/menu');
        $menus = $response->json();

        $responseGrupoMenu = ApiHelper::getAll('/grupo-menu');
        $grupoMenus = $responseGrupoMenu->json();

        $props = [
            'menus' => is_array($menus) ? $menus : [],
            'grupoMenus' => is_array($grupoMenus) ? $grupoMenus : [],
            'flash' => session('flash')
        ];

        return Inertia::render('Admin/Menu/Index', $props);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // CAMBIO: Validar 'url' en lugar de 'ruta'
        $request->validate([
            'nombre' => 'required|string|max:100',
            'url' => 'required|string|max:100', // <-- CORREGIDO
            'icono' => 'nullable|string|max:50',
            'grupo_menu_id' => 'required|integer',
        ]);

        $response = ApiHelper::create('/menu', $request->all());

        if ($response->successful()) {
            return redirect()->route('admin.menu.index')->with('flash', [
                'type' => 'success',
                'message' => 'Menú creado exitosamente.',
            ]);
        }

        return redirect()->back()->with('flash', [
            'type' => 'error',
            'message' => $response->json('message') ?? 'Error al crear el menú.',
            'errors' => $response->json('errors') ?? [],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // CAMBIO: Validar 'url' en lugar de 'ruta'
        $request->validate([
            'nombre' => 'required|string|max:100',
            'url' => 'required|string|max:100', // <-- CORREGIDO
            'icono' => 'nullable|string|max:50',
            'grupo_menu_id' => 'required|integer',
        ]);

        $response = ApiHelper::update('/menu', $id, $request->all());

        if ($response->successful()) {
            return redirect()->route('admin.menu.index')->with('flash', [
                'type' => 'success',
                'message' => 'Menú actualizado exitosamente.',
            ]);
        }

        return redirect()->back()->with('flash', [
            'type' => 'error',
            'message' => $response->json('message') ?? 'Error al actualizar el menú.',
            'errors' => $response->json('errors') ?? [],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = ApiHelper::delete('/menu', $id);

        if ($response->successful()) {
            return redirect()->route('admin.menu.index')->with('flash', [
                'type' => 'success',
                'message' => 'Menú eliminado exitosamente.',
            ]);
        }

        return redirect()->back()->with('flash', [
            'type' => 'error',
            'message' => $response->json('message') ?? 'Error al eliminar el menú.',
        ]);
    }
}
