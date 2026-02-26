<?php
require_once "../../modelo/conexion.php";

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$categoria = $_POST['categoria'];

$sql = "UPDATE productos 
        SET nombre='$nombre', descripcion='$descripcion', precio='$precio', categoria='$categoria'
        WHERE id=$id";

if ($conexion->query($sql)) {
    header("Location: ../../../paginas/productos.php?mensaje=actualizado");
} else {
    echo "Error al actualizar";
}
?>