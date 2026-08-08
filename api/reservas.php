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
    $sql = "SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
            LIMIT 1";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al verificar columnas: " . $conexion->error);
    }

    $stmt->bind_param("ss", $tabla, $columna);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}

function vincular_parametros($stmt, $tipos, $valores) {
    $parametros = [];
    $parametros[] = &$tipos;

    foreach ($valores as $indice => $valor) {
        $parametros[] = &$valores[$indice];
    }

    return call_user_func_array([$stmt, "bind_param"], $parametros);
}

try {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $usuario_id = intval($_GET["usuario_id"] ?? 0);
        $sql = "SELECT * FROM reservas";

        if ($usuario_id > 0) {
            $sql .= " WHERE usuario_id = " . $usuario_id;
        }

        $sql .= " ORDER BY id DESC";

        $resultado = $conexion->query($sql);

        if (!$resultado) {
            throw new Exception($conexion->error);
        }

        $reservas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $reservas[] = $fila;
        }

        responder(true, "Reservas cargadas", [
            "reservas" => $reservas
        ]);
    }

    $usuario_id = intval($_POST["usuario_id"] ?? 0);
    $nombre = trim($_POST["nombre"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $fecha = trim($_POST["fecha"] ?? "");
    $hora_recibida = trim($_POST["hora"] ?? "");
    $personas = intval($_POST["personas"] ?? 0);
    $observaciones = trim($_POST["observaciones"] ?? "");

    if (
        $usuario_id <= 0 ||
        $nombre === "" ||
        $telefono === "" ||
        $fecha === "" ||
        $hora_recibida === "" ||
        $personas <= 0
    ) {
        responder(false, "Completa todos los datos de la reserva");
    }

    $hora_objeto = DateTime::createFromFormat(
        "g:i A",
        strtoupper($hora_recibida)
    );

    if (!$hora_objeto) {
        responder(false, "Formato de hora no valido");
    }

    // Convierte, por ejemplo, 1:00 PM a 13:00:00 para MySQL.
    $hora = $hora_objeto->format("H:i:s");

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

    $sql = "INSERT INTO reservas (`" .
        implode("`,`", $columnas) .
        "`) VALUES ($placeholders)";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar reserva: " . $conexion->error);
    }

    if (!vincular_parametros($stmt, $tipos, $valores)) {
        throw new Exception("Error al asignar reserva: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Error al guardar reserva: " . $stmt->error);
    }

    responder(true, "Reserva confirmada con exito", [
        "reserva_id" => $conexion->insert_id
    ]);
} catch (Throwable $e) {
    responder(false, "No se pudo procesar la reserva: " . $e->getMessage());
}
