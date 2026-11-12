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
            $nombre_col = $_POST['nombre_columna'] ?? '';
            $tipo_col = $_POST['tipo_columna'] ?? '';
            
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && in_array($tipo_col, ['VARCHAR(255) DEFAULT NULL', 'TEXT DEFAULT NULL', 'INT DEFAULT 0', 'DECIMAL(10,2) DEFAULT 0.00', 'DATE DEFAULT NULL'], true)) {
                $query = "ALTER TABLE domicilios ADD COLUMN `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟥 ELIMINAR COLUMNA DINÁMICA
    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = $_POST['nombre_columna'] ?? '';
            
            // Protección crítica de campos maestros de la tabla domicilios
            $columnas_estables = ['id', 'pedido_id', 'id_pedido', 'direccion', 'telefono', 'estado', 'fecha'];
            
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && !in_array($nombre_col, $columnas_estables, true)) {
                $query = "ALTER TABLE domicilios DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟩 INSERTAR REGISTRO BASE
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $estado = $_POST['estado'] ?? '';
            $pedido_val = filter_var($_POST['pedido_id'] ?? null, FILTER_VALIDATE_INT);

            // Compatibilidad por si la tabla usa 'pedido_id' o 'id_pedido'
            $check_col = mysqli_query($conexion, "SHOW COLUMNS FROM domicilios LIKE 'pedido_id'");
            $usa_pedido_id = mysqli_num_rows($check_col) > 0;

            if ($pedido_val !== false && is_string($direccion) && is_string($telefono) && is_string($estado)) {
                $sql = $usa_pedido_id
                    ? 'INSERT INTO domicilios (pedido_id, direccion, telefono, estado) VALUES (?, ?, ?, ?)'
                    : 'INSERT INTO domicilios (id_pedido, direccion, telefono, estado) VALUES (?, ?, ?, ?)';
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('isss', $pedido_val, $direccion, $telefono, $estado);
                $stmt->execute();
            }
        }
        break;

    // ❌ ELIMINAR REGISTRO (FILA)
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if ($id !== false && $id > 0) {
                $stmt = $conexion->prepare('DELETE FROM domicilios WHERE id = ?');
                $stmt->bind_param('i', $id);
                $stmt->execute();
            }
        }
        break;
}

header("Location: /paginas/admin/configuracion/config_domicilios.php");
exit();
