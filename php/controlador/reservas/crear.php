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
$hora = trim($_POST['hora'] ?? '');
$personas = (int)($_POST['personas'] ?? 0);
$observaciones = $_POST['observaciones'] ?? '';

// 🧪 Validar
if (!$nombre || !$telefono || !$fecha || !$hora || $personas < 1 || $personas > 20 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    http_response_code(422); echo "error_datos";
    exit();
}

$fechaReserva = DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);
$erroresFecha = DateTimeImmutable::getLastErrors();
if (!$fechaReserva || ($erroresFecha !== false && ($erroresFecha['warning_count'] > 0 || $erroresFecha['error_count'] > 0))) {
    http_response_code(422); echo "error_datos";
    exit();
}

$hoy = new DateTimeImmutable('today');
$primerDiaDelMesLimite = $hoy->modify('first day of +6 months');
$ultimoDiaDelMesLimite = (int)$primerDiaDelMesLimite->modify('last day of this month')->format('d');
$fechaMaxima = $primerDiaDelMesLimite->setDate(
    (int)$primerDiaDelMesLimite->format('Y'),
    (int)$primerDiaDelMesLimite->format('m'),
    min((int)$hoy->format('d'), $ultimoDiaDelMesLimite)
);
if ($fechaReserva < $hoy || $fechaReserva > $fechaMaxima) {
    http_response_code(422); echo "error_fecha_rango";
    exit();
}

$horaNormalizada = DateTime::createFromFormat('g:i a', strtolower($hora));
if (!$horaNormalizada) $horaNormalizada = DateTime::createFromFormat('H:i', $hora);
if (!$horaNormalizada) { http_response_code(422); echo 'error_hora'; exit(); }
$hora = $horaNormalizada->format('H:i:s');

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
