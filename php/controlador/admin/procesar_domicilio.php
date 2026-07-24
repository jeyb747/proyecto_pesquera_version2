<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    
    // 🟦 CREAR COLUMNA DINÁMICA
    case 'crear_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            $tipo_col = $_POST['tipo_columna'];
            
            if (!empty($nombre_col)) {
                $query = "ALTER TABLE domicilios ADD `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟥 ELIMINAR COLUMNA DINÁMICA
    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            
            // Protección crítica de campos maestros de la tabla domicilios
            $columnas_estables = ['id', 'pedido_id', 'id_pedido', 'direccion', 'telefono', 'estado', 'fecha'];
            
            if (!empty($nombre_col) && !in_array($nombre_col, $columnas_estables)) {
                $query = "ALTER TABLE domicilios DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟩 INSERTAR REGISTRO BASE
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $direccion  = mysqli_real_escape_string($conexion, $_POST['direccion']);
            $telefono   = mysqli_real_escape_string($conexion, $_POST['telefono']);
            $estado     = mysqli_real_escape_string($conexion, $_POST['estado']);
            $pedido_val = intval($_POST['pedido_id']);

            // Compatibilidad por si la tabla usa 'pedido_id' o 'id_pedido'
            $check_col = mysqli_query($conexion, "SHOW COLUMNS FROM domicilios LIKE 'pedido_id'");
            $campo_pedido = (mysqli_num_rows($check_col) > 0) ? 'pedido_id' : 'id_pedido';

            $query = "INSERT INTO domicilios ($campo_pedido, direccion, telefono, estado) 
                      VALUES ($pedido_val, '$direccion', '$telefono', '$estado')";
            mysqli_query($conexion, $query);
        }
        break;

    // ❌ ELIMINAR REGISTRO (FILA)
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            mysqli_query($conexion, "DELETE FROM domicilios WHERE id = $id");
        }
        break;
}

header("Location: /paginas/admin/configuracion/config_domicilios.php");
exit();