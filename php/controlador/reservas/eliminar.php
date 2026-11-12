<?php
require_once(__DIR__ . "/../../modelo/conexion.php");

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id !== false && $id !== null && $id > 0) {
    $stmt = $conexion->prepare('DELETE FROM reservas WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: /paginas/admin/reservas.php");
exit();
