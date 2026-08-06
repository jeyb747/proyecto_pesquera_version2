<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

// 🔒 Validar sesión
if (!isset($_SESSION['id'])) {
    echo "error_sesion";
    exit();
}

$usuario_id = $_SESSION['id'];

// 📦 Obtener datos
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$personas = (int)($_POST['personas'] ?? 0);
$observaciones = $_POST['observaciones'] ?? '';

// 🧪 Validar
if (!$nombre || !$telefono || !$fecha || !$hora || $personas < 1 || $personas > 20) {
    echo "error_datos";
    exit();
}

// 🚀 Insertar en BD
$sql = $conexion->prepare("INSERT INTO reservas (usuario_id,nombre,telefono,fecha,hora,personas,observaciones,estado) VALUES (?,?,?,?,?,?,?,'pendiente')");
$sql->bind_param('issssis', $usuario_id, $nombre, $telefono, $fecha, $hora, $personas, $observaciones);
$resultado = $sql->execute();

// 📤 Respuesta para JS
if ($resultado) {
    echo "ok";
} else {
    echo "error_bd";
}
?>
