<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 validar sesión
if (!isset($_SESSION['id'])) {
    header("Location: /version_final/index.php");
    exit();
}

// 📦 datos del formulario
$usuario_id = $_SESSION['id'];

$nombre = $_POST['nombre'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$pago = $_POST['pago'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

$productos = $_POST['productos'] ?? '';
$total = $_POST['total'] ?? 0;

// 🧪 validar datos mínimos
if ($productos == '' || $direccion == '' || $total <= 0) {
    die("❌ Error: datos incompletos del pedido");
}

/* ======================================================
   1. CREAR PEDIDO
====================================================== */

$sql1 = "INSERT INTO pedidos 
(usuario_id, productos, total, fecha)
VALUES 
('$usuario_id', '$productos', '$total', NOW())";

$result1 = mysqli_query($conexion, $sql1);

if (!$result1) {
    die("❌ Error en pedido: " . mysqli_error($conexion));
}

$pedido_id = mysqli_insert_id($conexion);

/* ======================================================
   2. CREAR DOMICILIO
====================================================== */

$sql2 = "INSERT INTO domicilios 
(pedido_id, direccion, estado, repartidor)
VALUES 
('$pedido_id', '$direccion', 'pendiente', NULL)";

$result2 = mysqli_query($conexion, $sql2);

if (!$result2) {
    die("❌ Error en domicilio: " . mysqli_error($conexion));
}

/* ======================================================
   3. RESPUESTA FINAL
====================================================== */

header("Location: /version_final/paginas/domicilio.php?mensaje=ok");
exit();
?>