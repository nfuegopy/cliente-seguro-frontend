<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ClientLoginController extends Controller
{
    /**
     * Muestra la vista de login para clientes.
     */
    public function create()
    {
        return Inertia::render('Auth/ClientLogin', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Maneja la petición de login para clientes.
     */
    public function store(LoginRequest $request)
    {
        $apiHelper = new ApiHelper();
        $response = $apiHelper->login($request->email, $request->password);

        if ($response->failed()) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $apiUser = $response->json()['user'];
        $request->session()->put('api_token', $response->json()['access_token']);
        $request->session()->put('user', $apiUser);

        $request->session()->regenerate();

        // Redirige al dashboard de clientes (crearemos esta ruta más adelante)
        return redirect()->intended('/cliente/dashboard');
    }
}
