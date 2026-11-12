<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

// 🔒 PROTEGER ACCESO (Solo Administradores)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    
    // 🟦 1. CREAR COLUMNA DINÁMICA (ALTER TABLE ADD)
    case 'crear_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Limpiar el nombre quitando caracteres especiales o espacios molestos
            $nombre_col = $_POST['nombre_columna'] ?? '';
            $tipo_col = $_POST['tipo_columna'] ?? '';
            
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && in_array($tipo_col, ['VARCHAR(255) DEFAULT NULL', 'TEXT DEFAULT NULL', 'INT DEFAULT 0', 'DECIMAL(10,2) DEFAULT 0.00', 'DATE DEFAULT NULL'], true)) {
                $query = "ALTER TABLE reservas ADD COLUMN `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟥 2. ELIMINAR COLUMNA DINÁMICA (ALTER TABLE DROP)
    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = $_POST['nombre_columna'] ?? '';
            
            // 🔥 Guardarail de seguridad: Bloquea la eliminación de columnas maestras de la lógica de reservas
            $columnas_protegidas = ['id', 'usuario_id', 'fecha', 'hora', 'personas', 'estado', 'nombre', 'telefono', 'observaciones'];
            
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && !in_array($nombre_col, $columnas_protegidas, true)) {
                $query = "ALTER TABLE reservas DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟩 3. CREAR NUEVO REGISTRO DE RESERVA
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario_id = filter_var($_POST['usuario_id'] ?? null, FILTER_VALIDATE_INT);
            $personas = filter_var($_POST['personas'] ?? null, FILTER_VALIDATE_INT);
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $estado = $_POST['estado'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            if ($usuario_id !== false && $personas !== false && is_string($fecha) && is_string($hora) && is_string($estado) && is_string($nombre) && is_string($telefono)) {
                $stmt = $conexion->prepare('INSERT INTO reservas (usuario_id, fecha, hora, personas, estado, nombre, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('ississs', $usuario_id, $fecha, $hora, $personas, $estado, $nombre, $telefono);
                $stmt->execute();
            }
        }
        break;

    // ❌ 4. ELIMINAR REGISTRO DE RESERVA (FILA)
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if ($id !== false && $id > 0) {
                $stmt = $conexion->prepare('DELETE FROM reservas WHERE id = ?');
                $stmt->bind_param('i', $id);
                $stmt->execute();
            }
        }
        break;
}

// 🔄 REGRESAR SIEMPRE A LA VISTA DE LA TABLA DE RESERVAS
header("Location: /paginas/admin/configuracion/config_reservas.php");
exit();
