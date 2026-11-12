<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id !== false && $id !== null && $id > 0) {
    $stmt = $conexion->prepare('DELETE FROM reservas WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: /version_final/paginas/admin/reservas.php");
exit();
