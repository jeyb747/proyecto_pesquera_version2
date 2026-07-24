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
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            $tipo_col = $_POST['tipo_columna']; // Validado por el option del select
            
            if (!empty($nombre_col)) {
                $query = "ALTER TABLE usuarios ADD `$nombre_col` $tipo_col";
                mysqli_query($conexion, $query);
            }
        }
        break;

    case 'eliminar_columna':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_col = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_columna']);
            
            // Protección extra ante inyecciones o borrado de llaves primarias
            if (!empty($nombre_col) && !in_array($nombre_col, ['id', 'nombre', 'correo', 'password', 'id_rol'])) {
                $query = "ALTER TABLE usuarios DROP COLUMN `$nombre_col`";
                mysqli_query($conexion, $query);
            }
        }
        break;

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
            $correo   = mysqli_real_escape_string($conexion, $_POST['correo']);
            $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
            $estado   = intval($_POST['estado']);
            $id_rol   = intval($_POST['id_rol']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $query = "INSERT INTO usuarios (nombre, correo, telefono, password, estado, id_rol) VALUES ('$nombre', '$correo', '$telefono', '$password', $estado, $id_rol)";
            mysqli_query($conexion, $query);
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            mysqli_query($conexion, $query = "DELETE FROM usuarios WHERE id = $id");
        }
        break;
}

header("Location: /paginas/admin/configuracion/config_usuarios.php");
exit();