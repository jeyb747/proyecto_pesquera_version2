<?php
require_once("conexion.php");

function verificarUsuario($correo, $password){
    global $conexion;

    $query = "SELECT * FROM usuarios WHERE correo='$correo' AND password ='$password'";
    
    $resultado = mysqli_query($conexion, $query);

    if(!$resultado){
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    return $resultado;
}
?>