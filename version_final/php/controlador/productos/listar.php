<?php
require_once __DIR__ . "/../../modelo/conexion.php";

$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);
?>