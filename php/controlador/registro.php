<?php
session_start();
require_once(__DIR__ . "/../modelo/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibir todos los datos del formulario
    $nombre = $_POST['nombre'];
    $tipo_documento = intval($_POST['tipo_documento']); // Forzar a entero (1, 2, 3 o 4)
    $numero_documento = $_POST['numero_documento']; 
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // encriptar contraseña

    // Verificar si el correo ya existe
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        header("Location: /paginas/registro.php?error=correo");
        exit();
    }

    // Insertar nuevo usuario utilizando la relación de la tabla maestra
    // El formato en bind_param es "sissss" porque el segundo valor (id_tipo_documento) es de tipo Entero (i)
    $insert = $conexion->prepare("INSERT INTO usuarios (nombre, id_tipo_documento, numero_documento, correo, telefono, password) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("sissss", $nombre, $tipo_documento, $numero_documento, $correo, $telefono, $password);

    if ($insert->execute()) {
        header("Location: /paginas/login.php?exito=1");
        exit();
    } else {
        header("Location: /index.php");
        exit();
    }
}