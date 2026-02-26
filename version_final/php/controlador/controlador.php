<?php
session_start();
require_once("../modelo/login.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(!isset($_POST['correo']) || !isset($_POST['password'])){
        echo "Faltan datos";
        exit();
    }

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $resultado = verificarUsuario($correo, $password);

    if ($resultado && mysqli_num_rows($resultado) > 0) {

        $usuario = mysqli_fetch_assoc($resultado);

        // 🔥 AQUÍ ESTÁ LA CLAVE
        $_SESSION['usuario'] = $usuario['nombre']; 
        // también puedes usar correo si quieres

        header("Location: ../../index.php");
        exit();

    } else {
        echo "Datos incorrectos";
    }
}
?>