<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ClientLoginController;
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
require __DIR__.'/auth.php';
