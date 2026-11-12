<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$rol = trim($_POST['rol'] ?? '');

if (!$id || $nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || $rol === '') {
    header("Location: /version_final/paginas/admin/usuarios.php?mensaje=error");
    exit();
}

$sql = "UPDATE usuarios SET nombre = ?, correo = ?, rol = ? WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    header("Location: /version_final/paginas/admin/usuarios.php?mensaje=error");
    exit();
}

mysqli_stmt_bind_param($stmt, 'sssi', $nombre, $correo, $rol, $id);
$actualizado = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: /version_final/paginas/admin/usuarios.php?mensaje=" . ($actualizado ? 'actualizado' : 'error'));
exit();
