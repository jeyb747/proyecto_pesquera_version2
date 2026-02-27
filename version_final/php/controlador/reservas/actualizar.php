<?php
require_once "../../modelo/conexion.php";

$id = $_POST['id'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$personas = $_POST['personas'];
$estado = $_POST['estado'];

$sql = "UPDATE reservas 
SET fecha='$fecha', hora='$hora', personas='$personas', estado='$estado'
WHERE id=$id";

if ($conexion->query($sql)) {
    header("Location: ../../../paginas/admin/reservas.php");
} else {
    echo "Error: " . $conexion->error;
}
?>