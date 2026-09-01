<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");
require_once(__DIR__ . "/../../../includes/flash.php");

// 🔒 validar sesión
if (!isset($_SESSION['id'])) {
    header("Location: /index.php");
    exit();
}

// 📦 datos del formulario
$usuario_id = $_SESSION['id'];

$nombre = $_POST['nombre'] ?? '';
$direccion = trim($_POST['direccion'] ?? '');
$telefono = preg_replace('/\s+/', '', $_POST['telefono'] ?? '');
$pago = $_POST['pago'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

$productos = $_POST['productos'] ?? '';
$total = $_POST['total'] ?? 0;

// 🧪 validar datos mínimos
if ($productos == '' || $direccion == '' || !preg_match('/^\d{7,15}$/', $telefono) || $total <= 0) {
    die("❌ Error: datos incompletos del pedido");
}

/* ======================================================
   1. CREAR PEDIDO
====================================================== */

$sql1 = $conexion->prepare('INSERT INTO pedidos (usuario_id, productos, total, fecha) VALUES (?, ?, ?, NOW())');
$sql1->bind_param('isd', $usuario_id, $productos, $total);
$result1 = $sql1->execute();

if (!$result1) {
    die("❌ Error en pedido: " . mysqli_error($conexion));
}

$pedido_id = $conexion->insert_id;

/* ======================================================
   2. CREAR DOMICILIO
====================================================== */

$sql2 = $conexion->prepare('INSERT INTO domicilios (pedido_id, direccion, telefono, estado, repartidor) VALUES (?, ?, ?, "pendiente", NULL)');
$sql2->bind_param('iss', $pedido_id, $direccion, $telefono);
$result2 = $sql2->execute();

if (!$result2) {
    die("❌ Error en domicilio: " . mysqli_error($conexion));
}

/* ======================================================
   3. RESPUESTA FINAL
====================================================== */

flash_set('success', 'Domicilio realizado correctamente. Recibirás tu pedido en la dirección indicada.');
header("Location: /paginas/domicilio.php");
exit();
?>
