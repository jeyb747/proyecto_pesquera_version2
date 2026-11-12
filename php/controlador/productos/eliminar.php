<?php
require_once __DIR__ . "/../../modelo/conexion.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id !== false && $id !== null && $id > 0) {
    $stmt = $conexion->prepare('DELETE FROM productos WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header("Location: /paginas/admin/productos.php?mensaje=eliminado");
} else {
    header("Location: /paginas/admin/productos.php?mensaje=error");
}
exit();
