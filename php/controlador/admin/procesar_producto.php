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
            $nombre_col = $_POST['nombre_columna'] ?? '';
            $tipo_col = $_POST['tipo_columna'] ?? '';
            
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && in_array($tipo_col, ['VARCHAR(255) DEFAULT NULL', 'TEXT DEFAULT NULL', 'INT DEFAULT 0', 'DECIMAL(10,2) DEFAULT 0.00', 'DATE DEFAULT NULL'], true)) {
                $query = "ALTER TABLE productos ADD COLUMN `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟥 ELIMINAR COLUMNA FÍSICA DINÁMICA
    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = $_POST['nombre_columna'] ?? '';
            
            // Protección crítica de campos maestros de la tabla productos
            $columnas_vitales = ['id', 'nombre', 'descripcion', 'precio', 'stock', 'estado', 'imagen', 'fecha_creacion'];
            
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && !in_array($nombre_col, $columnas_vitales, true)) {
                $query = "ALTER TABLE productos DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟩 INSERTAR NUEVO REGISTRO EN LA TABLA
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
            $estado = filter_var($_POST['estado'] ?? null, FILTER_VALIDATE_INT);
            if (is_string($nombre) && is_string($descripcion) && $precio !== false && $stock !== false && $estado !== false) {
                $stmt = $conexion->prepare('INSERT INTO productos (nombre, descripcion, precio, stock, estado) VALUES (?, ?, ?, ?, ?)');
                $stmt->bind_param('ssdii', $nombre, $descripcion, $precio, $stock, $estado);
                $stmt->execute();
            }
        }
        break;

    // ❌ ELIMINAR FILA DE PRODUCTO
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if ($id !== false && $id > 0) {
                $stmt = $conexion->prepare('DELETE FROM productos WHERE id = ?');
                $stmt->bind_param('i', $id);
                $stmt->execute();
            }
        }
        break;
}

// 🔄 REGRESO CON MEDIDAS VISUALES CORRECTAS
header("Location: /paginas/admin/configuracion/config_productos.php");
exit();
