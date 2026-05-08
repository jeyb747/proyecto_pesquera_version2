<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/version_final/php/modelo/conexion.php");

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$rol = $_POST['rol'];

$sql = "UPDATE usuarios 
        SET nombre='$nombre', correo='$correo', rol='$rol'
        WHERE id=$id";

mysqli_query($conexion, $sql);

header("Location: /version_final/paginas/admin/usuarios.php?mensaje=actualizado");
exit();