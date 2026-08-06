<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

// 🔒 CONTROL DE SEGURIDAD
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    
    // 🟦 CREAR COLUMNA FÍSICA DINÁMICA
    case 'crear_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            $tipo_col = $_POST['tipo_columna'];
            
            if (!empty($nombre_col)) {
                $query = "ALTER TABLE productos ADD `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟥 ELIMINAR COLUMNA FÍSICA DINÁMICA
    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            
            // Protección crítica de campos maestros de la tabla productos
            $columnas_vitales = ['id', 'nombre', 'descripcion', 'precio', 'stock', 'estado', 'imagen', 'fecha_creacion'];
            
            if (!empty($nombre_col) && !in_array($nombre_col, $columnas_vitales)) {
                $query = "ALTER TABLE productos DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟩 INSERTAR NUEVO REGISTRO EN LA TABLA
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre      = mysqli_real_escape_string($conexion, $_POST['nombre']);
            $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
            $precio      = floatval($_POST['precio']);
            $stock       = intval($_POST['stock']);
            $estado      = intval($_POST['estado']);

            $query = "INSERT INTO productos (nombre, descripcion, precio, stock, estado) 
                      VALUES ('$nombre', '$descripcion', $precio, $stock, $estado)";
            mysqli_query($conexion, $query);
        }
        break;

    // ❌ ELIMINAR FILA DE PRODUCTO
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            mysqli_query($conexion, "DELETE FROM productos WHERE id = $id");
        }
        break;
}

// 🔄 REGRESO CON MEDIDAS VISUALES CORRECTAS
header("Location: /paginas/admin/configuracion/config_productos.php");
exit();