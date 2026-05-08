<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

$id = $_GET['id'];

$sql = "DELETE FROM pedidos WHERE id=$id";
mysqli_query($conexion, $sql);

header("Location: /version_final/paginas/admin/pedidos.php?mensaje=eliminado");
exit();