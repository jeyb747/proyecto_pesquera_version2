<?php
require_once(__DIR__ . "/../../modelo/conexion.php");

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$estado = $_POST['estado'] ?? '';

if ($id !== false && $id !== null && $id > 0 && is_string($estado)) {
    $stmt = $conexion->prepare('UPDATE pedidos SET estado = ? WHERE id = ?');
    $stmt->bind_param('si', $estado, $id);
    $stmt->execute();
}

header("Location: /paginas/admin/pedidos.php?mensaje=actualizado");
exit();
