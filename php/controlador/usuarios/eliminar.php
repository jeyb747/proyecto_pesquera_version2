<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: /paginas/admin/usuarios.php?mensaje=error");
    exit();
}

$sql = "SELECT r.nombre_rol AS rol
        FROM usuarios u
        INNER JOIN roles r ON u.id_rol = r.id
        WHERE u.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || $user['rol'] === 'admin') {
    header("Location: /paginas/admin/usuarios.php?mensaje=error");
    exit();
}

$delete = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();

header("Location: /paginas/admin/usuarios.php?mensaje=eliminado");
exit();
