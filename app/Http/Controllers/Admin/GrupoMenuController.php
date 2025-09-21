<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GrupoMenuController extends Controller
{
    public function index(): Response
    {
        $response = ApiHelper::getAll('/grupo-menu');
        return Inertia::render('Admin/GrupoMenu/Index', [
            'gruposMenu' => $response->json(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string',
        ]);

        ApiHelper::create('/grupo-menu', $request->all());
        return redirect()->route('admin.grupo-menu.index')->with('success', 'Grupo de Menú creado.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string',
        ]);

        ApiHelper::update('/grupo-menu', $id, $request->all());
        return redirect()->route('admin.grupo-menu.index')->with('success', 'Grupo de Menú actualizado.');
    }

    public function destroy($id)
    {
        ApiHelper::delete('/grupo-menu', $id);
        return redirect()->route('admin.grupo-menu.index')->with('success', 'Grupo de Menú eliminado.');
    }
}
