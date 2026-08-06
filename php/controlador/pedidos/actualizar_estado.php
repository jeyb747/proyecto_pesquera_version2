<?php
require_once(__DIR__ . "/../../modelo/conexion.php");

$id = $_POST['id'];
$estado = $_POST['estado'];

$sql = "UPDATE pedidos SET estado='$estado' WHERE id=$id";
mysqli_query($conexion, $sql);

header("Location: /paginas/admin/pedidos.php?mensaje=actualizado");
exit();