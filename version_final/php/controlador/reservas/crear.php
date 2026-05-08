<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

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
$personas = $_POST['personas'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

// 🧪 Validar
if (!$nombre || !$telefono || !$fecha || !$hora || !$personas) {
    echo "error_datos";
    exit();
}

// 🚀 Insertar en BD
$sql = "INSERT INTO reservas 
(usuario_id, nombre, telefono, fecha, hora, personas, observaciones, estado)
VALUES 
('$usuario_id', '$nombre', '$telefono', '$fecha', '$hora', '$personas', '$observaciones', 'pendiente')";

$resultado = mysqli_query($conexion, $sql);

// 📤 Respuesta para JS
if ($resultado) {
    echo "ok";
} else {
    echo "error_bd";
}
?>