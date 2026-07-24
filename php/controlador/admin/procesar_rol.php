<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

// 🔒 PROTEGER ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

// 🔎 DETECCIÓN AUTOMÁTICA DE COLUMNAS REALES
$columnas_query = mysqli_query($conexion, "DESCRIBE roles");
$columnas = [];
while ($col = mysqli_fetch_assoc($columnas_query)) {
    $columnas[] = $col['Field'];
}
$col_id     = $columnas[0];
$col_nombre = $columnas[1];
$tiene_descripcion = isset($columnas[2]);
$col_descripcion   = $tiene_descripcion ? $columnas[2] : null;

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_rol = mysqli_real_escape_string($conexion, $_POST['nombre_rol']);
            
            if ($tiene_descripcion) {
                $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
                $query = "INSERT INTO roles ($col_nombre, $col_descripcion) VALUES ('$nombre_rol', '$descripcion')";
            } else {
                $query = "INSERT INTO roles ($col_nombre) VALUES ('$nombre_rol')";
            }
            mysqli_query($conexion, $query);
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_rol = intval($_POST['id_rol']);
            $nombre_rol = mysqli_real_escape_string($conexion, $_POST['nombre_rol']);
            
            if ($tiene_descripcion) {
                $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
                $query = "UPDATE roles SET $col_nombre = '$nombre_rol', $col_descripcion = '$descripcion' WHERE $col_id = $id_rol";
            } else {
                $query = "UPDATE roles SET $col_nombre = '$nombre_rol' WHERE $col_id = $id_rol";
            }
            mysqli_query($conexion, $query);
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $id_rol = intval($_GET['id']);
            
            $query = "DELETE FROM roles WHERE $col_id = $id_rol";
            mysqli_query($conexion, $query);
        }
        break;
}

// 🔄 REDIRECCIÓN SEGURA
header("Location: /paginas/admin/configuracion/config_roles.php");
exit();