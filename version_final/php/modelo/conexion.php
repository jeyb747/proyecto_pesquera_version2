<?php
$host = getenv('DB_HOST');
$usuario = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$bd = getenv('DB_NAME');
$puerto = filter_var(getenv('DB_PORT') ?: '3307', FILTER_VALIDATE_INT);
$ssl = getenv('DB_SSL');
$usarSsl = $ssl === false || strtolower($ssl) !== 'false';

if (!$host || !$usuario || $password === false || $password === '' || !$bd || $puerto === false) {
    http_response_code(500);
    exit('La configuración de base de datos está incompleta.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = mysqli_init();
    $opciones = 0;
    if ($usarSsl) {
        mysqli_ssl_set($conexion, null, null, null, null, null);
        $opciones = MYSQLI_CLIENT_SSL;
    }
    mysqli_real_connect($conexion, $host, $usuario, $password, $bd, $puerto, null, $opciones);
    mysqli_set_charset($conexion, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('No se pudo conectar a la base de datos.');
}
?>
