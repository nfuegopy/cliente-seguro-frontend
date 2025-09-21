<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\GrupoMenuController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/acceso-clientes', [ClientLoginController::class, 'create'])->name('cliente.login');
Route::post('/acceso-clientes', [ClientLoginController::class, 'store']);





// --- Rutas de Panel de Administración ---
// Este grupo de rutas estará protegido por el middleware 'auth'.
// Solo los usuarios que hayan iniciado sesión podrán acceder.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');
    Route::get('/api/menu', [PermisosController::class, 'obtenerMenuParaRol'])->name('api.menu');


    // --- Rutas para Roles ---
    Route::resource('/admin/roles', RolesController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.roles');
    Route::resource('/admin/menu-grupo', GrupoMenuController::class)
        ->only(['index', 'store', 'update', 'destroy'])
       ->names('admin.grupo-menu');

});




require __DIR__.'/auth.php';
