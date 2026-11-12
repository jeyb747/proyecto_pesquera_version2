<?php

header("Content-Type: application/json");
require_once __DIR__ . "/../php/configuracion/cors.php";
configurar_cors(['GET', 'OPTIONS']);

require_once __DIR__ . "/../php/modelo/conexion.php";

$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);

$productos = [];

while($fila = $resultado->fetch_assoc()){
    $productos[] = $fila;
}

echo json_encode($productos);
