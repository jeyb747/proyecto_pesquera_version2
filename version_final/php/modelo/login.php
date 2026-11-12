<?php
require_once("conexion.php");

function verificarUsuario($correo, $password){
    global $conexion;

    if (!is_string($correo) || !is_string($password) || $correo === '' || $password === '') {
        return false;
    }

    $consulta = $conexion->prepare('SELECT id, nombre, correo, password, rol FROM usuarios WHERE correo = ? LIMIT 1');
    $consulta->bind_param('s', $correo);
    $consulta->execute();
    $usuario = $consulta->get_result()->fetch_assoc();

    if (!$usuario) {
        return false;
    }

    $hash = (string) $usuario['password'];
    if (password_verify($password, $hash)) {
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            actualizarHashPassword($conexion, $usuario['id'], $password);
        }
        return $usuario;
    }

    // Migra una contraseña antigua en texto plano al primer inicio de sesión válido.
    if (hash_equals($hash, $password)) {
        actualizarHashPassword($conexion, $usuario['id'], $password);
        return $usuario;
    }

    return false;
}

function actualizarHashPassword(mysqli $conexion, int $usuarioId, string $password): void
{
    $hashNuevo = password_hash($password, PASSWORD_DEFAULT);
    $actualizar = $conexion->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
    $actualizar->bind_param('si', $hashNuevo, $usuarioId);
    $actualizar->execute();
}
?>
