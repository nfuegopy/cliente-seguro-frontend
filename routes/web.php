<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\Admin\RolesController;
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
Route::get('/admin/roles', [RolesController::class, 'index'])->name('admin.roles.index');
Route::post('/admin/roles', [RolesController::class, 'store'])->name('admin.roles.store');
Route::patch('/admin/roles/{id}', [RolesController::class, 'update'])->name('admin.roles.update'); // <-- AÑADE ESTA
Route::delete('/admin/roles/{id}', [RolesController::class, 'destroy'])->name('admin.roles.destroy'); // <-- Y ESTA


});




require __DIR__.'/auth.php';
