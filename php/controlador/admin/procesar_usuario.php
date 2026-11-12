<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    case 'crear_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = $_POST['nombre_columna'] ?? '';
            $tipo_col = $_POST['tipo_columna'] ?? '';
            
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && in_array($tipo_col, ['VARCHAR(255) DEFAULT NULL', 'TEXT DEFAULT NULL', 'INT DEFAULT 0', 'DECIMAL(10,2) DEFAULT 0.00', 'DATE DEFAULT NULL'], true)) {
                $query = "ALTER TABLE usuarios ADD COLUMN `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = $_POST['nombre_columna'] ?? '';
            
            // Protección extra ante inyecciones o borrado de llaves primarias
            if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_col) && !in_array($nombre_col, ['id', 'nombre', 'correo', 'password', 'id_rol'], true)) {
                $query = "ALTER TABLE usuarios DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $estado = filter_var($_POST['estado'] ?? null, FILTER_VALIDATE_INT);
            $id_rol = filter_var($_POST['id_rol'] ?? null, FILTER_VALIDATE_INT);
            $plain_password = $_POST['password'] ?? '';
            if (is_string($nombre) && is_string($correo) && is_string($telefono) && $estado !== false && $id_rol !== false && is_string($plain_password)) {
                $password = password_hash($plain_password, PASSWORD_BCRYPT);
                $stmt = $conexion->prepare('INSERT INTO usuarios (nombre, correo, telefono, password, estado, id_rol) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('ssssii', $nombre, $correo, $telefono, $password, $estado, $id_rol);
                $stmt->execute();
            }
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if ($id !== false && $id > 0) {
                $stmt = $conexion->prepare('DELETE FROM usuarios WHERE id = ?');
                $stmt->bind_param('i', $id);
                $stmt->execute();
            }
        }
        break;
}

header("Location: /paginas/admin/configuracion/config_usuarios.php");
exit();
