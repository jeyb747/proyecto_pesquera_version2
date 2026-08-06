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
                $query = "ALTER TABLE pedidos ADD `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟥 ELIMINAR COLUMNA DINÁMICA 
    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            
            // Protección de integridad para el motor de la pasarela y relaciones de la tabla pedidos
            $columnas_estables = ['id', 'id_usuario', 'usuario_id', 'fecha', 'total', 'estado', 'id_sesion_pago'];
            
            if (!empty($nombre_col) && !in_array($nombre_col, $columnas_estables)) {
                $query = "ALTER TABLE pedidos DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟩 INSERTAR REGISTRO BASE
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Evaluamos si tu base usa 'usuario_id' o 'id_usuario' por compatibilidad estándar
            $usuario_id = intval($_POST['usuario_id']);
            $total      = floatval($_POST['total']);
            $estado     = mysqli_real_escape_string($conexion, $_POST['estado']);
            $fecha      = date('Y-m-d H:i:s');

            // Intenta detectar dinámicamente cuál campo llave foránea existe
            $check_col = mysqli_query($conexion, "SHOW COLUMNS FROM pedidos LIKE 'usuario_id'");
            $campo_usuario = (mysqli_num_rows($check_col) > 0) ? 'usuario_id' : 'id_usuario';

            $query = "INSERT INTO pedidos ($campo_usuario, total, estado, fecha) VALUES ($usuario_id, $total, '$estado', '$fecha')";
            mysqli_query($conexion, $query);
        }
        break;

    // ❌ ELIMINAR REGISTRO (FILA)
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            mysqli_query($conexion, "DELETE FROM pedidos WHERE id = $id");
        }
        break;
}

header("Location: /paginas/admin/configuracion/config_pedidos.php");
exit();