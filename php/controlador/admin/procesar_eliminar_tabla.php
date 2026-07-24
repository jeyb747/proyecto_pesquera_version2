<?php
session_start();
require_once(__DIR__ . "/../../modelo/conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['nombre_tabla'])) {
    // 🧼 Sanitizar la entrada para evitar inyecciones SQL en comandos estructurales
    $tabla_a_borrar = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['nombre_tabla']);
    
    // Guardarail estricto redundante por seguridad
    $tablas_protegidas = ['usuarios', 'reservas', 'productos', 'pedidos', 'domicilios', 'roles'];
    
    if (!empty($tabla_a_borrar) && !in_array($tabla_a_borrar, $tablas_protegidas)) {
        $query_drop = "DROP TABLE `$tabla_a_borrar`";
        mysqli_query($conexion, $query_drop);
    }
}

// 🔄 Redireccionar de vuelta a la sección para que veas el resultado actualizado
header("Location: /paginas/admin/configuracion/eliminar_tabla.php");
exit();