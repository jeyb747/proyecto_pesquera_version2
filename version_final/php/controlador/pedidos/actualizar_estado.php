<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$estado = $_POST['estado'] ?? '';

if ($id !== false && $id !== null && $id > 0 && is_string($estado) && $estado !== '' && strlen($estado) <= 50) {
    $stmt = $conexion->prepare('UPDATE pedidos SET estado = ? WHERE id = ?');
    $stmt->bind_param('si', $estado, $id);
    $stmt->execute();
}

header("Location: /version_final/paginas/admin/pedidos.php?mensaje=actualizado");
exit();
