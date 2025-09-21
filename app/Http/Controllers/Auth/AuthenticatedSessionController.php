<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User; // <-- 1. Importa el modelo User
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- 2. Importa Hash
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator; // <-- Importante cambiar por ValidationException
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str; // <-- 3. Importa Str

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Maneja la petición de autenticación.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        try {
            Log::info('Intentando iniciar sesión para: ' . $request->email);
            $response = ApiHelper::login($request->email, $request->password);
            $data = $response->json();
            Log::info('Respuesta exitosa de la API', $data);

            if (empty($data['user']['rol']['nombre']) || $data['user']['rol']['nombre'] !== 'Administrador') {
                throw ValidationException::withMessages([
                    'email' => 'Acceso denegado. Esta sección es solo para administradores.',
                ]);
            }

            RateLimiter::clear($request->throttleKey());

            // --- LA SOLUCIÓN ESTÁ AQUÍ ---
            // 4. Busca o crea el usuario en la BD de Laravel para la sesión
            $user = User::firstOrCreate(
                ['email' => $data['user']['email']], // Condición de búsqueda
                [ // Datos para crear si el usuario es nuevo en este frontend
                    'name' => $data['user']['nombre'] . ' ' . $data['user']['apellido'],
                    'password' => Hash::make(Str::random(20)), // Contraseña aleatoria, no se usará
                ]
            );

            // 5. Inicia sesión en Laravel con este usuario. ¡ESTO ES LO QUE FALTABA!
            Auth::login($user, $request->boolean('remember'));

            // 6. Guarda los datos de la API en la sesión para usarlos en las vistas
            Session::put('api_token', $data['access_token']);
            Session::put('user_from_api', $data['user']); // Renombrado para evitar conflictos

            $request->session()->regenerate();
            Log::info('Login de administrador exitoso y sesión regenerada para: ' . $request->email);

            return redirect()->intended(route('admin.dashboard', absolute: false));

        } catch (\Illuminate\Http\Client\RequestException $e) {
            RateLimiter::hit($request->throttleKey());
            Log::warning('Fallo de autenticación desde la API para: ' . $request->email);
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget(['api_token', 'user_from_api']);
        return redirect('/');
    }
}
