<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';
    $tabla = mysqli_real_escape_string($conexion, $_POST['tabla'] ?? '');

    // ➕ ACCIÓN: INSERTAR FILA (Ej: Agregar un nuevo Rol)
    if ($accion === 'insertar') {
        $columna = mysqli_real_escape_string($conexion, $_POST['columna'] ?? '');
        $valor = mysqli_real_escape_string($conexion, $_POST['valor'] ?? '');

        if (!empty($tabla) && !empty($columna) && !empty($valor)) {
            $sql = "INSERT INTO $tabla ($columna) VALUES ('$valor')";
            mysqli_query($conexion, $sql);
        }
    }

    // ❌ ACCIÓN: ELIMINAR FILA (Ej: Quitar un Rol)
    if ($accion === 'eliminar') {
        $id = intval($_POST['id'] ?? 0);
        if (!empty($tabla) && $id > 0) {
            $sql = "DELETE FROM $tabla WHERE id = $id";
            mysqli_query($conexion, $sql);
        }
    }

    // 🛠️ ACCIÓN: ALTERAR ESQUEMA (Ej: Quitar una columna - Usar con extrema precaución)
    if ($accion === 'alterar_esquema') {
        $tipo_alter = $_POST['tipo_alter'] ?? '';
        $columna_nombre = mysqli_real_escape_string($conexion, $_POST['columna_nombre'] ?? '');

        if ($tipo_alter === 'drop' && !empty($tabla) && !empty($columna_nombre)) {
            $sql = "ALTER TABLE $tabla DROP COLUMN $columna_nombre";
            mysqli_query($conexion, $sql);
        }
    }

    header("Location: /paginas/admin/gestor_tablas.php?mensaje=success");
    exit();
}
?>