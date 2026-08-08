<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    exit();
}

require_once __DIR__ . "/../php/modelo/conexion.php";

function responder($success, $mensaje, $extra = []) {
    echo json_encode(array_merge([
        "success" => $success,
        "mensaje" => $mensaje
    ], $extra));
    exit();
}

$nombre = trim($_POST["nombre"] ?? "");
$tipo_documento = intval($_POST["tipo_documento"] ?? 0);
$numero_documento = trim($_POST["numero_documento"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$password_plano = $_POST["password"] ?? "";

if ($nombre === "" || $tipo_documento <= 0 || $numero_documento === "" || $correo === "" || $telefono === "" || $password_plano === "") {
    responder(false, "Completa todos los campos");
}

$consulta = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
$consulta->bind_param("s", $correo);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows > 0) {
    responder(false, "Este correo ya esta registrado");
}

$password = password_hash($password_plano, PASSWORD_DEFAULT);
$insert = $conexion->prepare("INSERT INTO usuarios (nombre, id_tipo_documento, numero_documento, correo, telefono, password) VALUES (?, ?, ?, ?, ?, ?)");
$insert->bind_param("sissss", $nombre, $tipo_documento, $numero_documento, $correo, $telefono, $password);

if ($insert->execute()) {
    responder(true, "Cuenta creada correctamente", [
        "usuario_id" => $conexion->insert_id
    ]);
}

responder(false, "No se pudo crear la cuenta: " . $conexion->error);
?>
