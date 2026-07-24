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
    $sql = "SELECT d.*, p.usuario_id, p.productos, p.total
            FROM domicilios d
            INNER JOIN pedidos p ON d.pedido_id = p.id";

    if ($usuario_id > 0) {
        $sql .= " WHERE p.usuario_id = " . $usuario_id;
    }

    $sql .= " ORDER BY d.id DESC";
    $resultado = $conexion->query($sql);
    $domicilios = [];

    while ($fila = $resultado->fetch_assoc()) {
        $domicilios[] = $fila;
    }

    responder(true, "Domicilios cargados", ["domicilios" => $domicilios]);
}

$usuario_id = intval($_POST["usuario_id"] ?? 0);
$nombre = trim($_POST["nombre"] ?? "");
$direccion = trim($_POST["direccion"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$pago = trim($_POST["pago"] ?? "");
$observaciones = trim($_POST["observaciones"] ?? "");
$productos = trim($_POST["productos"] ?? "");
$total = floatval($_POST["total"] ?? 0);

if ($usuario_id <= 0 || $nombre === "" || $direccion === "" || $telefono === "" || $pago === "" || $productos === "" || $total <= 0) {
    responder(false, "Completa todos los datos del domicilio");
}

$conexion->begin_transaction();

try {
    $pedido_cols = ["usuario_id", "productos", "total", "fecha"];
    $pedido_vals = [$usuario_id, $productos, $total, date("Y-m-d H:i:s")];
    $pedido_tipos = "isds";

    if (columna_existe($conexion, "pedidos", "estado")) {
        $pedido_cols[] = "estado";
        $pedido_vals[] = "pendiente";
        $pedido_tipos .= "s";
    }

    if (columna_existe($conexion, "pedidos", "pago")) {
        $pedido_cols[] = "pago";
        $pedido_vals[] = $pago;
        $pedido_tipos .= "s";
    }

    if (columna_existe($conexion, "pedidos", "observaciones")) {
        $pedido_cols[] = "observaciones";
        $pedido_vals[] = $observaciones;
        $pedido_tipos .= "s";
    }

    $pedido_placeholders = implode(",", array_fill(0, count($pedido_cols), "?"));
    $pedido_sql = "INSERT INTO pedidos (`" . implode("`,`", $pedido_cols) . "`) VALUES ($pedido_placeholders)";
    $pedido_stmt = $conexion->prepare($pedido_sql);
    $pedido_stmt->bind_param($pedido_tipos, ...$pedido_vals);
    $pedido_stmt->execute();
    $pedido_id = $conexion->insert_id;

    $campo_pedido = columna_existe($conexion, "domicilios", "pedido_id") ? "pedido_id" : "id_pedido";
    $dom_cols = [$campo_pedido, "direccion", "estado"];
    $dom_vals = [$pedido_id, $direccion, "pendiente"];
    $dom_tipos = "iss";

    if (columna_existe($conexion, "domicilios", "telefono")) {
        $dom_cols[] = "telefono";
        $dom_vals[] = $telefono;
        $dom_tipos .= "s";
    }

    if (columna_existe($conexion, "domicilios", "nombre")) {
        $dom_cols[] = "nombre";
        $dom_vals[] = $nombre;
        $dom_tipos .= "s";
    }

    if (columna_existe($conexion, "domicilios", "pago")) {
        $dom_cols[] = "pago";
        $dom_vals[] = $pago;
        $dom_tipos .= "s";
    }

    if (columna_existe($conexion, "domicilios", "observaciones")) {
        $dom_cols[] = "observaciones";
        $dom_vals[] = $observaciones;
        $dom_tipos .= "s";
    }

    $dom_placeholders = implode(",", array_fill(0, count($dom_cols), "?"));
    $dom_sql = "INSERT INTO domicilios (`" . implode("`,`", $dom_cols) . "`) VALUES ($dom_placeholders)";
    $dom_stmt = $conexion->prepare($dom_sql);
    $dom_stmt->bind_param($dom_tipos, ...$dom_vals);
    $dom_stmt->execute();
    $domicilio_id = $conexion->insert_id;

    $conexion->commit();

    responder(true, "Pedido confirmado con exito", [
        "pedido_id" => $pedido_id,
        "domicilio_id" => $domicilio_id
    ]);
} catch (Throwable $e) {
    $conexion->rollback();
    responder(false, "No se pudo guardar el domicilio: " . $e->getMessage());
}
?>
