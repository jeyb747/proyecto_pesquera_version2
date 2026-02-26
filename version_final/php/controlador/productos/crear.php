<?php
require_once "../../modelo/conexion.php";

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$categoria = $_POST['categoria'];

$sql = "INSERT INTO productos (nombre, descripcion, precio, categoria) 
        VALUES ('$nombre', '$descripcion', '$precio', '$categoria')";

if ($conexion->query($sql)) {
    header("Location: ../../../paginas/productos.php?mensaje=creado");
} else {
    echo "Error: " . $conexion->error;
}
?>