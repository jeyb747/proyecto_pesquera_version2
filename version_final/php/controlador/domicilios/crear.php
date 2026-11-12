<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

// 🔒 validar sesión
if (!isset($_SESSION['id'])) {
    header("Location: /version_final/index.php");
    exit();
}

// 📦 datos del formulario
$usuario_id = filter_var($_SESSION['id'], FILTER_VALIDATE_INT);
if ($usuario_id === false || $usuario_id <= 0) {
    http_response_code(400);
    exit('Sesión no válida');
}

$nombre = $_POST['nombre'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$pago = $_POST['pago'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

$productos = $_POST['productos'] ?? '';
$total = filter_var($_POST['total'] ?? null, FILTER_VALIDATE_FLOAT);

// 🧪 validar datos mínimos
if (!is_string($productos) || $productos === '' || !is_string($direccion) || $direccion === '' || $total === false || $total <= 0) {
    die("❌ Error: datos incompletos del pedido");
}

/* ======================================================
   1. CREAR PEDIDO
====================================================== */

mysqli_begin_transaction($conexion);
try {
    $pedido = $conexion->prepare('INSERT INTO pedidos (usuario_id, productos, total, fecha) VALUES (?, ?, ?, NOW())');
    $pedido->bind_param('isd', $usuario_id, $productos, $total);
    $pedido->execute();
    $pedido_id = mysqli_insert_id($conexion);

/* ======================================================
   2. CREAR DOMICILIO
====================================================== */

$domicilio = $conexion->prepare("INSERT INTO domicilios (pedido_id, direccion, estado, repartidor) VALUES (?, ?, 'pendiente', NULL)");
$domicilio->bind_param('is', $pedido_id, $direccion);
$domicilio->execute();
    mysqli_commit($conexion);
} catch (mysqli_sql_exception $e) {
    mysqli_rollback($conexion);
    http_response_code(500);
    exit('No fue posible registrar el pedido.');
}

/* ======================================================
   3. RESPUESTA FINAL
====================================================== */

header("Location: /version_final/paginas/domicilio.php?mensaje=ok");
exit();
?>
