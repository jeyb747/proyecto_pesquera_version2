<?php
require_once __DIR__ . "/../../modelo/conexion.php";

$id = $_GET['id'];

$sql = "DELETE FROM productos WHERE id=$id";

if ($conexion->query($sql)) {
    header("Location: /paginas/admin/productos.php?mensaje=eliminado");
} else {
    echo "Error al eliminar";
}
?>