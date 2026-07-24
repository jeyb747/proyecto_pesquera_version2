<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['nombre_tabla'])) {
    // 🧼 Sanitizar el nombre de la tabla
    $nombre_tabla = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_tabla']);
    
    $nombres_cols = $_POST['nombre_col'] ?? [];
    $tipos_cols = $_POST['tipo_col'] ?? [];
    
    // Iniciar la estructura de la consulta SQL basal con su ID maestro auto_increment
    $sql_columnas = "`id` INT(11) NOT NULL AUTO_INCREMENT, ";
    
    // Agregar las columnas dinámicas que envió el formulario
    for ($i = 0; $i < count($nombres_cols); $i++) {
        $col_clean = preg_replace('/[^a-zA-Z0-9_]/', '', $nombres_cols[$i]);
        $tipo_clean = $tipos_cols[$i]; 
        
        if (!empty($col_clean)) {
            $sql_columnas .= "`$col_clean` $tipo_clean, ";
        }
    }
    
    // Declarar la llave primaria final de la tabla
    $sql_columnas .= "PRIMARY KEY (`id`)";
    
    // Construir la consulta completa estructurada
    $query_final = "CREATE TABLE `$nombre_tabla` ($sql_columnas) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    // Ejecutar en el motor MariaDB/MySQL
    mysqli_query($conexion, $query_final);
}

// 🔄 Redireccionar de vuelta a la página del constructor estructural
header("Location: /paginas/admin/configuracion/crear_tabla.php");
exit();