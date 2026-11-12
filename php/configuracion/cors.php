<?php
/**
 * CORS para clientes web alojados en otro origen.
 * Define CORS_ALLOWED_ORIGINS como una lista separada por comas de orígenes
 * completos, por ejemplo: https://app.example.com,https://admin.example.com
 */
function configurar_cors(array $metodosPermitidos): void
{
    $origen = $_SERVER['HTTP_ORIGIN'] ?? '';
    $configuracion = getenv('CORS_ALLOWED_ORIGINS');
    $origenesPermitidos = $configuracion === false || trim($configuracion) === ''
        ? []
        : array_values(array_filter(array_map('trim', explode(',', $configuracion))));

    header('Vary: Origin');
    $origenAutorizado = $origen !== '' && in_array($origen, $origenesPermitidos, true);

    if ($origenAutorizado) {
        header('Access-Control-Allow-Origin: ' . $origen);
        header('Access-Control-Allow-Methods: ' . implode(', ', $metodosPermitidos));
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Max-Age: 600');
    }

    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        if (!$origenAutorizado) {
            http_response_code(403);
            exit;
        }

        http_response_code(204);
        exit;
    }
}
