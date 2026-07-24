<?php
// Config por variables de entorno (Azure App Service) con valores por defecto para desarrollo local.
$host     = getenv("DB_HOST") ?: "localhost";
$usuario  = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASSWORD") ?: "";
$bd       = getenv("DB_NAME") ?: "la_pesquera";
$puerto   = (int) (getenv("DB_PORT") ?: 3306);

$conexion = new mysqli($host, $usuario, $password, $bd, $puerto);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
