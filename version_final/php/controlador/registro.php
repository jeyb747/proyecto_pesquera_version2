<?php
session_start();
require_once("../modelo/conexion.php"); // conexión al puerto 3307 incluida

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // encriptar contraseña

    // Verificar si el correo ya existe
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        header("Location: ../paginas/registro.php?error=correo");
        exit();
    }

    // Insertar nuevo usuario
    $insert = $conexion->prepare("INSERT INTO usuarios (nombre, correo, telefono, password) VALUES (?, ?, ?, ?)");
    $insert->bind_param("ssss", $nombre, $correo, $telefono, $password);

    if ($insert->execute()) {
        header("Location: ../../paginas/login.php?exito=1");
        exit();
    } else {
       header("Location: /version_final/index.php");
       exit();
    }
}