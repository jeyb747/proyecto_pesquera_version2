<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

$id = $_GET['id'];

// ❌ NO BORRAR ADMIN POR ERROR (opcional pero recomendado)
$verificar = mysqli_query($conexion, "SELECT rol FROM usuarios WHERE id=$id");
$user = mysqli_fetch_assoc($verificar);

if ($user['rol'] == 'admin') {
    header("Location: /version_final/paginas/admin/usuarios.php?mensaje=error");
    exit();
}

// 🗑 ELIMINAR
$sql = "DELETE FROM usuarios WHERE id=$id";
mysqli_query($conexion, $sql);

header("Location: /version_final/paginas/admin/usuarios.php?mensaje=eliminado");
exit();