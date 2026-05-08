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

        // ✅ sesión
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol']; 

        // 🔥 REDIRECCIÓN POR ROL
        if ($usuario['rol'] == 'admin') {

            header("Location: http://localhost/version_final/paginas/admin/dashboard.php");

        } elseif ($usuario['rol'] == 'repartidor') {

            header("Location: http://localhost/version_final/paginas/repartidor/domicilios.php");

        } else {

            header("Location: http://localhost/version_final/paginas/menu.php");
        }

        exit();

    } else {
        echo "Datos incorrectos";
    }
}
?>