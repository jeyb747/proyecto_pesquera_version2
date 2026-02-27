<?php
require_once "../../modelo/conexion.php";

$id = $_GET['id'];

$sql = "DELETE FROM reservas WHERE id=$id";

if ($conexion->query($sql)) {
    header("Location: ../../../paginas/admin/reservas.php");
} else {
    echo "Error: " . $conexion->error;
}
?>