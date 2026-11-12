<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /version_final/index.php");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    header("Location: /version_final/paginas/admin/usuarios.php?mensaje=error");
    exit();
}

// ❌ NO BORRAR ADMIN POR ERROR (opcional pero recomendado)
$verificar = $conexion->prepare('SELECT rol FROM usuarios WHERE id = ?');
$verificar->bind_param('i', $id);
$verificar->execute();
$user = $verificar->get_result()->fetch_assoc();

if (!$user || $user['rol'] === 'admin') {
    header("Location: /version_final/paginas/admin/usuarios.php?mensaje=error");
    exit();
}

// 🗑 ELIMINAR
$eliminar = $conexion->prepare('DELETE FROM usuarios WHERE id = ?');
$eliminar->bind_param('i', $id);
$eliminar->execute();

header("Location: /version_final/paginas/admin/usuarios.php?mensaje=eliminado");
exit();
