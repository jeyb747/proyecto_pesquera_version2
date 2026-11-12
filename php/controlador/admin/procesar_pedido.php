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
                $query = "ALTER TABLE pedidos ADD COLUMN `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟥 ELIMINAR COLUMNA DINÁMICA 
    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = $_POST['nombre_columna'] ?? '';
            
            // Protección de integridad para el motor de la pasarela y relaciones de la tabla pedidos
            $columnas_estables = ['id', 'id_usuario', 'usuario_id', 'fecha', 'total', 'estado', 'id_sesion_pago'];
            
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && !in_array($nombre_col, $columnas_estables, true)) {
                $query = "ALTER TABLE pedidos DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟩 INSERTAR REGISTRO BASE
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Evaluamos si tu base usa 'usuario_id' o 'id_usuario' por compatibilidad estándar
            $usuario_id = filter_var($_POST['usuario_id'] ?? null, FILTER_VALIDATE_INT);
            $total = filter_var($_POST['total'] ?? null, FILTER_VALIDATE_FLOAT);
            $estado = $_POST['estado'] ?? '';
            $fecha      = date('Y-m-d H:i:s');

            // Intenta detectar dinámicamente cuál campo llave foránea existe
            $check_col = mysqli_query($conexion, "SHOW COLUMNS FROM pedidos LIKE 'usuario_id'");
            $usa_usuario_id = mysqli_num_rows($check_col) > 0;

            if ($usuario_id !== false && $total !== false && is_string($estado)) {
                $sql = $usa_usuario_id
                    ? 'INSERT INTO pedidos (usuario_id, total, estado, fecha) VALUES (?, ?, ?, ?)'
                    : 'INSERT INTO pedidos (id_usuario, total, estado, fecha) VALUES (?, ?, ?, ?)';
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('idss', $usuario_id, $total, $estado, $fecha);
                $stmt->execute();
            }
        }
        break;

    // ❌ ELIMINAR REGISTRO (FILA)
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if ($id !== false && $id > 0) {
                $stmt = $conexion->prepare('DELETE FROM pedidos WHERE id = ?');
                $stmt->bind_param('i', $id);
                $stmt->execute();
            }
        }
        break;
}

header("Location: /paginas/admin/configuracion/config_pedidos.php");
exit();
