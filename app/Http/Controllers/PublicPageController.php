<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class PublicPageController extends Controller
{
    /**
     * Muestra una página pública basada en un slug dinámico.
     *
     * @param string $slug El identificador de la página desde la URL.
     * @return \Inertia\Response
     */
    public function show(string $slug): Response
    {
        Log::info("PublicPageController: Buscando sección para el slug '{$slug}'.");

        try {
            // 1. Obtenemos todas las secciones activas
            $responseSecciones = ApiHelper::getPublicAll('/secciones-web/activos');
            $secciones = $responseSecciones->json();

            // 2. Encontramos la sección actual por el slug
            $seccionActual = null;
            foreach ($secciones as $seccion) {
                if (Str::slug($seccion['titulo']) === $slug) {
                    $seccionActual = $seccion;
                    break;
                }
            }

            if (!$seccionActual) {
                Log::warning("PublicPageController: No se encontró ninguna sección para el slug '{$slug}'.");
                abort(404);
            }

            // --- INICIO DE LA LÓGICA CORREGIDA ---

            // 3. Obtener TODOS los "links" de productos publicados (USANDO LA NUEVA RUTA)
            $responseProductosPublicados = ApiHelper::getPublicAll('/seccion-producto-publicado/publicos');
            $todosProductosPublicados = $responseProductosPublicados->json() ?? [];

            // 4. Obtener TODOS los "links" de campos de formulario (USANDO LA NUEVA RUTA)
            $responseCamposFormulario = ApiHelper::getPublicAll('/producto-formulario-campos/publicos');
            $todosCamposDefinidos = $responseCamposFormulario->json() ?? [];

            // 5. Filtrar los productos que pertenecen a ESTA sección
            $productosDeLaSeccionIds = array_filter(
                $todosProductosPublicados,
                fn($p) => $p['seccion_web_id'] === $seccionActual['id']
            );

            $productosConFormulario = [];

            // 6. Para cada producto de esta sección, buscar sus campos
            foreach ($productosDeLaSeccionIds as $productoLink) {
                $productoInfo = $productoLink['productoSeguro']; // La API ya trae esto (eager loading)
                $productoId = $productoInfo['id'];

                // Filtramos los campos que pertenecen a ESTE producto
                $camposParaEsteProducto = array_filter(
                    $todosCamposDefinidos,
                    fn($c) => $c['producto_seguro_id'] === $productoId
                );

                // Ordenamos los campos según la definición de la BD
                usort($camposParaEsteProducto, fn($a, $b) => $a['orden'] <=> $b['orden']);

                // Añadimos los campos encontrados al objeto del producto
                $productoInfo['formulario'] = array_values($camposParaEsteProducto);
                $productosConFormulario[] = $productoInfo;
            }

            // --- FIN DE LA LÓGICA CORREGIDA ---

            Log::info("PublicPageController: Sección encontrada para '{$slug}'. Renderizando vista.", ['seccion' => $seccionActual['titulo']]);

            Log::debug("PublicPageController: Datos enviados al Frontend:", [
                'productos' => $productosConFormulario
            ]);

            return Inertia::render('Public/PaginaDinamica', [
                'seccion' => $seccionActual,
                'productosConFormulario' => $productosConFormulario // <-- 7. Pasamos los datos a la vista
            ]);

        } catch (\Exception $e) {
            Log::error("PublicPageController: Error al intentar obtener datos de la API.", ['error' => $e->getMessage()]);
            abort(500, 'No se pudo contactar al servicio de contenido.');
        }
    }

public function store(Request $request)
{
    // 1. Recibimos el paquete completo desde Vue
    $input = $request->all();
    Log::info('Recibiendo solicitud dinámica:', $input);

    try {
        // --- A. MAPEO DE DATOS FIJOS (Los que todo seguro tiene) ---
        // Extraemos email, nombre y apellido de sus objetos anidados
        $datosParaNest = [
            'producto_seguro_id' => $input['producto_seguro_id'],
            'email_usuario'      => $input['usuario']['email'] ?? null,
            'nombre_usuario'     => $input['persona']['nombre'] ?? 'Invitado', // Valor por defecto si falta
            'apellido_usuario'   => $input['persona']['apellido'] ?? '',

            // HARCODEO TEMPORAL: Como tu form aún no pide nivel, enviamos el ID 1 (Básico)
            // para que NestJS no devuelva error 400.
            'nivel_cobertura_id' => $request->input('nivel_cobertura_id', 4),
        ];

        // --- B. FUSIÓN DINÁMICA (El "Bag" de Datos) ---
        // Aquí metemos todo lo variable: marca de auto, edad para salud, m2 para hogar, etc.
        // No importa de qué formulario venga, todo va a un solo saco.

        $datosVariables = [];

        // Lista de "cajitas" que tu Frontend (PaginaDinamica.vue) sabe generar
        $contenedoresPosibles = [
            'detalles_poliza_auto', // Tu nombre oficial en BD
            'detalles_auto',        // Por seguridad
            'detalles_medico',      // Futuro seguro de salud
            'detalles_hogar',       // Futuro seguro de hogar
            'otros_datos',          // Aquí suele venir el precio o datos extra
            'precio'                // Por si definiste el precio aparte
        ];

        foreach ($contenedoresPosibles as $contenedor) {
            if (isset($input[$contenedor]) && is_array($input[$contenedor])) {
                // array_merge combina las llaves.
                // Ejemplo: Toma "marca_id" de auto y "valor_fiscal" de precio y los junta.
                $datosVariables = array_merge($datosVariables, $input[$contenedor]);
            }
        }

        // 1. Si viene "Precio" (del Frontend), conviértelo a "valor_fiscal" (para NestJS)
        if (isset($datosVariables['Precio']) && !isset($datosVariables['valor_fiscal'])) {
            $datosVariables['valor_fiscal'] = $datosVariables['Precio'];
        }

        // 2. Si viene "anio_fabricacion", asegúrate que NestJS lo vea como "anio" (si tu API lo requiere)
        if (isset($datosVariables['anio_fabricacion'])) {
             $datosVariables['anio'] = $datosVariables['anio_fabricacion'];
        }

        // --- C. ASIGNACIÓN AL DTO ---
        // NestJS espera el campo 'datos_vehiculo' (aunque sea salud, usaremos este campo
        // como contenedor genérico para no romper tu base de datos actual).
        $datosParaNest['datos_vehiculo'] = $datosVariables;

        // Log para que veas cómo quedó el JSON transformado antes de viajar
        Log::debug('Enviando a NestJS:', $datosParaNest);

        // --- D. ENVÍO AL ENDPOINT CORRECTO ---
        // Cambiamos '/polizas' por '/cotizaciones'
        $response = ApiHelper::create('/cotizaciones', $datosParaNest);

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => '¡Cotización generada! Te hemos enviado los detalles a tu correo.'
        ]);

    } catch (\Exception $e) {
        Log::error('Error en PublicPageController:', ['error' => $e->getMessage()]);

        // Si NestJS se queja (ej: falta un campo obligatorio), mostramos mensaje amigable
        return redirect()->back()->withErrors([
            'api_error' => 'No se pudo generar la cotización. Verifique los datos ingresados.'
        ]);
    }
}

}
