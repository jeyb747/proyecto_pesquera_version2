<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

$id = $_POST['id'];
$estado = $_POST['estado'];

$sql = "UPDATE pedidos SET estado='$estado' WHERE id=$id";
mysqli_query($conexion, $sql);

header("Location: /version_final/paginas/admin/pedidos.php?mensaje=actualizado");
exit();