<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_tabla']) && is_string($_POST['nombre_tabla'])) {
    $nombre_tabla = $_POST['nombre_tabla'];
    
    $nombres_cols = $_POST['nombre_col'] ?? [];
    $tipos_cols = $_POST['tipo_col'] ?? [];
    
    $columnas = ["`id` INT(11) NOT NULL AUTO_INCREMENT"];
    $tipos_permitidos = ['VARCHAR(255) DEFAULT NULL', 'TEXT DEFAULT NULL', 'INT DEFAULT 0', 'DECIMAL(10,2) DEFAULT 0.00', 'DATE DEFAULT NULL'];
    $columnas_vistas = ['id'];
    
    // Agregar las columnas dinámicas que envió el formulario
    if (preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_tabla) && is_array($nombres_cols) && is_array($tipos_cols)) {
        foreach ($nombres_cols as $i => $nombre_columna) {
            $tipo = $tipos_cols[$i] ?? '';
            if (is_string($nombre_columna) && preg_match('/\A[a-zA-Z][a-zA-Z0-9_]{0,63}\z/D', $nombre_columna)
                && !in_array(strtolower($nombre_columna), $columnas_vistas, true)
                && is_string($tipo) && in_array($tipo, $tipos_permitidos, true)) {
                $columnas_vistas[] = strtolower($nombre_columna);
                $columnas[] = "`$nombre_columna` $tipo";
            }
        }

        $sql_columnas = implode(', ', $columnas) . ', PRIMARY KEY (`id`)';
        $query_final = "CREATE TABLE `$nombre_tabla` ($sql_columnas) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        mysqli_query($conexion, $query_final);
    }
}

// 🔄 Redireccionar de vuelta a la página del constructor estructural
header("Location: /paginas/admin/configuracion/crear_tabla.php");
exit();
