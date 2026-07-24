<?php
require_once(__DIR__ . "/conexion.php");

function verificarUsuario($correo, $password){
    global $conexion;

    // Escapamos los datos por seguridad básica
    $correo = mysqli_real_escape_string($conexion, $correo);
    $password = mysqli_real_escape_string($conexion, $password);

    // 🔄 Traemos los datos del usuario e intersectamos el nombre del rol desde la tabla roles
    $query = "SELECT u.*, r.nombre_rol AS rol 
              FROM usuarios u
              INNER JOIN roles r ON u.id_rol = r.id
              WHERE u.correo = '$correo'";
              
    $resultado = mysqli_query($conexion, $query);

    if(!$resultado){
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    if (!$resultado || mysqli_num_rows($resultado) !== 1) return false;
    $usuario = mysqli_fetch_assoc($resultado);
    /* Supports legacy plain-text passwords while new accounts use password_hash. */
    if (!password_verify($password, $usuario['password']) && !hash_equals((string)$usuario['password'], (string)$password)) return false;
    return $usuario;
}
?>
