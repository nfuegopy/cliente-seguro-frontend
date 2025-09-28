<?php

use App\Http\Controllers\Admin\GrupoMenuController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuRolesController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\PersonaDocumentoController;
use App\Http\Controllers\Parametros\PaisController;
use App\Http\Controllers\Parametros\DepartamentoController;
use App\Http\Controllers\Parametros\CiudadController;
use App\Http\Controllers\Parametros\TipoDocumentoController;
use App\Http\Controllers\Negocio\Parametros\TipoSeguroController;
use App\Http\Controllers\PermisosController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('clienteseguro')->group(function () {

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

        // Este grupo añade el prefijo de URL /admin y el prefijo de nombre "admin."
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('roles', RolesController::class)->only(['index', 'store', 'update', 'destroy'])->names('roles');
            Route::resource('menu-grupo', GrupoMenuController::class)->only(['index', 'store', 'update', 'destroy'])->names('grupo-menu');
            Route::resource('menu', MenuController::class)->only(['index', 'store', 'update', 'destroy'])->names('menu');
            Route::resource('menuroles', MenuRolesController::class)->only(['index', 'store', 'update', 'destroy'])->names('menuroles');
            Route::resource('usuario', UsuarioController::class)->only(['index', 'store', 'update', 'destroy'])->names('usuario');
            Route::resource('personadocumento', PersonaDocumentoController::class)->only(['index', 'store', 'update', 'destroy'])->names('personadocumento');

            // Grupo de Parámetros del Sistema
            Route::prefix('parametros')->name('parametros.')->group(function () {
                Route::resource('tipodocumento', TipoDocumentoController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->names('tipodocumento');
                Route::resource('pais', PaisController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->names('pais');
                Route::resource('departamento', DepartamentoController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->names('departamento');
                Route::resource('ciudad', CiudadController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->names('ciudad');
            });

            // Grupo para Lógica de Negocio
            Route::prefix('negocio')->name('negocio.')->group(function () {
                Route::prefix('parametros')->name('parametros.')->group(function() {
                    Route::resource('tiposeguro', TipoSeguroController::class)
                        ->only(['index', 'store', 'update', 'destroy'])
                        ->names('tiposeguro');
                });
            });
        });

    }); // <-- ESTA ES LA LLAVE QUE FALTABA PARA CERRAR EL GRUPO MIDDLEWARE

    require __DIR__.'/auth.php';
});
