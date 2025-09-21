<?php

use App\Http\Controllers\Admin\GrupoMenuController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\PermisosController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 1. Lee el prefijo 'clientesegurodemo' desde tu archivo .env
$prefix = env('APP_ROUTE_PREFIX', '');

// 2. Aplica ese prefijo a TODAS las rutas definidas dentro de este grupo.
Route::prefix($prefix)->group(function () {

    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    })->name('welcome');

    Route::get('/acceso-clientes', [ClientLoginController::class, 'create'])->name('cliente.login');
    Route::post('/acceso-clientes', [ClientLoginController::class, 'store']);

    // --- Rutas de Panel de Administración ---
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

    // 3. Incluye las rutas de login, registro, etc., que también quedarán bajo el prefijo.
    require __DIR__.'/auth.php';

});
