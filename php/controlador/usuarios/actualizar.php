<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header("Location: /paginas/admin/usuarios.php?mensaje=error");
    exit();
}

$id = intval($_POST['id']);
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$id_rol = intval($_POST['id_rol'] ?? 0);
$estado = isset($_POST['estado']) ? intval($_POST['estado']) : 1;

if ($id <= 0 || $nombre === '' || $correo === '' || $id_rol <= 0) {
    header("Location: /paginas/admin/editar_usuario.php?id=$id&mensaje=error");
    exit();
}

$sql = "UPDATE usuarios
        SET nombre = ?, correo = ?, telefono = ?, id_rol = ?, estado = ?
        WHERE id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssiii", $nombre, $correo, $telefono, $id_rol, $estado, $id);

if ($stmt->execute()) {
    header("Location: /paginas/admin/usuarios.php?mensaje=actualizado");
} else {
    header("Location: /paginas/admin/editar_usuario.php?id=$id&mensaje=error");
}

exit();
