<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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

function columna_existe($conexion, $tabla, $columna) {
    $stmt = $conexion->prepare("SHOW COLUMNS FROM `$tabla` LIKE ?");
    $stmt->bind_param("s", $columna);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $usuario_id = intval($_GET["usuario_id"] ?? 0);
    $sql = "SELECT * FROM reservas";

    if ($usuario_id > 0) {
        $sql .= " WHERE usuario_id = " . $usuario_id;
    }

    $sql .= " ORDER BY id DESC";
    $resultado = $conexion->query($sql);
    $reservas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $reservas[] = $fila;
    }

    responder(true, "Reservas cargadas", ["reservas" => $reservas]);
}

$usuario_id = intval($_POST["usuario_id"] ?? 0);
$nombre = trim($_POST["nombre"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$fecha = trim($_POST["fecha"] ?? "");
$hora = trim($_POST["hora"] ?? "");
$personas = intval($_POST["personas"] ?? 0);
$observaciones = trim($_POST["observaciones"] ?? "");

if ($usuario_id <= 0 || $nombre === "" || $telefono === "" || $fecha === "" || $hora === "" || $personas <= 0) {
    responder(false, "Completa todos los datos de la reserva");
}

$columnas = ["usuario_id", "fecha", "hora", "personas"];
$valores = [$usuario_id, $fecha, $hora, $personas];
$tipos = "issi";

if (columna_existe($conexion, "reservas", "estado")) {
    $columnas[] = "estado";
    $valores[] = "pendiente";
    $tipos .= "s";
}

if (columna_existe($conexion, "reservas", "nombre")) {
    $columnas[] = "nombre";
    $valores[] = $nombre;
    $tipos .= "s";
}

if (columna_existe($conexion, "reservas", "telefono")) {
    $columnas[] = "telefono";
    $valores[] = $telefono;
    $tipos .= "s";
}

if (columna_existe($conexion, "reservas", "observaciones")) {
    $columnas[] = "observaciones";
    $valores[] = $observaciones;
    $tipos .= "s";
}

$placeholders = implode(",", array_fill(0, count($columnas), "?"));
$sql = "INSERT INTO reservas (`" . implode("`,`", $columnas) . "`) VALUES ($placeholders)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param($tipos, ...$valores);

if ($stmt->execute()) {
    responder(true, "Reserva confirmada con exito", [
        "reserva_id" => $conexion->insert_id
    ]);
}

responder(false, "No se pudo guardar la reserva: " . $conexion->error);
?>
