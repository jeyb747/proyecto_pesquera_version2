<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . "/../php/modelo/conexion.php";

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM usuarios WHERE correo = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    if (password_verify($password, $usuario['password'])) {

        unset($usuario['password']);

        echo json_encode([
            "success" => true,
            "usuario" => $usuario
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "mensaje" => "Contraseña incorrecta"
        ]);
    }

} else {

    echo json_encode([
        "success" => false,
        "mensaje" => "Usuario no encontrado"
    ]);
}