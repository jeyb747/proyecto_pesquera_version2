<?php
require_once(__DIR__ . "/../../modelo/conexion.php");

$id = $_GET['id'];

$sql = "DELETE FROM reservas WHERE id='$id'";
mysqli_query($conexion, $sql);

header("Location: /paginas/admin/reservas.php");