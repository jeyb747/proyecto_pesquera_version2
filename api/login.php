<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . "/../php/modelo/conexion.php";
require_once __DIR__ . "/../php/configuracion/verificacion.php";
asegurar_columnas_verificacion($conexion);

$correo = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';

$sql = "SELECT u.*, r.nombre_rol AS rol
        FROM usuarios u
        LEFT JOIN roles r ON r.id = u.id_rol
        WHERE LOWER(TRIM(u.correo)) = LOWER(?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($password, $usuario['password'])) {
        if ((int)$usuario['correo_verificado'] === 0) {
            echo json_encode(["success" => false, "requiere_verificacion" => true, "mensaje" => "Verifica tu correo antes de iniciar sesión."]);
            exit;
        }
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
