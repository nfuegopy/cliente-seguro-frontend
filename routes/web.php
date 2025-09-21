<?php

use App\Http\Controllers\Admin\GrupoMenuController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\PermisosController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Lee el prefijo desde el archivo .env.
// En tu servidor, será 'clientesegurodemo'.
// En tu localhost, estará vacío ('').
$prefix = env('APP_ROUTE_PREFIX', '');

// Todas tus rutas se definen dentro de este grupo.
// El prefijo se aplicará automáticamente solo si está definido en el .env.
Route::prefix($prefix)->group(function () {


    Route::get('/test-hola', function () {
    return '¡Hola Mundo desde Laravel! La ruta funciona.';
});

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

});
