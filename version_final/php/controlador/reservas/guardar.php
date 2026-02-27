<?php
session_start();
require_once "../../modelo/conexion.php";

if (!isset($_SESSION['usuario_id'])) {
    echo "Debes iniciar sesión";
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$personas = $_POST['personas'];

$sql = "INSERT INTO reservas (usuario_id, fecha, hora, personas)
        VALUES ('$usuario_id', '$fecha', '$hora', '$personas')";

if ($conexion->query($sql)) {
    header("Location: ../../../paginas/reservas.php");
} else {
    echo "Error al guardar reserva";
}