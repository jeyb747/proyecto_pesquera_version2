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
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            $tipo_col = $_POST['tipo_columna'];
            
            if (!empty($nombre_col)) {
                $query = "ALTER TABLE reservas ADD `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟥 2. ELIMINAR COLUMNA DINÁMICA (ALTER TABLE DROP)
    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            
            // 🔥 Guardarail de seguridad: Bloquea la eliminación de columnas maestras de la lógica de reservas
            $columnas_protegidas = ['id', 'usuario_id', 'fecha', 'hora', 'personas', 'estado', 'nombre', 'telefono', 'observaciones'];
            
            if (!empty($nombre_col) && !in_array($nombre_col, $columnas_protegidas)) {
                $query = "ALTER TABLE reservas DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    // 🟩 3. CREAR NUEVO REGISTRO DE RESERVA
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario_id = intval($_POST['usuario_id']);
            $fecha      = mysqli_real_escape_string($conexion, $_POST['fecha']);
            $hora       = mysqli_real_escape_string($conexion, $_POST['hora']);
            $personas   = intval($_POST['personas']);
            $estado     = mysqli_real_escape_string($conexion, $_POST['estado']);
            $nombre     = mysqli_real_escape_string($conexion, $_POST['nombre']);
            $telefono   = mysqli_real_escape_string($conexion, $_POST['telefono']);

            $query = "INSERT INTO reservas (usuario_id, fecha, hora, personas, estado, nombre, telefono) 
                      VALUES ($usuario_id, '$fecha', '$hora', $personas, '$estado', '$nombre', '$telefono')";
            mysqli_query($conexion, $query);
        }
        break;

    // ❌ 4. ELIMINAR REGISTRO DE RESERVA (FILA)
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $query = "DELETE FROM reservas WHERE id = $id";
            mysqli_query($conexion, $query);
        }
        break;
}

// 🔄 REGRESAR SIEMPRE A LA VISTA DE LA TABLA DE RESERVAS
header("Location: /paginas/admin/configuracion/config_reservas.php");
exit(); 