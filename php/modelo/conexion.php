<?php
$host = getenv('DB_HOST');
$usuario = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$bd = getenv('DB_NAME');
$puerto = (int) (getenv('DB_PORT') ?: 3306);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = mysqli_init();
    mysqli_ssl_set($conexion, null, null, null, null, null);

    mysqli_real_connect(
        $conexion,
        $host,
        $usuario,
        $password,
        $bd,
        $puerto,
        null,
        MYSQLI_CLIENT_SSL
    );

    mysqli_set_charset($conexion, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('No se pudo conectar a la base de datos.');
}
