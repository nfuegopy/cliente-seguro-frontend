<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

/**
 * Clase Helper para centralizar todas las llamadas a la API de Seguros (NestJS).
 */
class ApiHelper
{
    /**
     * Prepara una instancia base del cliente HTTP.
     * Configura la URL base, cabeceras comunes y el manejo de errores.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    private static function client(): PendingRequest
    {
        return Http::baseUrl(config('services.api_seguro.url'))
                      ->acceptJson()
                      // Lanza una excepción automáticamente si la API responde con un error 4xx o 5xx.
                      // Esto simplifica el manejo de errores en los controladores.
                      ->throw();
    }

    /**
     * Prepara un cliente HTTP que incluye la X-API-KEY para endpoints protegidos.
     * Ideal para operaciones de sistema o de back-office.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    private static function clientWithApiKey(): PendingRequest
    {
        return self::client()->withHeaders([
            'X-API-KEY' => config('services.api_seguro.key'),
        ]);
    }

    /**
     * Prepara un cliente HTTP que incluye el token JWT de la sesión del usuario.
     * Esencial para realizar acciones en nombre del usuario logueado.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    private static function clientWithToken(): PendingRequest
    {
        $token = Session::get('api_token');

        return self::client()->withToken($token);
    }


    // --- MÉTODOS PÚBLICOS (SIN AUTENTICACIÓN) ---

    /**
     * Realiza el login de un usuario contra la API.
     * No requiere API Key.
     *
     * @param string $email
     * @param string $password
     * @return \Illuminate\Http\Client\Response
     */
    public static function login(string $email, string $password): Response
    {
        return self::client()->post('/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);
    }

    /**
     * Registra un nuevo usuario en la API.
     * No requiere API Key.
     *
     * @param array $userData
     * @return \Illuminate\Http\Client\Response
     */
    public static function register(array $userData): Response
    {
        // El endpoint /auth/register espera una estructura de datos específica.
        return self::client()->post('/auth/register', $userData);
    }


    // --- MÉTODOS DE LECTURA (GET) ---

    /**
     * Obtiene una lista de recursos.
     * Por defecto, usa la API Key.
     *
     * @param string $endpoint
     * @param array $query
     * @return \Illuminate\Http\Client\Response
     */
    public static function getAll(string $endpoint, array $query = []): Response
    {
        return self::clientWithApiKey()->get($endpoint, $query);
    }

    /**
     * Obtiene un recurso específico por su ID.
     * Por defecto, usa la API Key.
     *
     * @param string $endpoint
     * @param int|string $id
     * @return \Illuminate\Http\Client\Response
     */
    public static function getById(string $endpoint, $id): Response
    {
        return self::clientWithApiKey()->get("{$endpoint}/{$id}");
    }


    // --- MÉTODOS DE ESCRITURA (POST, PATCH, DELETE) ---

    /**
     * Crea un nuevo recurso.
     * Por defecto, usa la API Key.
     *
     * @param string $endpoint
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     */
    public static function create(string $endpoint, array $data): Response
    {
        return self::clientWithApiKey()->post($endpoint, $data);
    }

    /**
     * Crea multiples recursos nuevos.
     * Por defecto, usa la API Key.
     *
     * @param string $endpoint
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     */
    public static function createMassive(string $endpoint, array $data): Response
    {
        return self::clientWithApiKey()->post("{$endpoint}/masivo", $data);
    }

    /**
     * Actualiza un recurso existente.
     * Por defecto, usa la API Key.
     *
     * @param string $endpoint
     * @param int|string $id
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     */
    public static function update(string $endpoint, $id, array $data): Response
    {
        return self::clientWithApiKey()->patch("{$endpoint}/{$id}", $data);
    }

    /**
     * Elimina un recurso.
     * Por defecto, usa la API Key.
     *
     * @param string $endpoint
     * @param int|string $id
     * @return \Illuminate\Http\Client\Response
     */
    public static function delete(string $endpoint, $id): Response
    {
        return self::clientWithApiKey()->delete("{$endpoint}/{$id}");
    }


    // --- MÉTODOS AUTENTICADOS (COMO USUARIO) ---

    /**
     * Obtiene recursos que requieren autenticación de usuario (JWT).
     *
     * @param string $endpoint
     * @param array $query
     * @return \Illuminate\Http\Client\Response
     */
    public static function getAsUser(string $endpoint, array $query = []): Response
    {
        return self::clientWithToken()->get($endpoint, $query);
    }

    /**
     * Crea un recurso en nombre del usuario logueado (JWT).
     *
     * @param string $endpoint
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     */
    public static function createAsUser(string $endpoint, array $data): Response
    {
        return self::clientWithToken()->post($endpoint, $data);
    }


    /**
     * Envía una petición POST con archivos (multipart/form-data).
     *
     * @param string $endpoint El endpoint de la API.
     * @param Request $request La petición original de Laravel que contiene los datos y el archivo.
     * @param string $fileKey El nombre del campo del archivo (ej: 'imagen').
     * @return \Illuminate\Http\Client\Response
     */
    public static function postWithFile(string $endpoint, Request $request, string $fileKey): Response
    {
        $client = self::clientWithApiKey()->asMultipart();

        // Adjuntamos el archivo si existe en la petición.
        if ($request->hasFile($fileKey)) {
            $client->attach(
                $fileKey,
                file_get_contents($request->file($fileKey)),
                $request->file($fileKey)->getClientOriginalName()
            );
        }

        // Adjuntamos el resto de los datos del formulario.
        foreach ($request->except($fileKey) as $key => $value) {
             if ($value !== null) {
                $client->attach($key, $value);
            }
        }

        // Para actualizar, Laravel envía un campo _method='PATCH' que debemos manejar.
        // if ($request->input('_method') === 'PATCH') {
        //     return $client->post("{$endpoint}");
        // }

if ($request->input('_method') === 'PATCH') {
            return $client->patch("{$endpoint}"); // Cambiado de post() a patch()
        }

        return $client->post($endpoint);
    }

    public static function getPublicAll(string $endpoint, array $query = []): Response
    {
        // Usamos el cliente base sin la API Key
        return self::client()->get($endpoint, $query);
    }
}
