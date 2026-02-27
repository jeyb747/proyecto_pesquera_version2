<?php
require_once __DIR__ . "/../../modelo/conexion.php";

$sql = "SELECT * FROM reservas";
$resultado = $conexion->query($sql);
?> 