<?php
require_once(__DIR__ . "/../../modelo/conexion.php");

$id = $_GET['id'];

$sql = "DELETE FROM pedidos WHERE id=$id";
mysqli_query($conexion, $sql);

header("Location: /paginas/admin/pedidos.php?mensaje=eliminado");
exit();