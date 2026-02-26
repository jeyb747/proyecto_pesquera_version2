<?php
require_once "../../modelo/conexion.php";

$id = $_GET['id'];

$sql = "DELETE FROM productos WHERE id=$id";

if ($conexion->query($sql)) {
    header("Location: ../../../paginas/productos.php?mensaje=eliminado");
} else {
    echo "Error al eliminar";
}
?>