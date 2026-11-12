<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 Validar sesión
if (!isset($_SESSION['id'])) {
    echo "error_sesion";
    exit();
}

$usuario_id = filter_var($_SESSION['id'], FILTER_VALIDATE_INT);
if ($usuario_id === false || $usuario_id <= 0) {
    http_response_code(400);
    exit('Sesión no válida');
}

// 📦 Obtener datos
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$personas = $_POST['personas'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

// 🧪 Validar
if (!is_string($nombre) || $nombre === '' || !is_string($telefono) || $telefono === '' || !is_string($fecha) || !is_string($hora) || !is_string($observaciones)) {
    echo "error_datos";
    exit();
}
$personas = filter_var($personas, FILTER_VALIDATE_INT);
if ($personas === false || $personas < 1) {
    echo "error_datos";
    exit();
}

// 🚀 Insertar en BD
$stmt = $conexion->prepare("INSERT INTO reservas (usuario_id, nombre, telefono, fecha, hora, personas, observaciones, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente')");
$stmt->bind_param('issssis', $usuario_id, $nombre, $telefono, $fecha, $hora, $personas, $observaciones);
$resultado = $stmt->execute();

// 📤 Respuesta para JS
if ($resultado) {
    echo "ok";
} else {
    echo "error_bd";
}
?>
