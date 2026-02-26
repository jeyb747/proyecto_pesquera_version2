<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE correo='$correo' AND password='$password'";
    $resultado = mysqli_query($conexion, $query);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($resultado) > 0) {

        $usuario = mysqli_fetch_assoc($resultado);

        //  Guardar en sesión
        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        //  Redirección por rol
        if ($usuario['rol'] == 'admin') {
            header("Location: ../../paginas/admin/dashboard.php");
        } else {
            header("Location: ../../index.php");
        }
        exit();

    } else {
        echo "<script>
                alert('Correo o contraseña incorrectos');
                window.location='../paginas/login.html';
              </script>";
    }
}
?>